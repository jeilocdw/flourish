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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <select name="currency" id="currency-select" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Select Currency</option>
                            <option value="USD" data-symbol="$" <?php echo e(($settings['currency'] ?? '') === 'USD' ? 'selected' : ''); ?>>USD - US Dollar</option>
                            <option value="EUR" data-symbol="€" <?php echo e(($settings['currency'] ?? '') === 'EUR' ? 'selected' : ''); ?>>EUR - Euro</option>
                            <option value="GBP" data-symbol="£" <?php echo e(($settings['currency'] ?? '') === 'GBP' ? 'selected' : ''); ?>>GBP - British Pound</option>
                            <option value="NGN" data-symbol="₦" <?php echo e(($settings['currency'] ?? '') === 'NGN' ? 'selected' : ''); ?>>NGN - Nigerian Naira</option>
                            <option value="KES" data-symbol="KSh" <?php echo e(($settings['currency'] ?? '') === 'KES' ? 'selected' : ''); ?>>KES - Kenyan Shilling</option>
                            <option value="GHS" data-symbol="₵" <?php echo e(($settings['currency'] ?? '') === 'GHS' ? 'selected' : ''); ?>>GHS - Ghanaian Cedi</option>
                            <option value="ZAR" data-symbol="R" <?php echo e(($settings['currency'] ?? '') === 'ZAR' ? 'selected' : ''); ?>>ZAR - South African Rand</option>
                            <option value="INR" data-symbol="₹" <?php echo e(($settings['currency'] ?? '') === 'INR' ? 'selected' : ''); ?>>INR - Indian Rupee</option>
                            <option value="BDT" data-symbol="৳" <?php echo e(($settings['currency'] ?? '') === 'BDT' ? 'selected' : ''); ?>>BDT - Bangladeshi Taka</option>
                            <option value="PKR" data-symbol="₨" <?php echo e(($settings['currency'] ?? '') === 'PKR' ? 'selected' : ''); ?>>PKR - Pakistani Rupee</option>
                            <option value="PHP" data-symbol="₱" <?php echo e(($settings['currency'] ?? '') === 'PHP' ? 'selected' : ''); ?>>PHP - Philippine Peso</option>
                            <option value="IDR" data-symbol="Rp" <?php echo e(($settings['currency'] ?? '') === 'IDR' ? 'selected' : ''); ?>>IDR - Indonesian Rupiah</option>
                            <option value="MYR" data-symbol="RM" <?php echo e(($settings['currency'] ?? '') === 'MYR' ? 'selected' : ''); ?>>MYR - Malaysian Ringgit</option>
                            <option value="SGD" data-symbol="S$" <?php echo e(($settings['currency'] ?? '') === 'SGD' ? 'selected' : ''); ?>>SGD - Singapore Dollar</option>
                            <option value="AUD" data-symbol="A$" <?php echo e(($settings['currency'] ?? '') === 'AUD' ? 'selected' : ''); ?>>AUD - Australian Dollar</option>
                            <option value="CAD" data-symbol="C$" <?php echo e(($settings['currency'] ?? '') === 'CAD' ? 'selected' : ''); ?>>CAD - Canadian Dollar</option>
                            <option value="JPY" data-symbol="¥" <?php echo e(($settings['currency'] ?? '') === 'JPY' ? 'selected' : ''); ?>>JPY - Japanese Yen</option>
                            <option value="CNY" data-symbol="¥" <?php echo e(($settings['currency'] ?? '') === 'CNY' ? 'selected' : ''); ?>>CNY - Chinese Yuan</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" id="currency-symbol" value="<?php echo e($settings['currency_symbol'] ?? '$'); ?>" class="w-full px-4 py-2 border rounded-lg">
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

<script>
document.getElementById('currency-select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const symbol = selectedOption.dataset.symbol || '';
    document.getElementById('currency-symbol').value = symbol;
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/settings/index.blade.php ENDPATH**/ ?>