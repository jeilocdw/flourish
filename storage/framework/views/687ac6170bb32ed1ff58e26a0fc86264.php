<div id="receipt-print" style="font-family: 'Courier New', monospace; font-size: 12px; width: 80mm; margin: 0 auto; padding: 5px;">
    <div style="text-align: center; margin-bottom: 16px;">
        <h2 style="margin: 0; font-size: 16px;">Flourish Supermarket</h2>
        <p style="margin: 4px 0; font-size: 11px;"><?php echo e($sale->store?->name ?? 'Main Store'); ?></p>
        <p style="margin: 0; font-size: 10px;"><?php echo e($sale->store?->address ?? ''); ?></p>
    </div>

    <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 12px 0; margin: 12px 0;">
        <div style="display: flex; justify-content: space-between;">
            <span>Invoice:</span>
            <span><?php echo e($sale->invoice); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>Date:</span>
            <span><?php echo e($sale->created_at->format('Y-m-d H:i')); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>Cashier:</span>
            <span><?php echo e($sale->user->name); ?></span>
        </div>
        <?php if($sale->customer): ?>
        <div style="display: flex; justify-content: space-between;">
            <span>Customer:</span>
            <span><?php echo e($sale->customer->name); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin: 12px 0;">
        <thead>
            <tr style="border-bottom: 1px dashed #000;">
                <th style="text-align: left; padding: 4px 0;">Item</th>
                <th style="text-align: right; padding: 4px 0;">Qty</th>
                <th style="text-align: right; padding: 4px 0;">Price</th>
                <th style="text-align: right; padding: 4px 0;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="padding: 3px 0;"><?php echo e($item->product->name); ?></td>
                <td style="text-align: right; padding: 3px 0;"><?php echo e($item->quantity); ?></td>
                <td style="text-align: right; padding: 3px 0;"><?php echo e(format_currency($item->unit_price)); ?></td>
                <td style="text-align: right; padding: 3px 0;"><?php echo e(format_currency($item->total)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div style="border-top: 1px dashed #000; padding-top: 12px; margin-top: 12px;">
        <div style="display: flex; justify-content: space-between;">
            <span>Subtotal:</span>
            <span><?php echo e(format_currency($sale->subtotal)); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>Tax:</span>
            <span><?php echo e(format_currency($sale->tax)); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 14px;">
            <span>TOTAL:</span>
            <span><?php echo e(format_currency($sale->total)); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span>Paid:</span>
            <span><?php echo e(format_currency($sale->paid)); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>Change:</span>
            <span><?php echo e(format_currency($sale->paid - $sale->total)); ?></span>
        </div>
    </div>

    <div style="text-align: center; margin-top: 16px; font-size: 11px;">
        <p>Payment: <?php echo e(ucfirst($sale->payment_method)); ?></p>
        <p style="margin-top: 8px;">Thank you for shopping with us!</p>
    </div>
</div>
<?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/pos/receipt.blade.php ENDPATH**/ ?>