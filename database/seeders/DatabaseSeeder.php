<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default store
        DB::table('stores')->insert([
            'name' => 'Main Store',
            'code' => 'MAIN',
            'address' => '123 Main Street',
            'phone' => '+1234567890',
            'email' => 'info@flourish.com',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'is_active' => true,
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create default units
        DB::table('units')->insert([
            ['name' => 'Piece', 'short_name' => 'pc', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kilogram', 'short_name' => 'kg', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gram', 'short_name' => 'g', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Liter', 'short_name' => 'L', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meter', 'short_name' => 'm', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Create default categories
        DB::table('categories')->insert([
            ['name' => 'Groceries', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beverages', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Snacks', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Household', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Personal Care', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Create default brands
        DB::table('brands')->insert([
            ['name' => 'Generic', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nestle', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coca-Cola', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unilever', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Procter & Gamble', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Create default settings
        DB::table('settings')->insert([
            ['key' => 'store_name', 'value' => 'Flourish Supermarket', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency', 'value' => 'USD', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency_symbol', 'value' => '$', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tax_rate', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Create admin user
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'store_id' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
