<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use App\Models\Attribute;
use App\Models\AttributeValue;

use App\Models\Category;
use App\Models\SubCategory;

class AttributeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = Session::get('user_role');
        if (!$role) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $this->requirePermission('attributes', 'view');
        $attributeModel = new Attribute();
        $attributes = $attributeModel->getAll();
        
        $attributeValueModel = new AttributeValue();
        foreach ($attributes as &$attr) {
            $attr['values'] = $attributeValueModel->getByAttributeId($attr['id']);
        }

        $this->render('admin/catalog/attributes/index', [
            'title' => 'Manage Attributes',
            'attributes' => $attributes
        ], 'admin');
    }

    public function create()
    {
        $this->requirePermission('attributes', 'create');
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();

        $this->render('admin/catalog/attributes/create', [
            'title' => 'Add Attribute',
            'categories' => $categories
        ], 'admin');
    }

    public function edit()
    {
        $this->requirePermission('attributes', 'edit');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $attributeModel = new Attribute();
        $attribute = $attributeModel->findById($id);

        if (!$attribute) {
            Session::setFlash('error', 'Attribute not found.');
            $this->redirect('/admin/catalog/attributes');
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();

        $subCategoryModel = new SubCategory();
        $subCategories = [];
        if ($attribute['category_id']) {
            $subCategories = $subCategoryModel->getByCategory($attribute['category_id']);
        }

        $attributeValueModel = new AttributeValue();
        $attributeValues = $attributeValueModel->getByAttributeId($id);

        $this->render('admin/catalog/attributes/edit', [
            'title' => 'Edit Attribute',
            'attribute' => $attribute,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'values' => $attributeValues
        ], 'admin');
    }

    public function store()
    {
        $this->requirePermission('attributes', 'create');
        $isJson = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        $name = trim($_POST['name'] ?? '');
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $subCategoryId = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null;
        $inputType = trim($_POST['input_type'] ?? 'checkbox');
        $code = trim($_POST['attribute_code'] ?? '');

        if (empty($name) || empty($categoryId)) {
            $msg = 'Category and Attribute Name are required.';
            if ($isJson) {
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            Session::setFlash('error', $msg);
            $this->redirect('/admin/catalog/attributes/create');
            return;
        }

        // Auto-generate attribute_code if empty
        if (empty($code)) {
            $code = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $name));
            $code = trim($code, '_');
            if (empty($code)) $code = 'attr_' . time();
        }

        // Ensure unique attribute code
        $db = \Core\Database::getInstance();
        $stmtCode = $db->prepare("SELECT COUNT(*) FROM attributes WHERE attribute_code = ?");
        $stmtCode->execute([$code]);
        if ($stmtCode->fetchColumn() > 0) {
            $code .= '_' . substr(uniqid(), -4);
        }

        $data = [
            'category_id' => $categoryId,
            'sub_category_id' => $subCategoryId,
            'name' => $name,
            'attribute_code' => $code,
            'input_type' => $inputType,
            'description' => trim($_POST['description'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'is_searchable' => isset($_POST['is_searchable']) ? 1 : 0,
            'is_filterable' => isset($_POST['is_filterable']) ? 1 : 0,
            'is_variant' => isset($_POST['is_variant']) ? 1 : 0,
            'show_on_product_form' => isset($_POST['show_on_product_form']) ? 1 : 1,
            'is_visible_on_website' => isset($_POST['is_visible_on_website']) ? 1 : 1
        ];

        try {
            $attributeModel = new Attribute();
            $attributeId = $attributeModel->create($data);
            
            // Handle values (supports both array and comma-separated string)
            $rawValues = $_POST['values'] ?? [];
            $colorCodes = $_POST['color_codes'] ?? [];
            
            if (is_string($rawValues)) {
                $values = array_filter(array_map('trim', explode(',', $rawValues)), fn($v) => $v !== '');
            } else {
                $values = (array)$rawValues;
            }

            if (!empty($values)) {
                $attributeValueModel = new AttributeValue();
                $idx = 0;
                foreach ($values as $valKey => $val) {
                    $val = trim((string)$val);
                    if ($val === '') continue;
                    
                    $colorCode = isset($colorCodes[$valKey]) ? trim($colorCodes[$valKey]) : (isset($colorCodes[$idx]) ? trim($colorCodes[$idx]) : null);
                    $attributeValueModel->create([
                        'attribute_id' => $attributeId,
                        'value' => $val,
                        'color_code' => !empty($colorCode) ? $colorCode : null
                    ]);
                    $idx++;
                }
            }

            $successMsg = "Attribute '{$name}' created successfully!";
            if ($isJson) {
                echo json_encode([
                    'success' => true,
                    'message' => $successMsg,
                    'attribute_id' => $attributeId,
                    'name' => $name
                ]);
                exit;
            }

            Session::setFlash('success', $successMsg);
            $this->redirect('/admin/catalog/attributes');
        } catch (\Exception $e) {
            $errMsg = 'Error creating attribute: ' . $e->getMessage();
            if ($isJson) {
                echo json_encode(['success' => false, 'message' => $errMsg]);
                exit;
            }
            Session::setFlash('error', $errMsg);
            $this->redirect('/admin/catalog/attributes/create');
        }
    }

    public function update()
    {
        $this->requirePermission('attributes', 'edit');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/attributes');
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'sub_category_id' => !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null,
            'name' => trim($_POST['name'] ?? ''),
            'attribute_code' => trim($_POST['attribute_code'] ?? ''),
            'input_type' => trim($_POST['input_type'] ?? 'textbox'),
            'description' => trim($_POST['description'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'is_searchable' => isset($_POST['is_searchable']) ? 1 : 0,
            'is_filterable' => isset($_POST['is_filterable']) ? 1 : 0,
            'is_variant' => isset($_POST['is_variant']) ? 1 : 0,
            'show_on_product_form' => isset($_POST['show_on_product_form']) ? 1 : 0,
            'is_visible_on_website' => isset($_POST['is_visible_on_website']) ? 1 : 0
        ];

        if ($id <= 0 || empty($data['name']) || empty($data['attribute_code']) || empty($data['category_id'])) {
            Session::setFlash('error', 'Invalid input or missing required fields.');
            $this->redirect('/admin/catalog/attributes/edit?id=' . $id);
        }

        try {
            $attributeModel = new Attribute();
            $attributeModel->update($id, $data);

            // Handle values sync
            $rawValues = $_POST['values'] ?? [];
            $rawColorCodes = $_POST['color_codes'] ?? [];
            $submittedItems = [];
            foreach ($rawValues as $idx => $v) {
                $v = trim($v);
                $c = isset($rawColorCodes[$idx]) ? trim($rawColorCodes[$idx]) : null;
                if ($v !== '') {
                    $submittedItems[] = [
                        'value' => $v,
                        'color_code' => $c
                    ];
                }
            }

            $attributeValueModel = new AttributeValue();
            $existing = $attributeValueModel->getByAttributeId($id);

            // Delete values that were removed by the user
            $submittedValueNames = array_column($submittedItems, 'value');
            foreach ($existing as $exist) {
                if (!in_array($exist['value'], $submittedValueNames)) {
                    $attributeValueModel->delete((int)$exist['id']);
                }
            }

            // Add or update values
            $existingMap = [];
            foreach ($existing as $exist) {
                $existingMap[$exist['value']] = $exist;
            }

            foreach ($submittedItems as $item) {
                if (isset($existingMap[$item['value']])) {
                    $existId = (int)$existingMap[$item['value']]['id'];
                    $attributeValueModel->update($existId, [
                        'value' => $item['value'],
                        'color_code' => $item['color_code']
                    ]);
                } else {
                    $attributeValueModel->create([
                        'attribute_id' => $id,
                        'value' => $item['value'],
                        'color_code' => $item['color_code']
                    ]);
                }
            }

            Session::setFlash('success', 'Attribute updated successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error updating attribute: ' . $e->getMessage());
            $this->redirect('/admin/catalog/attributes/edit?id=' . $id);
        }

        $this->redirect('/admin/catalog/attributes');
    }

    public function delete()
    {
        $this->requirePermission('attributes', 'delete');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/attributes');
        }

        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            try {
                $db = \Core\Database::getInstance();
                $db->beginTransaction();

                // Delete associated attribute values first
                $stmtVal = $db->prepare("DELETE FROM attribute_values WHERE attribute_id = ?");
                $stmtVal->execute([$id]);

                // Delete attribute
                $attributeModel = new Attribute();
                $attributeModel->delete($id);

                $db->commit();
                Session::setFlash('success', 'Attribute deleted successfully.');
            } catch (\Exception $e) {
                if (isset($db) && $db->inTransaction()) {
                    $db->rollBack();
                }
                Session::setFlash('error', 'Cannot delete attribute because it is in use by existing products or variants.');
            }
        }

        $this->redirect('/admin/catalog/attributes');
    }

    public function storeValue()
    {
        $this->requirePermission('attributes', 'create');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/attributes');
        }

        $attributeId = (int)($_POST['attribute_id'] ?? 0);
        $value = trim($_POST['value'] ?? '');

        if ($attributeId <= 0 || empty($value)) {
            Session::setFlash('error', 'Attribute and Value are required.');
            $this->redirect('/admin/catalog/attributes');
        }

        try {
            $attributeValueModel = new AttributeValue();
            $attributeValueModel->create(['attribute_id' => $attributeId, 'value' => $value]);
            Session::setFlash('success', 'Attribute value added successfully.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Error adding value: ' . $e->getMessage());
        }

        $this->redirect('/admin/catalog/attributes');
    }

    public function deleteValue()
    {
        $this->requirePermission('attributes', 'delete');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/catalog/attributes');
        }

        $id = (int)($_POST['id'] ?? 0);
        
        if ($id > 0) {
            try {
                $attributeValueModel = new AttributeValue();
                $attributeValueModel->delete($id);
                Session::setFlash('success', 'Attribute value deleted successfully.');
            } catch (\Exception $e) {
                Session::setFlash('error', 'Cannot delete attribute value because it is in use.');
            }
        }

        $this->redirect('/admin/catalog/attributes');
    }

    public function ajaxGetForCategory()
    {
        header('Content-Type: application/json');
        
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $subCategoryId = isset($_GET['sub_category_id']) ? (int)$_GET['sub_category_id'] : 0;
        
        if ($categoryId <= 0) {
            echo json_encode([]);
            return;
        }

        $attributeModel = new Attribute();
        $db = \Core\Database::getInstance();
        
        // Fetch active attributes that belong to the category AND (sub_category is null OR matches)
        $sql = "SELECT * FROM attributes 
                WHERE status = 'active' 
                AND category_id = :category_id 
                AND (sub_category_id IS NULL OR sub_category_id = :sub_category_id)
                ORDER BY display_order ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'category_id' => $categoryId,
            'sub_category_id' => $subCategoryId > 0 ? $subCategoryId : null
        ]);
        $attributes = $stmt->fetchAll();

        $attributeValueModel = new AttributeValue();
        foreach ($attributes as &$attr) {
            $attr['values'] = $attributeValueModel->getByAttributeId($attr['id']);
        }

        echo json_encode($attributes);
    }
}
