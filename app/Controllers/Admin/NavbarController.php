<?php
namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\CmsComponent;
use Core\Session;

class NavbarController extends Controller
{
    private CmsComponent $cmsModel;

    public function __construct()
    {
        parent::__construct();
        if (\Core\Session::get('user_role') !== 'super_admin') {
            $this->redirect('/admin/dashboard');
        }
        $this->cmsModel = new CmsComponent();
    }

    public function index()
    {
        $db = \Core\Database::getInstance();
        $stmt = $db->query("SELECT * FROM cms_components WHERE section_type = 'navbar'");
        $navbars = $stmt->fetchAll();
        
        foreach ($navbars as &$navbar) {
            $navbar['content'] = json_decode($navbar['content_data'], true) ?: [];
        }

        $this->render('admin/cms/navbars/index', [
            'title' => 'Navbars',
            'navbars' => $navbars
        ], 'admin');
    }

    public function create()
    {
        $db = \Core\Database::getInstance();
        $categories = $db->query("SELECT id, name, slug FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();

        $this->render('admin/cms/navbars/create', [
            'title' => 'New Navbar Configuration',
            'categories' => $categories
        ], 'admin');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/cms/navbars');
        }

        $title = $_POST['title'] ?? 'Navbar Configuration';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Structure based on UI needs
        $contentDataArray = [
            'title' => $title,
            'global_settings' => [
                'logo' => $_POST['logo'] ?? '',
                'favicon' => $_POST['favicon'] ?? '',
                'logo_cta_url' => $_POST['logo_cta_url'] ?? '/',
                'logo_position' => $_POST['logo_position'] ?? 'Left',
                'bg_color' => [
                    'type' => $_POST['bg_color_type'] ?? 'solid',
                    'value' => $_POST['bg_color_value'] ?? '#ffffff'
                ],
                'text_color' => $_POST['text_color'] ?? '#8c6a4f',
                'show_system_icons' => isset($_POST['show_system_icons']) ? 1 : 0,
                'container_height' => (int)($_POST['container_height'] ?? 72),
                'font_size' => (int)($_POST['font_size'] ?? 14),
                'icon_size' => (int)($_POST['icon_size'] ?? 20),
            ],
            'menu_items' => []
        ];

        // Process menu items array
        if (isset($_POST['menu_item_title']) && is_array($_POST['menu_item_title'])) {
            foreach ($_POST['menu_item_title'] as $index => $itemTitle) {
                $contentDataArray['menu_items'][] = [
                    'title' => $itemTitle,
                    'cta_url' => $_POST['menu_item_cta_url'][$index] ?? '',
                    'position' => $_POST['menu_item_position'][$index] ?? 'Left',
                    'auto_fill_catalog' => $_POST['menu_item_auto_fill'][$index] ?? [],
                    'text_color' => $_POST['menu_item_text_color'][$index] ?? '',
                    'bg_color' => $_POST['menu_item_bg_color'][$index] ?? '',
                    'dropdown_bg_color' => $_POST['menu_item_dropdown_bg_color'][$index] ?? '#ffffff',
                    'dropdown_text_color' => $_POST['menu_item_dropdown_text_color'][$index] ?? '#111827',
                    'font_size' => $_POST['menu_item_font_size'][$index] ?? '',
                    'icon_size' => $_POST['menu_item_icon_size'][$index] ?? '',
                    'is_dropdown' => isset($_POST['menu_item_is_dropdown'][$index]) ? 1 : 0,
                    'active' => isset($_POST['menu_item_active'][$index]) ? 1 : 0,
                    // sub_items structure if we add custom ones later
                    'sub_items' => []
                ];
            }
        }

        $contentData = json_encode($contentDataArray);
        $id = 'navbar_' . uniqid();

        $this->cmsModel->create([
            'id' => $id,
            'section_type' => 'navbar',
            'content_data' => $contentData,
            'is_active' => $is_active
        ]);

        \Core\Cache::delete('cms_layout_home');
        \Core\Cache::delete('layout_main_components');
        \Core\Cache::delete('layout_admin_navbar');
        Session::setFlash('success', 'Navbar created successfully.');
        $this->redirect('/admin/cms/navbars');
    }

