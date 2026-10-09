<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use App\Models\SizeChart;
use App\Models\Category;

class SizeChartController extends Controller
{
    private SizeChart $sizeChartModel;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->sizeChartModel = new SizeChart();
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $charts = $this->sizeChartModel->getAllActive();
        $categories = $this->categoryModel->getAll();

        // Process charts
        foreach ($charts as &$chart) {
            $chart['columns'] = json_decode($chart['columns_json'] ?? '[]', true) ?: [];
            $chart['rows'] = json_decode($chart['rows_json'] ?? '[]', true) ?: [];
        }
        unset($chart);

        $selectedId = (int)($_GET['id'] ?? 0);
        $selectedCat = (int)($_GET['category'] ?? 0);

        $this->render('storefront/size_chart', [
            'title' => 'Garment Size Guide & Measurements - GRM B2B',
            'charts' => $charts,
            'categories' => $categories,
            'selectedId' => $selectedId,
            'selectedCat' => $selectedCat
        ], 'main');
    }

    public function getChartJson(): void
    {
        header('Content-Type: application/json');
        
        $id = (int)($_GET['id'] ?? 0);
        $categoryId = (int)($_GET['category_id'] ?? 0);

        if ($id > 0) {
            $chart = $this->sizeChartModel->getById($id);
            if ($chart && $chart['is_active']) {
                $chart['columns'] = json_decode($chart['columns_json'] ?? '[]', true) ?: [];
                $chart['rows'] = json_decode($chart['rows_json'] ?? '[]', true) ?: [];
                echo json_encode(['success' => true, 'charts' => [$chart]]);
                exit;
            }
        }

        if ($categoryId > 0) {
            $charts = $this->sizeChartModel->getByCategoryId($categoryId);
            foreach ($charts as &$c) {
                $c['columns'] = json_decode($c['columns_json'] ?? '[]', true) ?: [];
                $c['rows'] = json_decode($c['rows_json'] ?? '[]', true) ?: [];
            }
            echo json_encode(['success' => true, 'charts' => $charts]);
            exit;
        }

        // Return all active
        $charts = $this->sizeChartModel->getAllActive();
        foreach ($charts as &$c) {
            $c['columns'] = json_decode($c['columns_json'] ?? '[]', true) ?: [];
            $c['rows'] = json_decode($c['rows_json'] ?? '[]', true) ?: [];
        }
        echo json_encode(['success' => true, 'charts' => $charts]);
        exit;
    }
}
