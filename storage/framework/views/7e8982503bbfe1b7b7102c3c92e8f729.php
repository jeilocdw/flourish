<?php $__env->startSection('header', 'Settings'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <form method="POST" action="<?php echo e(route('settings.update')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium mb-4">Store Information</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                        <input type="text" name="store_name" value="<?php echo e($settings['store_name'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store Address</label>
                        <textarea name="store_address" rows="2" class="w-full px-4 py-2 border rounded-lg"><?php echo e($settings['store_address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store Phone</label>
                        <input type="text" name="store_phone" value="<?php echo e($settings['store_phone'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store Email</label>
                        <input type="email" name="store_email" value="<?php echo e($settings['store_email'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium mb-4">Business Settings</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="<?php echo e($settings['currency_symbol'] ?? '$'); ?>" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" value="<?php echo e($settings['tax_rate'] ?? 0); ?>" step="0.01" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert</label>
                        <input type="number" name="low_stock_alert" value="<?php echo e($settings['low_stock_alert'] ?? 10); ?>" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="invoice_prefix" value="1" <?php echo e(($settings['invoice_prefix'] ?? true) ? 'checked' : ''); ?> class="mr-2">
                            <span class="text-sm text-gray-700">Enable Invoice Prefix</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Settings</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/settings/index.blade.php ENDPATH**/ ?>