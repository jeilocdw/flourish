<?php $__env->startSection('header', 'Sales Report'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from', now()->startOfMonth()->format('Y-m-d'))); ?>" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="<?php echo e(request('date_to', now()->endOfMonth()->format('Y-m-d'))); ?>" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Store</label>
                <select name="store_id" class="px-4 py-2 border rounded-lg">
                    <option value="">All Stores</option>
                    <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($store->id); ?>" <?php echo e(request('store_id') == $store->id ? 'selected' : ''); ?>><?php echo e($store->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Generate Report</button>
            <a href="<?php echo e(route('reports.sales')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Sales</p>
        <p class="text-2xl font-bold"><?php echo e(format_currency($summary['total_sales'] ?? 0)); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Orders</p>
        <p class="text-2xl font-bold"><?php echo e($summary['total_orders'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Average Order</p>
        <p class="text-2xl font-bold"><?php echo e(format_currency($summary['average_order'] ?? 0)); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Items Sold</p>
        <p class="text-2xl font-bold"><?php echo e($summary['items_sold'] ?? 0); ?></p>
    </div>
</div>

<div class="bg-white rounded-lg shadow mt-4">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="font-medium">Sales Details</h3>
        <a href="<?php echo e(route('reports.export', request()->query())); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Export</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $profit = 0;
                    foreach ($sale->items as $item) {
                        $cost = $item->product->cost_price ?? 0;
                        $profit += ($item->unit_price - $cost) * $item->quantity;
                    }
                ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->invoice); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->customer?->name ?? 'Walk-in'); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->store?->name ?? '-'); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->items->sum('quantity')); ?></td>
                    <td class="px-4 py-3"><?php echo e(format_currency($sale->total)); ?></td>
                    <td class="px-4 py-3"><?php echo e(format_currency($profit)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No sales found for the selected period</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(method_exists($sales, 'links')): ?>
    <div class="p-4 border-t">
        <?php echo e($sales->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/reports/sales.blade.php ENDPATH**/ ?>