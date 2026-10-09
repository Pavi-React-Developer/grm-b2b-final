-- ============================================================
-- Marakathai B2B — Cancellation & Refund Module
-- Migration: refunds_setup.sql
-- Run this once against your MySQL (TiDB) database
-- ============================================================

-- 1. Extend orders.status to include refund lifecycle states
--    (TiDB / MySQL: ALTER COLUMN to update the status ENUM)
ALTER TABLE orders
    MODIFY COLUMN status ENUM(
        'pending',
        'placed',
        'packed',
        'shipped',
        'out_for_delivery',
        'delivered',
        'cancelled',
        'refund_pending',
        'refund_processing',
        'refund_completed',
        'refund_failed'
    ) NOT NULL DEFAULT 'pending';

-- 2. Add cancellation metadata columns to orders
ALTER TABLE orders
    ADD COLUMN IF NOT EXISTS cancellation_reason VARCHAR(500) NULL AFTER status,
    ADD COLUMN IF NOT EXISTS cancelled_at DATETIME NULL AFTER cancellation_reason,
    ADD COLUMN IF NOT EXISTS cashfree_order_id VARCHAR(100) NULL AFTER cancelled_at;

-- 3. Create refund_requests table
CREATE TABLE IF NOT EXISTS refund_requests (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id            INT UNSIGNED NOT NULL,
    user_id             INT UNSIGNED NOT NULL,
    amount              DECIMAL(10, 2) NOT NULL,
    reason              TEXT NOT NULL,
    cancellation_type   ENUM('full', 'partial') NOT NULL DEFAULT 'full',
    status              ENUM(
                            'pending_admin_approval',
                            'processing',
                            'completed',
                            'rejected'
                        ) NOT NULL DEFAULT 'pending_admin_approval',
    cashfree_refund_id  VARCHAR(100) NULL COMMENT 'Cashfree refund reference ID',
    cashfree_order_id   VARCHAR(100) NULL COMMENT 'Cashfree order ID linked to the payment',
    admin_notes         TEXT NULL COMMENT 'Admin reason for rejection or processing notes',
    processed_by        INT UNSIGNED NULL COMMENT 'Admin user ID who processed this',
    processed_at        DATETIME NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_order_id  (order_id),
    INDEX idx_user_id   (user_id),
    INDEX idx_status    (status),

    CONSTRAINT fk_rr_order  FOREIGN KEY (order_id) REFERENCES orders(id)  ON DELETE RESTRICT,
    CONSTRAINT fk_rr_user   FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
