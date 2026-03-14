<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CashierAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'cashier') {
            return $next($request);
        }

        // Allow logout
        if ($request->route()->getName() === 'logout') {
            return $next($request);
        }

        $allowedRoutes = [
            'pos.index',
            'pos.products',
            'pos.process-sale',
            'pos.receipt',
            'inventory.index',
            'inventory.lowStock',
            'inventory.expiring',
            'sales.index',
            'sales.show',
            'reports.sales',
            'dashboard',
        ];

        $allowedStoreRoutes = [
            'customers.index',
            'customers.create',
            'customers.store',
            'customers.update',
            'customers.edit',
        ];

        $currentRoute = $request->route()->getName();
        
        if (!in_array($currentRoute, $allowedRoutes) && !in_array($currentRoute, $allowedStoreRoutes)) {
            abort(403, 'Access denied. Cashiers can only access POS, Inventory, Sales, Customers, and Reports.');
        }

        // Prevent cashiers from deleting
        if ($request->isMethod('delete')) {
            abort(403, 'Cashiers cannot delete records.');
        }

        return $next($request);
    }
}
