<?php $__env->startSection('header', 'Expiring Soon'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <div class="flex gap-4">
            <a href="<?php echo e(route('inventory.index')); ?>" class="px-4 py-2 rounded-lg <?php echo e(request()->routeIs('inventory.index') ? 'bg-emerald-600 text-white' : 'bg-gray-200'); ?>">All Items</a>
            <a href="<?php echo e(route('inventory.lowStock')); ?>" class="px-4 py-2 rounded-lg <?php echo e(request()->routeIs('inventory.lowStock') ? 'bg-emerald-600 text-white' : 'bg-gray-200'); ?>">Low Stock</a>
            <a href="<?php echo e(route('inventory.expiring')); ?>" class="px-4 py-2 rounded-lg <?php echo e(request()->routeIs('inventory.expiring') ? 'bg-emerald-600 text-white' : 'bg-gray-200'); ?>">Expiring</a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($item->name ?? 'N/A'); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->category?->name ?? 'N/A'); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->productStore->first()?->quantity ?? 0); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->expiry_date?->format('Y-m-d') ?? 'N/A'); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded text-xs">Expiring Soon</span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No expiring products found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(method_exists($products, 'links')): ?>
    <div class="p-4 border-t">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/inventory/expiring.blade.php ENDPATH**/ ?>