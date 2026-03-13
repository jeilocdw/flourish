<?php $__env->startSection('header', 'Sales'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search by invoice number..." class="px-4 py-2 border rounded-lg" value="<?php echo e(request('search')); ?>">
            <input type="date" name="date_from" class="px-4 py-2 border rounded-lg" value="<?php echo e(request('date_from')); ?>">
            <input type="date" name="date_to" class="px-4 py-2 border rounded-lg" value="<?php echo e(request('date_to')); ?>">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($sale->invoice_number); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->customer?->name ?? 'Walk-in Customer'); ?></td>
                    <td class="px-4 py-3"><?php echo e($sale->items_count ?? $sale->items->count()); ?></td>
                    <td class="px-4 py-3">$<?php echo e(number_format($sale->total_amount, 2)); ?></td>
                    <td class="px-4 py-3">
                        <?php if($sale->status === 'completed'): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs">Completed</span>
                        <?php elseif($sale->status === 'pending'): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded text-xs">Pending</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs"><?php echo e(ucfirst($sale->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('sales.show', $sale)); ?>" class="text-blue-600 hover:underline mr-2">View</a>
                        <a href="<?php echo e(route('pos.receipt', $sale)); ?>" class="text-gray-600 hover:underline" target="_blank">Receipt</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No sales found</td>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/sales/index.blade.php ENDPATH**/ ?>