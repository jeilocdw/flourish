# Flourish Supermarket POS

A comprehensive Laravel 12 Point of Sale (POS) application for supermarkets with features including product management, inventory tracking, sales, purchases, expenses, reports, and user management.

## Features

- **Authentication & Authorization** - Role-based access control (Admin, Manager, Cashier)
- **POS Terminal** - Quick product search, category filtering, cart management
- **Product Management** - CSV import/export, barcode support, stock tracking
- **Inventory Management** - Low stock alerts, expiring products tracking
- **Sales & Purchases** - Complete transaction management with receipt generation
- **Expenses Tracking** - Categorized expense management
- **Reports** - Sales reports with profit calculations
- **Multi-store Support** - Manage multiple store locations

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 5.7+ or MariaDB 10.3+

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url> flourish
cd flourish
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Configure Environment

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flourish_pos
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database (Optional)

```bash
php artisan db:seed
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## Default Login

After seeding, you can login with:

- **Email**: admin@example.com
- **Password**: admin123

## Deployment

### Shared Hosting (cPanel, etc.)

1. **Upload Files**: Upload all files to your public_html or appropriate directory
2. **Configure Database**: Create a MySQL database and import migrations
3. **Update .env**: Set your database credentials
4. **Storage Link**: Run `php artisan storage:link` (or create symbolic link manually)
5. **Permissions**: Ensure `storage/` and `bootstrap/cache/` are writable

### Vercel Deployment

1. **Install Vercel CLI** (optional):

```bash
npm i -g vercel
```

2. **Create vercel.json** in project root:

```json
{
    "builds": [
        {
            "src": "public/index.php",
            "use": "@vercel/php"
        }
    ],
    "routes": [
        {
            "src": "/(.*)",
            "dest": "/public/index.php"
        }
    ]
}
```

3. **Update index.php** (in public folder):

Add this at the top after `<?php`:

```php
// Vercel environment configuration
if (isset($_ENV['VERCEL'])) {
    $_SERVER['REQUEST_URI'] = str_replace('/api', '', $_SERVER['REQUEST_URI']);
    
    require __DIR__.'/../vendor/autoload.php';
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    $response->send();
    $kernel->terminate($request, $response);
    exit;
}
```

4. **Deploy**:

```bash
vercel
```

Or connect your GitHub repository to Vercel for automatic deployments.

### Render Deployment

1. **Create render.yaml** in project root:

```yaml
services:
  - type: web
    name: flourish-pos
    env: php
    buildCommand: composer install --no-dev --optimize-autoloader
    startCommand: php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false

databases:
  - name: flourish-db
    plan: free
    mysqlVersion: "8.0"
```

2. **Deploy**:
   - Go to [Render Dashboard](https://dashboard.render.com)
   - Click "New" → "Blueprint"
   - Connect your GitHub repository
   - Select render.yaml and deploy

For detailed guide, see [RENDER.md](RENDER.md)

### Laravel Forge / Vapor

For managed Laravel hosting, use Laravel Forge or Vapor for easy deployment.

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| APP_NAME | Application name | Flourish Supermarket |
| APP_ENV | Environment | local |
| APP_DEBUG | Debug mode | false |
| APP_URL | Application URL | http://localhost |
| DB_CONNECTION | Database type | mysql |
| DB_HOST | Database host | 127.0.0.1 |
| DB_PORT | Database port | 3306 |
| DB_DATABASE | Database name | flourish_pos |
| DB_USERNAME | Database username | root |
| DB_PASSWORD | Database password | - |

## User Roles

| Role | Permissions |
|------|-------------|
| Admin | Full access to all features |
| Manager | Manage products, sales, purchases, expenses, reports |
| Cashier | POS, Sales, Customers, Inventory (view only) |

## Currency Settings

Configure your preferred currency in Settings > Business Settings. Available currencies include:
- USD ($)
- EUR (€)
- GBP (£)
- NGN (₦)
- KES (KSh)
- And many more...

## CSV Import/Export

### Products Import

1. Go to Products page
2. Click "Import"
3. Upload CSV file with columns: name, sku, barcode, category_id, brand_id, unit_id, cost_price, sell_price, tax_rate, alert_quantity

### Products Export

1. Go to Products page
2. Click "Export"
3. Download CSV file

## API Endpoints

The application uses the following main routes:

- `/pos` - POS Terminal
- `/pos/products` - Get products (JSON)
- `/pos/process-sale` - Process sale
- `/pos/receipt/{sale}` - Generate receipt
- `/products` - Product management
- `/sales` - Sales management
- `/purchases` - Purchase management
- `/expenses` - Expense management
- `/customers` - Customer management
- `/suppliers` - Supplier management
- `/inventory` - Inventory management
- `/reports/sales` - Sales reports
- `/settings` - Application settings

## License

MIT License
