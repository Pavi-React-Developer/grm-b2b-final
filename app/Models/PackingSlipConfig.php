<?php
namespace App\Models;

use Core\Database;

class PackingSlipConfig
{
    private static string $settingKey = 'packing_slip_config';

    public static function getDefaultConfig(): array
    {
        $db = Database::getInstance();
        
        // Try to pull default GST and Logo from CMS Navbar Component
        $defaultGstin = '33BFGPG7507C2ZW';
        $cmsLogo = '';
        try {
            $stmt = $db->query("SELECT content_data FROM cms_components WHERE section_type = 'navbar' AND is_active = 1 ORDER BY id DESC LIMIT 1");
            $comp = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($comp && !empty($comp['content_data'])) {
                $data = json_decode($comp['content_data'], true);
                $cmsLogo = $data['global_settings']['logo'] ?? $data['logo'] ?? '';
                if (!empty($data['global_settings']['gst_number'])) {
                    $defaultGstin = $data['global_settings']['gst_number'];
                } elseif (!empty($data['gst_number'])) {
                    $defaultGstin = $data['gst_number'];
                }
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return [
            'company_name'  => 'GRM Garments',
            'subtitle'      => 'FORWARDING WITH CARE',
            'logo_url'      => $cmsLogo,
            'address'       => "123 Textile Park, Industrial Estate\nTirupur, Tamil Nadu - 641602, India",
            'phone'         => '+91 98765 43210',
            'email'         => 'care@grmgarments.com',
            'gstin'         => $defaultGstin,
            'notes'         => 'Declaration: We declare that this packing slip shows the actual items described and that all particulars are true and correct.',
            'show_logo'     => 1,
            'show_gstin'    => 1,
            'show_notes'    => 1,
            'show_prices'   => 0,
            // Customizable order of sections
            'section_order' => [
                'header',     // CMS Logo + Company Name + GSTIN & Order Details
                'addresses',  // From: Sender / To: Customer Address
                'notes'       // Terms, declaration, barcode/signature note
            ],
            // Direct USB Thermal Printer Settings (TSC TE244)
            'tspl_printer'   => 'TSC TE244',
            'tspl_mode'      => 'bitmap',
            'tspl_density'   => 7,
            'tspl_threshold' => 165,
            'tspl_speed'     => 4,
            'tspl_invert'    => 0,
        ];
    }

    public static function get(): array
    {
        $settingModel = new Setting();
        $raw = $settingModel->get(self::$settingKey, null);
        $defaults = self::getDefaultConfig();

        if (empty($raw)) {
            return $defaults;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }

        // Merge with defaults in case new keys are introduced
        $config = array_merge($defaults, $decoded);

        // Ensure section_order has all valid sections (excluding totals and items)
        $validSections = ['header', 'meta', 'addresses', 'notes'];
        if (empty($config['section_order']) || !is_array($config['section_order'])) {
            $config['section_order'] = $validSections;
        } else {
            // Filter out 'totals', 'items' and unknown sections
            $config['section_order'] = array_values(array_filter($config['section_order'], fn($s) => $s !== 'totals' && $s !== 'items' && in_array($s, $validSections)));
            // Append any missing sections to avoid omitting essential data
            foreach ($validSections as $s) {
                if (!in_array($s, $config['section_order'])) {
                    $config['section_order'][] = $s;
                }
            }
        }

        return $config;
    }

    public static function save(array $data): bool
    {
        $current = self::get();

        $validSections = ['header', 'meta', 'addresses', 'notes'];
        $submittedOrder = $data['section_order'] ?? [];
        if (is_string($submittedOrder)) {
            $submittedOrder = json_decode($submittedOrder, true) ?: [];
        }
        $filteredOrder = array_values(array_filter(array_intersect($submittedOrder, $validSections), fn($s) => $s !== 'totals' && $s !== 'items'));
        foreach ($validSections as $s) {
            if (!in_array($s, $filteredOrder)) {
                $filteredOrder[] = $s;
            }
        }

        $updated = [
            'company_name'  => trim($data['company_name'] ?? $current['company_name']),
            'subtitle'      => trim($data['subtitle'] ?? ($current['subtitle'] ?? 'FORWARDING WITH CARE')),
            'logo_url'      => trim($data['logo_url'] ?? $current['logo_url']),
            'address'       => trim($data['address'] ?? $current['address']),
            'phone'         => trim($data['phone'] ?? $current['phone']),
            'email'         => trim($data['email'] ?? $current['email']),
            'gstin'         => trim($data['gstin'] ?? $current['gstin']),
            'notes'         => trim($data['notes'] ?? $current['notes']),
            'show_logo'     => isset($data['show_logo']) ? (int)$data['show_logo'] : 1,
            'show_gstin'    => isset($data['show_gstin']) ? (int)$data['show_gstin'] : 1,
            'show_notes'     => isset($data['show_notes']) ? (int)$data['show_notes'] : 1,
            'show_prices'    => isset($data['show_prices']) ? (int)$data['show_prices'] : 0,
            'section_order'  => $filteredOrder,
            'tspl_printer'   => trim($data['tspl_printer'] ?? ($current['tspl_printer'] ?? 'TSC TE244')),
            'tspl_mode'      => in_array($data['tspl_mode'] ?? '', ['bitmap', 'vector']) ? $data['tspl_mode'] : ($current['tspl_mode'] ?? 'bitmap'),
            'tspl_density'   => isset($data['tspl_density']) ? (int)$data['tspl_density'] : (int)($current['tspl_density'] ?? 7),
            'tspl_threshold' => isset($data['tspl_threshold']) ? (int)$data['tspl_threshold'] : (int)($current['tspl_threshold'] ?? 165),
            'tspl_speed'     => isset($data['tspl_speed']) ? (int)$data['tspl_speed'] : (int)($current['tspl_speed'] ?? 4),
            'tspl_invert'    => isset($data['tspl_invert']) ? (int)$data['tspl_invert'] : (int)($current['tspl_invert'] ?? 0),
        ];

        $settingModel = new Setting();
        $settingModel->set(self::$settingKey, json_encode($updated));
        return true;
    }
}
