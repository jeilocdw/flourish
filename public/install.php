<?php
// Install.php - Server-side handling (must be at top)

$action = $_GET['action'] ?? '';

// Handle API calls before any HTML output
if ($action === 'check_requirements') {
    header('Content-Type: application/json');
    echo json_encode([
        'php' => version_compare(PHP_VERSION, '8.2.0') >= 0,
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'pdo_pgsql' => extension_loaded('pdo_pgsql'),
        'json' => extension_loaded('json'),
        'curl' => extension_loaded('curl'),
    ]);
    exit;
}

if ($action === 'check_installed') {
    header('Content-Type: application/json');
    $envFile = __DIR__ . '/.env';
    if (!file_exists($envFile)) {
        echo json_encode(['installed' => false]);
        exit;
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES);
    $dbType = 'mysql';
    $dbName = '';
    $dbHost = '127.0.0.1';
    $dbPort = '3306';
    $dbUser = 'root';
    $dbPass = '';
    
    foreach ($lines as $line) {
        if (strpos($line, 'DB_CONNECTION=') === 0) {
            $dbType = substr($line, 15);
        } elseif (strpos($line, 'DB_DATABASE=') === 0) {
            $dbName = substr($line, 12);
        } elseif (strpos($line, 'DB_HOST=') === 0) {
            $dbHost = substr($line, 8);
        } elseif (strpos($line, 'DB_PORT=') === 0) {
            $dbPort = substr($line, 8);
        } elseif (strpos($line, 'DB_USERNAME=') === 0) {
            $dbUser = substr($line, 12);
        } elseif (strpos($line, 'DB_PASSWORD=') === 0) {
            $dbPass = substr($line, 11);
        }
    }
    
    if (empty($dbName)) {
        echo json_encode(['installed' => false]);
        exit;
    }
    
    try {
        if ($dbType === 'pgsql') {
            $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
        } else {
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName}";
        }
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $result = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo json_encode(['installed' => $result > 0]);
    } catch (Exception $e) {
        echo json_encode(['installed' => false]);
    }
    exit;
}

