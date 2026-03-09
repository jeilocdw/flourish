<?php
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'check_requirements') {
    echo json_encode([
        'php' => (float)phpversion(),
        'pdo' => extension_loaded('pdo'),
        'mysql' => extension_loaded('pdo_mysql'),
        'json' => extension_loaded('json'),
        'curl' => extension_loaded('curl')
    ]);
    exit;
}

if ($action === 'check_installed') {
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile)) {
        echo json_encode(['installed' => false]);
        exit;
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES);
    $dbName = '';
    foreach ($lines as $line) {
        if (strpos($line, 'DB_DATABASE=') === 0) {
            $dbName = substr($line, 12);
            break;
        }
    }
    
    if (empty($dbName)) {
        echo json_encode(['installed' => false]);
        exit;
    }
    
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=$dbName", 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $result = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo json_encode(['installed' => $result > 0]);
    } catch (Exception $e) {
        echo json_encode(['installed' => false]);
    }
    exit;
}

if ($action === 'test_db' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        $pdo = new PDO("mysql:host={$input['host']};port={$input['port']}", $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$input['database']}`");
        echo json_encode(['success' => true, 'message' => 'Connection successful']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

if ($action === 'install' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        $pdo = new PDO("mysql:host={$input['host']};port={$input['port']}", $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$input['database']}`");
        $pdo->exec("USE `{$input['database']}`");
        
        $envContent = "APP_NAME=\"Flourish Supermarket\"\n";
        $envContent .= "APP_ENV=local\n";
        $envContent .= "APP_KEY=\n";
        $envContent .= "APP_DEBUG=true\n";
        $envContent .= "APP_URL=http://localhost\n\n";
        $envContent .= "DB_CONNECTION=mysql\n";
        $envContent .= "DB_HOST={$input['host']}\n";
        $envContent .= "DB_PORT={$input['port']}\n";
        $envContent .= "DB_DATABASE={$input['database']}\n";
        $envContent .= "DB_USERNAME={$input['username']}\n";
        $envContent .= "DB_PASSWORD={$input['password']}\n";
        
        file_put_contents(__DIR__ . '/../.env', $envContent);
        
        $migrations = [
            "CREATE TABLE IF NOT EXISTS stores (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, code VARCHAR(255) UNIQUE NOT NULL, address TEXT, phone VARCHAR(255), email VARCHAR(255), currency VARCHAR(10) DEFAULT 'USD', currency_symbol VARCHAR(5) DEFAULT '$', is_active BOOLEAN DEFAULT true, is_default BOOLEAN DEFAULT false, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS users (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role ENUM('admin','manager','cashier') DEFAULT 'cashier', store_id BIGINT UNSIGNED, is_active BOOLEAN DEFAULT true, remember_token VARCHAR(100), created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE SET NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS categories (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, image VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS brands (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, logo VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS units (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, short_name VARCHAR(10) NOT NULL, base_unit BIGINT UNSIGNED, operator VARCHAR(10), operator_value DECIMAL(10,2), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (base_unit) REFERENCES units(id) ON DELETE SET NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS products (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, sku VARCHAR(255) UNIQUE NOT NULL, barcode VARCHAR(255), category_id BIGINT UNSIGNED NOT NULL, brand_id BIGINT UNSIGNED, unit_id BIGINT UNSIGNED NOT NULL, cost_price DECIMAL(10,2) DEFAULT 0, sell_price DECIMAL(10,2) DEFAULT 0, tax_rate DECIMAL(5,2) DEFAULT 0, alert_quantity INT DEFAULT 10, expiry_date DATE, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE, FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL, FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS product_store (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, product_id BIGINT UNSIGNED NOT NULL, store_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 0, alert_quantity INT DEFAULT 10, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, UNIQUE KEY product_store (product_id, store_id), FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE, FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS customers (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255), phone VARCHAR(255), address TEXT, city VARCHAR(255), country VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS suppliers (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255), phone VARCHAR(255), address TEXT, city VARCHAR(255), country VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS sales (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, invoice VARCHAR(255) UNIQUE NOT NULL, store_id BIGINT UNSIGNED NOT NULL, customer_id BIGINT UNSIGNED, user_id BIGINT UNSIGNED NOT NULL, subtotal DECIMAL(10,2) DEFAULT 0, tax DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, paid DECIMAL(10,2) DEFAULT 0, change_amount DECIMAL(10,2) DEFAULT 0, payment_method ENUM('cash','card','mobile','bank_transfer') DEFAULT 'cash', status ENUM('completed','pending','cancelled') DEFAULT 'completed', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE, FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS sale_items (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, sale_id BIGINT UNSIGNED NOT NULL, product_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 1, unit_price DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE, FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS payments (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, sale_id BIGINT UNSIGNED NOT NULL, amount DECIMAL(10,2) DEFAULT 0, payment_method ENUM('cash','card','mobile','bank_transfer') DEFAULT 'cash', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS purchases (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, invoice VARCHAR(255) UNIQUE NOT NULL, supplier_id BIGINT UNSIGNED, store_id BIGINT UNSIGNED NOT NULL, user_id BIGINT UNSIGNED NOT NULL, total DECIMAL(10,2) DEFAULT 0, status ENUM('completed','pending','ordered') DEFAULT 'completed', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL, FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS purchase_items (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, purchase_id BIGINT UNSIGNED NOT NULL, product_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 1, unit_cost DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE, FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS expenses (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, store_id BIGINT UNSIGNED NOT NULL, category VARCHAR(255) NOT NULL, amount DECIMAL(10,2) DEFAULT 0, description TEXT, date DATE NOT NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE) ENGINE=InnoDB",
            "CREATE TABLE IF NOT EXISTS settings (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `key` VARCHAR(255) UNIQUE NOT NULL, value TEXT, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB"
        ];
        
        foreach ($migrations as $sql) {
            $pdo->exec($sql);
        }
        
        $pdo->exec("INSERT INTO stores (name, code, address, phone, email, currency, currency_symbol, is_active, is_default, created_at, updated_at) VALUES ('Main Store', 'MAIN', '123 Main Street', '+1234567890', 'info@flourish.com', 'USD', '$', 1, 1, NOW(), NOW())");
        $pdo->exec("INSERT INTO units (name, short_name, is_active, created_at, updated_at) VALUES ('Piece', 'pc', 1, NOW(), NOW()), ('Kilogram', 'kg', 1, NOW(), NOW()), ('Gram', 'g', 1, NOW(), NOW()), ('Liter', 'L', 1, NOW(), NOW()), ('Meter', 'm', 1, NOW(), NOW())");
        $pdo->exec("INSERT INTO categories (name, is_active, created_at, updated_at) VALUES ('Groceries', 1, NOW(), NOW()), ('Beverages', 1, NOW(), NOW()), ('Snacks', 1, NOW(), NOW()), ('Household', 1, NOW(), NOW()), ('Personal Care', 1, NOW(), NOW())");
        $pdo->exec("INSERT INTO brands (name, is_active, created_at, updated_at) VALUES ('Generic', 1, NOW(), NOW()), ('Nestle', 1, NOW(), NOW()), ('Coca-Cola', 1, NOW(), NOW()), ('Unilever', 1, NOW(), NOW()), ('Procter & Gamble', 1, NOW(), NOW())");
        
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $pdo->exec("INSERT INTO users (name, email, password, role, store_id, is_active, created_at, updated_at) VALUES ('Administrator', 'admin@example.com', '$password', 'admin', 1, 1, NOW(), NOW())");
        
        $pdo->exec("INSERT INTO settings (`key`, value, created_at, updated_at) VALUES ('store_name', 'Flourish Supermarket', NOW(), NOW()), ('currency', 'USD', NOW(), NOW()), ('currency_symbol', '$', NOW(), NOW()), ('tax_rate', '0', NOW(), NOW())");
        
        $key = 'base64:' . base64_encode(random_bytes(32));
        $envContent = file_get_contents(__DIR__ . '/../.env');
        $envContent = str_replace('APP_KEY=', 'APP_KEY=' . $key, $envContent);
        file_put_contents(__DIR__ . '/../.env', $envContent);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid action']);
