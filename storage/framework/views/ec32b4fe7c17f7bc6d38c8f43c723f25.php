<?php $__env->startSection('header', 'Sale Details'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold">Invoice: <?php echo e($sale->invoice); ?></h2>
            <p class="text-gray-500">Date: <?php echo e($sale->created_at->format('M d, Y H:i')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('pos.receipt', $sale)); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700" target="_blank">Print Receipt</a>
            <a href="<?php echo e(route('sales.index')); ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-semibold mb-2">Store</h3>
            <p><?php echo e($sale->store->name ?? 'N/A'); ?></p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Cashier</h3>
            <p><?php echo e($sale->user->name ?? 'N/A'); ?></p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Customer</h3>
            <p><?php echo e($sale->customer->name ?? 'Walk-in Customer'); ?></p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Payment Method</h3>
            <p class="capitalize"><?php echo e($sale->payment_method); ?></p>
        </div>
    </div>

    <h3 class="font-semibold mb-2">Items (<?php echo e($items->count()); ?>)</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-right">Price</th>
                    <th class="px-4 py-2 text-right">Qty</th>
                    <th class="px-4 py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-2"><?php echo e($item->product->name ?? 'Product #' . $item->product_id); ?></td>
                    <td class="px-4 py-2 text-right"><?php echo e(format_currency($item->unit_price)); ?></td>
                    <td class="px-4 py-2 text-right"><?php echo e($item->quantity); ?></td>
                    <td class="px-4 py-2 text-right"><?php echo e(format_currency($item->total)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">No items found</td>
                </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-semibold">Subtotal</td>
                    <td class="px-4 py-2 text-right"><?php echo e(format_currency($sale->subtotal)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-semibold">Tax</td>
                    <td class="px-4 py-2 text-right"><?php echo e(format_currency($sale->tax)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                    <td class="px-4 py-2 text-right font-bold"><?php echo e(format_currency($sale->total)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right">Paid</td>
                    <td class="px-4 py-2 text-right"><?php echo e(format_currency($sale->paid)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right">Change</td>
                    <td class="px-4 py-2 text-right"><?php echo e(format_currency($sale->change_amount ?? 0)); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/sales/show.blade.php ENDPATH**/ ?>