if ($action === 'test_db' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Determine database type from input
    $dbType = $input['type'] ?? 'pgsql';
    $isPostgres = ($dbType === 'pgsql');
    
    try {
        if ($isPostgres) {
            $dsn = "pgsql:host={$input['host']};port={$input['port']};dbname=postgres";
            $pdo = new PDO($dsn, $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            @$pdo->exec("CREATE DATABASE \"{$input['database']}\"");
        } else {
            $dsn = "mysql:host={$input['host']};port={$input['port']}";
            $pdo = new PDO($dsn, $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$input['database']}`");
        }
        echo json_encode(['success' => true, 'message' => 'Connection successful']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

if ($action === 'install' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['host']) || !isset($input['database'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Determine database type from input
    $dbType = $input['type'] ?? 'pgsql';
    $isPostgres = ($dbType === 'pgsql');
    
    try {
        if ($isPostgres) {
            $dsn = "pgsql:host={$input['host']};port={$input['port']};dbname=postgres";
            $pdo = new PDO($dsn, $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            @$pdo->exec("CREATE DATABASE \"{$input['database']}\"");
            
            // Reconnect to the new database
            $pdo = new PDO("pgsql:host={$input['host']};port={$input['port']};dbname={$input['database']}", $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            
            // PostgreSQL tables
            $pdo->exec("CREATE TABLE IF NOT EXISTS stores (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, code VARCHAR(255) UNIQUE NOT NULL, address TEXT, phone VARCHAR(255), email VARCHAR(255), currency VARCHAR(10) DEFAULT 'USD', currency_symbol VARCHAR(5) DEFAULT '$', is_active BOOLEAN DEFAULT true, is_default BOOLEAN DEFAULT false, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS users (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(20) DEFAULT 'cashier', store_id BIGINT, is_active BOOLEAN DEFAULT true, remember_token VARCHAR(100), created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS categories (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS brands (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS units (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, short_name VARCHAR(10) NOT NULL, base_unit BIGINT, operator VARCHAR(10), operator_value DECIMAL(10,2), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS products (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, sku VARCHAR(255) UNIQUE NOT NULL, barcode VARCHAR(255), category_id BIGINT, brand_id BIGINT, unit_id BIGINT, cost_price DECIMAL(10,2) DEFAULT 0, sell_price DECIMAL(10,2) DEFAULT 0, tax_rate DECIMAL(5,2) DEFAULT 0, alert_quantity INT DEFAULT 10, expiry_date DATE, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS product_store (id SERIAL PRIMARY KEY, product_id BIGINT NOT NULL, store_id BIGINT NOT NULL, quantity INT DEFAULT 0, alert_quantity INT DEFAULT 10, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, UNIQUE(product_id, store_id))");
            $pdo->exec("CREATE TABLE IF NOT EXISTS customers (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255), phone VARCHAR(255), address TEXT, city VARCHAR(255), country VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS suppliers (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255), phone VARCHAR(255), address TEXT, city VARCHAR(255), country VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS sales (id SERIAL PRIMARY KEY, invoice VARCHAR(255) UNIQUE NOT NULL, store_id BIGINT NOT NULL, customer_id BIGINT, user_id BIGINT NOT NULL, subtotal DECIMAL(10,2) DEFAULT 0, tax DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, paid DECIMAL(10,2) DEFAULT 0, change_amount DECIMAL(10,2) DEFAULT 0, payment_method VARCHAR(20) DEFAULT 'cash', status VARCHAR(20) DEFAULT 'completed', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS sale_items (id SERIAL PRIMARY KEY, sale_id BIGINT NOT NULL, product_id BIGINT NOT NULL, quantity INT DEFAULT 1, unit_price DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS payments (id SERIAL PRIMARY KEY, sale_id BIGINT NOT NULL, amount DECIMAL(10,2) DEFAULT 0, payment_method VARCHAR(20) DEFAULT 'cash', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS purchases (id SERIAL PRIMARY KEY, invoice VARCHAR(255) UNIQUE NOT NULL, supplier_id BIGINT, store_id BIGINT NOT NULL, user_id BIGINT NOT NULL, total DECIMAL(10,2) DEFAULT 0, status VARCHAR(20) DEFAULT 'completed', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS purchase_items (id SERIAL PRIMARY KEY, purchase_id BIGINT NOT NULL, product_id BIGINT NOT NULL, quantity INT DEFAULT 1, unit_cost DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS expenses (id SERIAL PRIMARY KEY, store_id BIGINT NOT NULL, category VARCHAR(255) NOT NULL, amount DECIMAL(10,2) DEFAULT 0, description TEXT, date DATE NOT NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS settings (id SERIAL PRIMARY KEY, key VARCHAR(255) UNIQUE NOT NULL, value TEXT, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
            
            // Insert default data
            $pdo->exec("INSERT INTO stores (name, code, address, phone, email, currency, currency_symbol, is_active, is_default, created_at, updated_at) VALUES ('Main Store', 'MAIN', '123 Main Street', '+1234567890', 'info@flourish.com', 'USD', '$', true, true, NOW(), NOW())");
            $pdo->exec("INSERT INTO units (name, short_name, is_active, created_at, updated_at) VALUES ('Piece', 'pc', true, NOW(), NOW()), ('Kilogram', 'kg', true, NOW(), NOW()), ('Gram', 'g', true, NOW(), NOW()), ('Liter', 'L', true, NOW(), NOW()), ('Meter', 'm', true, NOW(), NOW())");
            $pdo->exec("INSERT INTO categories (name, is_active, created_at, updated_at) VALUES ('Groceries', true, NOW(), NOW()), ('Beverages', true, NOW(), NOW()), ('Snacks', true, NOW(), NOW()), ('Household', true, NOW(), NOW()), ('Personal Care', true, NOW(), NOW())");
            $pdo->exec("INSERT INTO brands (name, is_active, created_at, updated_at) VALUES ('Generic', true, NOW(), NOW()), ('Nestle', true, NOW(), NOW()), ('Coca-Cola', true, NOW(), NOW()), ('Unilever', true, NOW(), NOW()), ('Procter & Gamble', true, NOW(), NOW())");
            
            $password = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (name, email, password, role, store_id, is_active, created_at, updated_at) VALUES ('Administrator', 'admin@example.com', '$password', 'admin', 1, true, NOW(), NOW())");
            
            $pdo->exec("INSERT INTO settings (key, value, created_at, updated_at) VALUES ('store_name', 'Flourish Supermarket', NOW(), NOW()), ('currency', 'USD', NOW(), NOW()), ('currency_symbol', '$', NOW(), NOW()), ('tax_rate', '0', NOW(), NOW())");
            
            $dbType = 'pgsql';
        } else {
            // MySQL
            $pdo = new PDO("mysql:host={$input['host']};port={$input['port']}", $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$input['database']}`");
            $pdo->exec("USE `{$input['database']}`");
            
            // MySQL tables (same as before)
            $pdo->exec("CREATE TABLE IF NOT EXISTS stores (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, code VARCHAR(255) UNIQUE NOT NULL, address TEXT, phone VARCHAR(255), email VARCHAR(255), currency VARCHAR(10) DEFAULT 'USD', currency_symbol VARCHAR(5) DEFAULT '$', is_active BOOLEAN DEFAULT true, is_default BOOLEAN DEFAULT false, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS users (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role ENUM('admin','manager','cashier') DEFAULT 'cashier', store_id BIGINT UNSIGNED, is_active BOOLEAN DEFAULT true, remember_token VARCHAR(100), created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS categories (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS brands (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS units (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, short_name VARCHAR(10) NOT NULL, base_unit BIGINT UNSIGNED, operator VARCHAR(10), operator_value DECIMAL(10,2), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS products (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, sku VARCHAR(255) UNIQUE NOT NULL, barcode VARCHAR(255), category_id BIGINT UNSIGNED, brand_id BIGINT UNSIGNED, unit_id BIGINT UNSIGNED, cost_price DECIMAL(10,2) DEFAULT 0, sell_price DECIMAL(10,2) DEFAULT 0, tax_rate DECIMAL(5,2) DEFAULT 0, alert_quantity INT DEFAULT 10, expiry_date DATE, is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS product_store (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, product_id BIGINT UNSIGNED NOT NULL, store_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 0, alert_quantity INT DEFAULT 10, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, UNIQUE KEY product_store (product_id, store_id)) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS customers (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255), phone VARCHAR(255), address TEXT, city VARCHAR(255), country VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS suppliers (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255), phone VARCHAR(255), address TEXT, city VARCHAR(255), country VARCHAR(255), is_active BOOLEAN DEFAULT true, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS sales (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, invoice VARCHAR(255) UNIQUE NOT NULL, store_id BIGINT UNSIGNED NOT NULL, customer_id BIGINT UNSIGNED, user_id BIGINT UNSIGNED NOT NULL, subtotal DECIMAL(10,2) DEFAULT 0, tax DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, paid DECIMAL(10,2) DEFAULT 0, change_amount DECIMAL(10,2) DEFAULT 0, payment_method ENUM('cash','card','mobile','bank_transfer') DEFAULT 'cash', status ENUM('completed','pending','cancelled') DEFAULT 'completed', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS sale_items (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, sale_id BIGINT UNSIGNED NOT NULL, product_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 1, unit_price DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS payments (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, sale_id BIGINT UNSIGNED NOT NULL, amount DECIMAL(10,2) DEFAULT 0, payment_method ENUM('cash','card','mobile','bank_transfer') DEFAULT 'cash', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS purchases (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, invoice VARCHAR(255) UNIQUE NOT NULL, supplier_id BIGINT UNSIGNED, store_id BIGINT UNSIGNED NOT NULL, user_id BIGINT UNSIGNED NOT NULL, total DECIMAL(10,2) DEFAULT 0, status ENUM('completed','pending','ordered') DEFAULT 'completed', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS purchase_items (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, purchase_id BIGINT UNSIGNED NOT NULL, product_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 1, unit_cost DECIMAL(10,2) DEFAULT 0, total DECIMAL(10,2) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS expenses (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, store_id BIGINT UNSIGNED NOT NULL, category VARCHAR(255) NOT NULL, amount DECIMAL(10,2) DEFAULT 0, description TEXT, date DATE NOT NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            $pdo->exec("CREATE TABLE IF NOT EXISTS settings (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, key VARCHAR(255) UNIQUE NOT NULL, value TEXT, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL) ENGINE=InnoDB");
            
            $pdo->exec("INSERT INTO stores (name, code, address, phone, email, currency, currency_symbol, is_active, is_default, created_at, updated_at) VALUES ('Main Store', 'MAIN', '123 Main Street', '+1234567890', 'info@flourish.com', 'USD', '$', 1, 1, NOW(), NOW())");
            $pdo->exec("INSERT INTO units (name, short_name, is_active, created_at, updated_at) VALUES ('Piece', 'pc', 1, NOW(), NOW()), ('Kilogram', 'kg', 1, NOW(), NOW()), ('Gram', 'g', 1, NOW(), NOW()), ('Liter', 'L', 1, NOW(), NOW()), ('Meter', 'm', 1, NOW(), NOW())");
            $pdo->exec("INSERT INTO categories (name, is_active, created_at, updated_at) VALUES ('Groceries', 1, NOW(), NOW()), ('Beverages', 1, NOW(), NOW()), ('Snacks', 1, NOW(), NOW()), ('Household', 1, NOW(), NOW()), ('Personal Care', 1, NOW(), NOW())");
            $pdo->exec("INSERT INTO brands (name, is_active, created_at, updated_at) VALUES ('Generic', 1, NOW(), NOW()), ('Nestle', 1, NOW(), NOW()), ('Coca-Cola', 1, NOW(), NOW()), ('Unilever', 1, NOW(), NOW()), ('Procter & Gamble', 1, NOW(), NOW())");
            
            $password = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (name, email, password, role, store_id, is_active, created_at, updated_at) VALUES ('Administrator', 'admin@example.com', '$password', 'admin', 1, 1, NOW(), NOW())");
            
            $pdo->exec("INSERT INTO settings (key, value, created_at, updated_at) VALUES ('store_name', 'Flourish Supermarket', NOW(), NOW()), ('currency', 'USD', NOW(), NOW()), ('currency_symbol', '$', NOW(), NOW()), ('tax_rate', '0', NOW(), NOW())");
            
            $dbType = 'mysql';
        }
        
        // Determine base URL from current request
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host;
        
        // Create .env file
        $envContent = "APP_NAME=\"Flourish Supermarket\"\n";
        $envContent .= "APP_ENV=production\n";
        $envContent .= "APP_KEY=\n";
        $envContent .= "APP_DEBUG=false\n";
        $envContent .= "APP_URL={$baseUrl}\n\n";
        $envContent .= "DB_CONNECTION={$dbType}\n";
        $envContent .= "DB_HOST={$input['host']}\n";
        $envContent .= "DB_PORT={$input['port']}\n";
        $envContent .= "DB_DATABASE={$input['database']}\n";
        $envContent .= "DB_USERNAME={$input['username']}\n";
        $envContent .= "DB_PASSWORD={$input['password']}\n";
        
        file_put_contents(__DIR__ . '/.env', $envContent);
        
        // Generate app key
        $key = 'base64:' . base64_encode(random_bytes(32));
        $envContent = file_get_contents(__DIR__ . '/.env');
        $envContent = str_replace('APP_KEY=', 'APP_KEY=' . $key, $envContent);
        file_put_contents(__DIR__ . '/.env', $envContent);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flourish Supermarket - Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-emerald-600 to-teal-800 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Flourish</h1>
                <p class="text-emerald-100 text-lg">Supermarket POS Installation</p>
            </div>

            <!-- Progress Steps -->
            <div class="bg-white rounded-lg shadow-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div id="step1-dot" class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">1</div>
                        <span class="ml-2 font-medium">Requirements</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-4"><div id="progress-1" class="h-full bg-primary transition-all" style="width: 100%"></div></div>
                    <div class="flex items-center">
                        <div id="step2-dot" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">2</div>
                        <span class="ml-2 font-medium">Database</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-4"><div id="progress-2" class="h-full bg-primary transition-all" style="width: 0%"></div></div>
                    <div class="flex items-center">
                        <div id="step3-dot" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">3</div>
                        <span class="ml-2 font-medium">Install</span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div id="content-area" class="bg-white rounded-lg shadow-xl p-8">
                <!-- Requirements Check -->
                <div id="requirements-step">
                    <h2 class="text-2xl font-bold mb-6">Server Requirements</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="font-medium">PHP Version >= 8.2</span>
                            <span id="php-status" class="text-gray-400">Checking...</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="font-medium">PDO Extension</span>
                            <span id="pdo-status" class="text-gray-400">Checking...</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="font-medium">MySQL Extension</span>
                            <span id="mysql-status" class="text-gray-400">Checking...</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="font-medium">PostgreSQL Extension</span>
                            <span id="pgsql-status" class="text-gray-400">Checking...</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="font-medium">JSON Extension</span>
                            <span id="json-status" class="text-gray-400">Checking...</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="font-medium">CURL Extension</span>
                            <span id="curl-status" class="text-gray-400">Checking...</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <button onclick="checkRequirements()" class="px-6 py-3 bg-primary hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                            Check Requirements
                        </button>
                    </div>
                </div>

                <!-- Database Setup -->
                <div id="database-step" class="hidden">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <p class="text-sm text-yellow-700">
                            <strong>Important:</strong> Use the <strong>Internal Connection String</strong> from your Render database dashboard (not the external hostname).<br>
                            Find it at: Dashboard → Database → "Internal Connection String"
                        </p>
                    </div>
                    
                    <form id="database-form" onsubmit="setupDatabase(event)">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Type</label>
                                <select name="db_type" id="db_type" onchange="updatePort()" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                                    <option value="pgsql">PostgreSQL (Recommended for Render)</option>
                                    <option value="mysql">MySQL</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                                <input type="text" name="db_host" placeholder="e.g., dpg-xxx.internal" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Port</label>
                                <input type="text" name="db_port" id="db_port" value="5432" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                                <input type="text" name="db_name" value="flourish" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <input type="text" name="db_user" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="db_pass" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <button type="submit" class="px-6 py-3 bg-primary hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                                Test & Save Connection
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Installation -->
                <div id="install-step" class="hidden">
                    <h2 class="text-2xl font-bold mb-6">Installing Application</h2>
                    
                    <div id="install-progress" class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span>Creating database tables...</span>
                            <span id="step-migration" class="text-gray-400">Pending</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span>Seeding initial data...</span>
                            <span id="step-seeder" class="text-gray-400">Pending</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span>Creating admin user...</span>
                            <span id="step-admin" class="text-gray-400">Pending</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span>Generating application key...</span>
                            <span id="step-key" class="text-gray-400">Pending</span>
                        </div>
                    </div>

                    <div id="install-result" class="hidden mt-6 p-4 rounded-lg"></div>

                    <div class="mt-6 text-center">
                        <button id="start-install" onclick="startInstallation()" class="px-6 py-3 bg-primary hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                            Start Installation
                        </button>
                        <a id="go-to-app" href="/" class="hidden px-6 py-3 bg-primary hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                            Go to Application
                        </a>
                    </div>
                </div>
            </div>

            <!-- Error Display -->
            <div id="error-area" class="hidden mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>
        </div>
    </div>

    <script>
        let dbConfig = {};

        function updatePort() {
            const dbType = document.getElementById('db_type').value;
            document.getElementById('db_port').value = dbType === 'pgsql' ? '5432' : '3306';
        }

        function updateStep(step) {
            document.getElementById('step1-dot').className = step >= 1 ? 'w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold' : 'w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold';
            document.getElementById('step2-dot').className = step >= 2 ? 'w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold' : 'w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold';
            document.getElementById('step3-dot').className = step >= 3 ? 'w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold' : 'w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold';
            document.getElementById('progress-1').style.width = step >= 2 ? '100%' : '0%';
            document.getElementById('progress-2').style.width = step >= 3 ? '100%' : '0%';
        }

        function showStep(stepName) {
            document.getElementById('requirements-step').classList.add('hidden');
            document.getElementById('database-step').classList.add('hidden');
            document.getElementById('install-step').classList.add('hidden');
            document.getElementById(stepName).classList.remove('hidden');
        }

        function setStatus(id, passed, message) {
            const el = document.getElementById(id);
            if (passed) {
                el.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            } else {
                el.innerHTML = '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg> ' + message;
            }
        }

        function checkRequirements() {
            fetch('install.php?action=check_requirements')
            .then(res => res.json())
            .then(data => {
                setStatus('php-status', data.php, data.php ? 'OK' : 'Need PHP 8.2+');
                setStatus('pdo-status', data.pdo, data.pdo ? 'OK' : 'Required');
                setStatus('mysql-status', data.pdo_mysql, data.pdo_mysql ? 'OK' : 'Required');
                setStatus('pgsql-status', data.pdo_pgsql, data.pdo_pgsql ? 'OK' : 'Required');
                setStatus('json-status', data.json, data.json ? 'OK' : 'Required');
                setStatus('curl-status', data.curl, data.curl ? 'OK' : 'Required');
                
                if (data.php && data.pdo && (data.pdo_mysql || data.pdo_pgsql) && data.json) {
                    setTimeout(() => {
                        updateStep(2);
                        showStep('database-step');
                    }, 500);
                }
            })
            .catch(err => {
                showError('Failed to check requirements: ' + err.message);
            });
        }

        function setupDatabase(e) {
            e.preventDefault();
            const form = document.getElementById('database-form');
            const formData = new FormData(form);
            
            dbConfig = {
                type: formData.get('db_type'),
                host: formData.get('db_host'),
                port: formData.get('db_port'),
                database: formData.get('db_name'),
                username: formData.get('db_user'),
                password: formData.get('db_pass')
            };

            fetch('install.php?action=test_db', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(dbConfig)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateStep(3);
                    showStep('install-step');
                } else {
                    showError(data.message);
                }
            })
            .catch(err => showError('Connection failed: ' + err.message));
        }

        function showError(msg) {
            const el = document.getElementById('error-area');
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function startInstallation() {
            document.getElementById('start-install').disabled = true;
            document.getElementById('start-install').innerHTML = 'Installing...';
            
            console.log('Starting installation with config:', dbConfig);
            
            fetch('install.php?action=install', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(dbConfig)
            })
            .then(res => {
                console.log('Response status:', res.status);
                return res.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    document.getElementById('step-migration').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('step-seeder').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('step-admin').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('step-key').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    
                    const result = document.getElementById('install-result');
                    result.classList.remove('hidden');
                    result.className = 'mt-6 p-4 rounded-lg bg-green-100 text-green-700';
                    result.innerHTML = '<strong>Installation Complete!</strong><br>Default login: admin@example.com / admin123';
                    
                    document.getElementById('start-install').classList.add('hidden');
                    document.getElementById('go-to-app').classList.remove('hidden');
                } else {
                    showError(data.message);
                    document.getElementById('start-install').disabled = false;
                }
            })
            .catch(err => {
                showError('Installation failed: ' + err.message);
                document.getElementById('start-install').disabled = false;
            });
        }

        // Check if already installed
        fetch('install.php?action=check_installed')
            .then(res => res.json())
            .then(data => {
                if (data.installed) {
                    updateStep(3);
                    showStep('install-step');
                    document.getElementById('step-migration').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('step-seeder').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('step-admin').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('step-key').innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    document.getElementById('start-install').classList.add('hidden');
                    document.getElementById('go-to-app').classList.remove('hidden');
                }
            });
    </script>
</body>
</html>
