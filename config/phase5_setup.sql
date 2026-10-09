USE grm_b2b_db;

-- 1. Subcategories
CREATE TABLE IF NOT EXISTS sub_categories (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 2. Attributes Architecture
CREATE TABLE IF NOT EXISTS attributes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE -- e.g., 'Color', 'Wood Type'
);

CREATE TABLE IF NOT EXISTS attribute_values (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    attribute_id BIGINT NOT NULL,
    value VARCHAR(255) NOT NULL, -- e.g., 'Red', 'Teak'
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
);

-- 3. Updating Products Table
ALTER TABLE products ADD COLUMN sub_category_id BIGINT NULL AFTER category_id;
ALTER TABLE products ADD FOREIGN KEY (sub_category_id) REFERENCES sub_categories(id) ON DELETE SET NULL;

-- 4. Product Attributes Mapping
CREATE TABLE IF NOT EXISTS product_attribute_values (
    product_id BIGINT NOT NULL,
    attribute_value_id BIGINT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE,
    PRIMARY KEY (product_id, attribute_value_id)
);
