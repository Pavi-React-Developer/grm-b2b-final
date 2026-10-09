<?php
namespace App\Models;

use Core\Model;

class VendorBankAccount extends Model
{
    /**
     * Get all bank accounts for a vendor
     */
    public function getByVendorId(int $vendorId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM vendor_bank_accounts 
            WHERE vendor_id = ? 
            ORDER BY is_primary DESC, created_at DESC
        ");
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Get the active / primary bank account for a vendor
     */
    public function getPrimaryAccount(int $vendorId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM vendor_bank_accounts 
            WHERE vendor_id = ? AND is_primary = 1 
            LIMIT 1
        ");
        $stmt->execute([$vendorId]);
        $account = $stmt->fetch();
        
        if (!$account) {
            // Fallback to latest account if none marked primary
            $stmt = $this->db->prepare("
                SELECT * FROM vendor_bank_accounts 
                WHERE vendor_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1
            ");
            $stmt->execute([$vendorId]);
            $account = $stmt->fetch();
        }

        return $account ?: null;
    }

    /**
     * Get single account by ID with vendor ownership check
     */
    public function getById(int $id, int $vendorId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM vendor_bank_accounts 
            WHERE id = ? AND vendor_id = ?
        ");
        $stmt->execute([$id, $vendorId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new bank account for vendor
     */
    public function create(int $vendorId, array $data): int
    {
        // Check existing count
        $existing = $this->getByVendorId($vendorId);
        $isPrimary = (!empty($data['is_primary']) || count($existing) === 0) ? 1 : 0;

        if ($isPrimary) {
            $this->unsetAllPrimary($vendorId);
        }

        $stmt = $this->db->prepare("
            INSERT INTO vendor_bank_accounts (
                vendor_id, bank_name, account_holder_name, account_number, 
                ifsc_code, branch_name, account_type, upi_id, is_primary, status
            ) VALUES (
                :vendor_id, :bank_name, :account_holder_name, :account_number, 
                :ifsc_code, :branch_name, :account_type, :upi_id, :is_primary, 'active'
            )
        ");

        $stmt->execute([
            'vendor_id'           => $vendorId,
            'bank_name'           => trim($data['bank_name'] ?? ''),
            'account_holder_name' => trim($data['account_holder_name'] ?? ''),
            'account_number'      => trim($data['account_number'] ?? ''),
            'ifsc_code'           => strtoupper(trim($data['ifsc_code'] ?? '')),
            'branch_name'         => trim($data['branch_name'] ?? '') ?: null,
            'account_type'        => in_array($data['account_type'] ?? '', ['current', 'savings']) ? $data['account_type'] : 'current',
            'upi_id'              => trim($data['upi_id'] ?? '') ?: null,
            'is_primary'          => $isPrimary
        ]);

        $newId = (int)$this->db->lastInsertId();

        if ($isPrimary) {
            $this->syncVendorProfile($vendorId, [
                'bank_name'           => trim($data['bank_name'] ?? ''),
                'account_number'      => trim($data['account_number'] ?? ''),
                'ifsc_code'           => strtoupper(trim($data['ifsc_code'] ?? '')),
                'account_holder_name' => trim($data['account_holder_name'] ?? '')
            ]);
        }

        return $newId;
    }

    /**
     * Update an existing bank account
     */
    public function update(int $id, int $vendorId, array $data): bool
    {
        $account = $this->getById($id, $vendorId);
        if (!$account) {
            return false;
        }

        $isPrimary = !empty($data['is_primary']) ? 1 : $account['is_primary'];

        if ($isPrimary && !$account['is_primary']) {
            $this->unsetAllPrimary($vendorId);
        }

        $stmt = $this->db->prepare("
            UPDATE vendor_bank_accounts SET
                bank_name = :bank_name,
                account_holder_name = :account_holder_name,
                account_number = :account_number,
                ifsc_code = :ifsc_code,
                branch_name = :branch_name,
                account_type = :account_type,
                upi_id = :upi_id,
                is_primary = :is_primary
            WHERE id = :id AND vendor_id = :vendor_id
        ");

        $success = $stmt->execute([
            'id'                  => $id,
            'vendor_id'           => $vendorId,
            'bank_name'           => trim($data['bank_name'] ?? $account['bank_name']),
            'account_holder_name' => trim($data['account_holder_name'] ?? $account['account_holder_name']),
            'account_number'      => trim($data['account_number'] ?? $account['account_number']),
            'ifsc_code'           => strtoupper(trim($data['ifsc_code'] ?? $account['ifsc_code'])),
            'branch_name'         => isset($data['branch_name']) ? (trim($data['branch_name']) ?: null) : $account['branch_name'],
            'account_type'        => in_array($data['account_type'] ?? '', ['current', 'savings']) ? $data['account_type'] : $account['account_type'],
            'upi_id'              => isset($data['upi_id']) ? (trim($data['upi_id']) ?: null) : $account['upi_id'],
            'is_primary'          => $isPrimary
        ]);

        if ($success && $isPrimary) {
            $this->syncVendorProfile($vendorId, [
                'bank_name'           => trim($data['bank_name'] ?? $account['bank_name']),
                'account_number'      => trim($data['account_number'] ?? $account['account_number']),
                'ifsc_code'           => strtoupper(trim($data['ifsc_code'] ?? $account['ifsc_code'])),
                'account_holder_name' => trim($data['account_holder_name'] ?? $account['account_holder_name'])
            ]);
        }

        return $success;
    }

    /**
     * Set a specific bank account as Primary / Active
     */
    public function setPrimary(int $id, int $vendorId): bool
    {
        $account = $this->getById($id, $vendorId);
        if (!$account) {
            return false;
        }

        $this->unsetAllPrimary($vendorId);

        $stmt = $this->db->prepare("
            UPDATE vendor_bank_accounts 
            SET is_primary = 1 
            WHERE id = ? AND vendor_id = ?
        ");
        $success = $stmt->execute([$id, $vendorId]);

        if ($success) {
            $this->syncVendorProfile($vendorId, [
                'bank_name'           => $account['bank_name'],
                'account_number'      => $account['account_number'],
                'ifsc_code'           => $account['ifsc_code'],
                'account_holder_name' => $account['account_holder_name']
            ]);
        }

        return $success;
    }

    /**
     * Delete a bank account
     */
    public function delete(int $id, int $vendorId): bool
    {
        $account = $this->getById($id, $vendorId);
        if (!$account) {
            return false;
        }

        $wasPrimary = (bool)$account['is_primary'];

        $stmt = $this->db->prepare("DELETE FROM vendor_bank_accounts WHERE id = ? AND vendor_id = ?");
        $success = $stmt->execute([$id, $vendorId]);

        // If the primary was deleted, promote the next available account
        if ($success && $wasPrimary) {
            $remaining = $this->getByVendorId($vendorId);
            if (!empty($remaining)) {
                $this->setPrimary((int)$remaining[0]['id'], $vendorId);
            } else {
                // Clear in vendor_profiles
                $this->syncVendorProfile($vendorId, [
                    'bank_name'           => null,
                    'account_number'      => null,
                    'ifsc_code'           => null,
                    'account_holder_name' => null
                ]);
            }
        }

        return $success;
    }

    /**
     * Unset primary status for all bank accounts of a vendor
     */
    private function unsetAllPrimary(int $vendorId): void
    {
        $stmt = $this->db->prepare("UPDATE vendor_bank_accounts SET is_primary = 0 WHERE vendor_id = ?");
        $stmt->execute([$vendorId]);
    }

    /**
     * Synchronize active bank details with vendor_profiles
     */
    private function syncVendorProfile(int $vendorId, array $bankDetails): void
    {
        $stmt = $this->db->prepare("
            UPDATE vendor_profiles SET
                bank_name = :bank_name,
                account_number = :account_number,
                ifsc_code = :ifsc_code,
                account_holder_name = :account_holder_name
            WHERE user_id = :vendor_id
        ");
        $stmt->execute([
            'vendor_id'           => $vendorId,
            'bank_name'           => $bankDetails['bank_name'] ?? null,
            'account_number'      => $bankDetails['account_number'] ?? null,
            'ifsc_code'           => $bankDetails['ifsc_code'] ?? null,
            'account_holder_name' => $bankDetails['account_holder_name'] ?? null
        ]);
    }
}
