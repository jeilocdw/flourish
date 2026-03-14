<?php $__env->startSection('header', 'Purchases'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search by reference..." class="px-4 py-2 border rounded-lg" value="<?php echo e(request('search')); ?>">
            <input type="date" name="date_from" class="px-4 py-2 border rounded-lg" value="<?php echo e(request('date_from')); ?>">
            <input type="date" name="date_to" class="px-4 py-2 border rounded-lg" value="<?php echo e(request('date_to')); ?>">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="<?php echo e(route('purchases.create')); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Purchase</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($purchase->reference_number); ?></td>
                    <td class="px-4 py-3"><?php echo e($purchase->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="px-4 py-3"><?php echo e($purchase->supplier?->name ?? 'N/A'); ?></td>
                    <td class="px-4 py-3"><?php echo e($purchase->items_count ?? $purchase->items->count()); ?></td>
                    <td class="px-4 py-3"><?php echo e(format_currency($purchase->total)); ?></td>
                    <td class="px-4 py-3">
                        <?php if($purchase->status === 'received'): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs">Received</span>
                        <?php elseif($purchase->status === 'pending'): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded text-xs">Pending</span>
                        <?php elseif($purchase->status === 'ordered'): ?>
                            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-xs">Ordered</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs"><?php echo e(ucfirst($purchase->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('purchases.show', $purchase)); ?>" class="text-blue-600 hover:underline mr-2">View</a>
                        <a href="<?php echo e(route('purchases.edit', $purchase)); ?>" class="text-gray-600 hover:underline">Edit</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No purchases found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(method_exists($purchases, 'links')): ?>
    <div class="p-4 border-t">
        <?php echo e($purchases->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/purchases/index.blade.php ENDPATH**/ ?>