<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Database;

class ProjectController extends Controller
{
    /**
     * Display public storefront /project showcase page
     */
    public function index()
    {
        $db = Database::getInstance();
        $stmt = $db->query("
            SELECT * FROM cms_projects 
            WHERE is_active = 1 
            ORDER BY sort_order ASC, id DESC
        ");
        $projects = $stmt->fetchAll();

        foreach ($projects as &$project) {
            $project['images'] = json_decode($project['images'] ?? '[]', true) ?: [];
            $project['videos'] = json_decode($project['videos'] ?? '[]', true) ?: [];
        }

        $this->render('storefront/projects/index', [
            'title'    => 'Our Projects - GRM B2B Showcase & Portfolio',
            'projects' => $projects
        ]);
    }
}
