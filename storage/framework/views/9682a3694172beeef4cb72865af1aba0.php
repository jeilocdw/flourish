<?php $__env->startSection('title', 'Flourish Supermarket'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white h-screen fixed left-0 top-0 z-50 overflow-y-auto">
        <div class="p-4 border-b border-gray-700">
            <h1 class="text-xl font-bold">Flourish</h1>
            <p class="text-xs text-gray-400">Supermarket POS</p>
        </div>
        
        <nav class="mt-4 pb-20">
            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('dashboard') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            
            <a href="<?php echo e(route('pos.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('pos.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                POS Terminal
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase">Inventory</div>
            
            <a href="<?php echo e(route('products.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('products.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>
            
            <a href="<?php echo e(route('inventory.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('inventory.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Inventory
            </a>
            
            <a href="<?php echo e(route('categories.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('categories.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Categories
            </a>
            
            <a href="<?php echo e(route('brands.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('brands.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                Brands
            </a>
            
            <a href="<?php echo e(route('units.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('units.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                Units
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase">Sales</div>
            
            <a href="<?php echo e(route('sales.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('sales.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                Sales
            </a>
            
            <a href="<?php echo e(route('customers.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('customers.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Customers
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase">Purchases</div>
            
            <a href="<?php echo e(route('purchases.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('purchases.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Purchases
            </a>
            
            <a href="<?php echo e(route('suppliers.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('suppliers.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Suppliers
            </a>
            
            <a href="<?php echo e(route('expenses.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('expenses.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Expenses
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase">Reports</div>
            
            <a href="<?php echo e(route('reports.sales')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('reports.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Reports
            </a>
            
            <?php if(auth()->user()->role === 'admin'): ?>
            <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase">Admin</div>
            
            <a href="<?php echo e(route('users.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('users.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users
            </a>
            
            <a href="<?php echo e(route('stores.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('stores.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Stores
            </a>
            <?php endif; ?>
            
            <a href="<?php echo e(route('settings.index')); ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('settings.*') ? 'bg-gray-800 border-l-4 border-primary' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-semibold text-gray-800"><?php echo $__env->yieldContent('header', 'Dashboard'); ?></h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600"><?php echo e(auth()->user()->name); ?></span>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-gray-600 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('main'); ?>
        </main>
    </div>
</div>
<?php echo $__env->yieldPushContent('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/layouts/app.blade.php ENDPATH**/ ?>