    public function edit()
    {
        $id = $_GET['id'] ?? '';
        $navbar = $this->cmsModel->getById($id);
        
        if (!$navbar || $navbar['section_type'] !== 'navbar') {
            Session::setFlash('error', 'Navbar not found.');
            $this->redirect('/admin/cms/navbars');
            return;
        }

        $navbar['content'] = json_decode($navbar['content_data'], true) ?: [];

        $db = \Core\Database::getInstance();
        $categories = $db->query("SELECT id, name, slug FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();

        $this->render('admin/cms/navbars/edit', [
            'title' => 'Edit Navbar Configuration',
            'navbar' => $navbar,
            'categories' => $categories
        ], 'admin');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/cms/navbars');
        }

        $id = $_POST['id'] ?? '';
        $navbar = $this->cmsModel->getById($id);

        if (!$navbar || $navbar['section_type'] !== 'navbar') {
            Session::setFlash('error', 'Navbar not found.');
            $this->redirect('/admin/cms/navbars');
            return;
        }

        $title = $_POST['title'] ?? 'Navbar Configuration';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $contentDataArray = [
            'title' => $title,
            'global_settings' => [
                'logo' => $_POST['logo'] ?? '',
                'favicon' => $_POST['favicon'] ?? '',
                'logo_cta_url' => $_POST['logo_cta_url'] ?? '/',
                'logo_position' => $_POST['logo_position'] ?? 'Left',
                'bg_color' => [
                    'type' => $_POST['bg_color_type'] ?? 'solid',
                    'value' => $_POST['bg_color_value'] ?? '#ffffff'
                ],
                'text_color' => $_POST['text_color'] ?? '#8c6a4f',
                'show_system_icons' => isset($_POST['show_system_icons']) ? 1 : 0,
                'container_height' => (int)($_POST['container_height'] ?? 72),
                'font_size' => (int)($_POST['font_size'] ?? 14),
                'icon_size' => (int)($_POST['icon_size'] ?? 20),
            ],
            'menu_items' => []
        ];

        if (isset($_POST['menu_item_title']) && is_array($_POST['menu_item_title'])) {
            foreach ($_POST['menu_item_title'] as $index => $itemTitle) {
                $contentDataArray['menu_items'][] = [
                    'title' => $itemTitle,
                    'cta_url' => $_POST['menu_item_cta_url'][$index] ?? '',
                    'position' => $_POST['menu_item_position'][$index] ?? 'Left',
                    'auto_fill_catalog' => $_POST['menu_item_auto_fill'][$index] ?? [],
                    'text_color' => $_POST['menu_item_text_color'][$index] ?? '',
                    'bg_color' => $_POST['menu_item_bg_color'][$index] ?? '',
                    'dropdown_bg_color' => $_POST['menu_item_dropdown_bg_color'][$index] ?? '#ffffff',
                    'dropdown_text_color' => $_POST['menu_item_dropdown_text_color'][$index] ?? '#111827',
                    'font_size' => $_POST['menu_item_font_size'][$index] ?? '',
                    'icon_size' => $_POST['menu_item_icon_size'][$index] ?? '',
                    'is_dropdown' => isset($_POST['menu_item_is_dropdown'][$index]) ? 1 : 0,
                    'active' => isset($_POST['menu_item_active'][$index]) ? 1 : 0,
                    'sub_items' => []
                ];
            }
        }

        $contentData = json_encode($contentDataArray);

        $this->cmsModel->update($id, [
            'content_data' => $contentData,
            'is_active' => $is_active
        ]);

        \Core\Cache::delete('cms_layout_home');
        \Core\Cache::delete('layout_main_components');
        \Core\Cache::delete('layout_admin_navbar');
        Session::setFlash('success', 'Navbar updated successfully.');
        $this->redirect('/admin/cms/navbars');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/cms/navbars');
        }

        $id = $_POST['id'] ?? '';
        $navbar = $this->cmsModel->getById($id);

        if (!$navbar || $navbar['section_type'] !== 'navbar') {
            Session::setFlash('error', 'Navbar not found.');
            $this->redirect('/admin/cms/navbars');
            return;
        }

        $this->cmsModel->delete($id);
        
        \Core\Cache::delete('cms_layout_home');
        \Core\Cache::delete('layout_main_components');
        \Core\Cache::delete('layout_admin_navbar');
        Session::setFlash('success', 'Navbar deleted successfully.');
        $this->redirect('/admin/cms/navbars');
    }
}
