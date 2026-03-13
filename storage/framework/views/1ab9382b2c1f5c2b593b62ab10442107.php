<?php $__env->startSection('header', 'POS Terminal'); ?>

<?php $__env->startSection('main'); ?>
<div class="flex h-[calc(100vh-140px)] gap-6">
    <!-- Products Panel -->
    <div class="flex-1 flex flex-col bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="flex gap-4 mb-4">
                <input type="text" id="search-product" placeholder="Search products..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <select id="filter-category" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        
        <div id="products-grid" class="flex-1 overflow-y-auto p-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Products will be loaded here -->
        </div>
    </div>

    <!-- Cart Panel -->
    <div class="w-96 flex flex-col bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold">Current Sale</h3>
        </div>
        
        <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
            <p class="text-gray-500 text-center">No items in cart</p>
        </div>
        
        <div class="border-t p-4 space-y-3">
            <div class="flex justify-between text-lg font-semibold">
                <span>Total:</span>
                <span id="cart-total">$0.00</span>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium">Customer (Optional)</label>
                <select id="customer-select" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Walk-in Customer</option>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium">Payment Method</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="cash" checked class="mr-2">
                        <span>Cash</span>
                    </label>
                    <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="card" class="mr-2">
                        <span>Card</span>
                    </label>
                    <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="mobile" class="mr-2">
                        <span>Mobile</span>
                    </label>
                    <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="bank_transfer" class="mr-2">
                        <span>Bank</span>
                    </label>
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium">Amount Paid</label>
                <input type="number" id="amount-paid" class="w-full px-3 py-2 border rounded-lg text-lg" placeholder="0.00" step="0.01">
            </div>
            
            <div class="flex justify-between text-sm">
                <span>Change:</span>
                <span id="change-amount" class="font-semibold">$0.00</span>
            </div>
            
            <button id="complete-sale" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Complete Sale
            </button>
            
            <button id="clear-cart" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg transition">
                Clear Cart
            </button>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receipt-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6" id="receipt-content">
            <!-- Receipt content will be here -->
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button id="print-receipt" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg">
                Print
            </button>
            <button id="close-receipt" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg">
                Close
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let cart = [];
let products = [];

function loadProducts() {
    const search = document.getElementById('search-product').value;
    const categoryId = document.getElementById('filter-category').value;
    
    fetch(`/pos/products?search=${search}&category_id=${categoryId}`)
        .then(res => res.json())
        .then(data => {
            products = data;
            renderProducts();
        });
}

function renderProducts() {
    const grid = document.getElementById('products-grid');
    grid.innerHTML = products.map(product => {
        const stock = product.product_store?.[0]?.quantity || 0;
        return `
            <div class="border rounded-lg p-3 cursor-pointer hover:border-emerald-500 hover:shadow-md transition" onclick="addToCart(${product.id})">
                <div class="h-24 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h4 class="font-medium text-sm truncate">${product.name}</h4>
                <p class="text-emerald-600 font-bold">$${parseFloat(product.sell_price).toFixed(2)}</p>
                <p class="text-xs ${stock < 10 ? 'text-red-500' : 'text-gray-500'}">Stock: ${stock}</p>
            </div>
        `;
    }).join('');
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    const existing = cart.find(item => item.product_id === productId);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({
            product_id: productId,
            name: product.name,
            price: parseFloat(product.sell_price),
            quantity: 1
        });
    }
    renderCart();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.product_id !== productId);
    renderCart();
}

function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        removeFromCart(productId);
        return;
    }
    const item = cart.find(i => i.product_id === productId);
    if (item) {
        item.quantity = quantity;
        renderCart();
    }
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('cart-total');
    const completeBtn = document.getElementById('complete-sale');
    
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">No items in cart</p>';
        totalEl.textContent = '$0.00';
        completeBtn.disabled = true;
        return;
    }
    
    let total = 0;
    container.innerHTML = cart.map(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        return `
            <div class="flex items-center justify-between p-2 border rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-sm">${item.name}</h4>
                    <p class="text-gray-500 text-xs">$${item.price.toFixed(2)} x ${item.quantity}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})" class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded">-</button>
                    <span class="text-sm font-medium">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})" class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded">+</button>
                    <button onclick="removeFromCart(${item.product_id})" class="text-red-500 ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    totalEl.textContent = '$' + total.toFixed(2);
    
    const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
    const change = amountPaid - total;
    document.getElementById('change-amount').textContent = '$' + Math.max(0, change).toFixed(2);
    
    completeBtn.disabled = amountPaid < total;
}

document.getElementById('search-product').addEventListener('input', loadProducts);
document.getElementById('filter-category').addEventListener('change', loadProducts);
document.getElementById('amount-paid').addEventListener('input', renderCart);
document.getElementById('clear-cart').addEventListener('click', () => {
    cart = [];
    document.getElementById('amount-paid').value = '';
    renderCart();
});

document.getElementById('complete-sale').addEventListener('click', () => {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const paid = parseFloat(document.getElementById('amount-paid').value);
    
    if (paid < total) {
        alert('Insufficient payment amount');
        return;
    }
    
    fetch('/pos/process-sale', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            items: cart,
            customer_id: document.getElementById('customer-select').value || null,
            payment_method: document.querySelector('input[name="payment_method"]:checked').value,
            paid_amount: paid
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showReceipt(data.sale_id);
            cart = [];
            document.getElementById('amount-paid').value = '';
            renderCart();
        } else {
            alert(data.error);
        }
    });
});

function showReceipt(saleId) {
    fetch(`/pos/receipt/${saleId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('receipt-content').innerHTML = html;
            document.getElementById('receipt-modal').classList.remove('hidden');
            document.getElementById('receipt-modal').classList.add('flex');
        });
}

document.getElementById('close-receipt').addEventListener('click', () => {
    document.getElementById('receipt-modal').classList.add('hidden');
    document.getElementById('receipt-modal').classList.remove('flex');
});

document.getElementById('print-receipt').addEventListener('click', () => {
    window.print();
});

loadProducts();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/pos.blade.php ENDPATH**/ ?>