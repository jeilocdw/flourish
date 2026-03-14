<?php $__env->startSection('header', 'POS Terminal'); ?>

<?php $__env->startSection('main'); ?>
<script>
    const currencySymbol = '<?php echo e(currency_symbol()); ?>';
</script>
<div class="flex h-screen gap-2 p-4">
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
        
        <div id="products-grid" class="flex-1 overflow-y-auto p-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-0 content-start">
            <!-- Products will be loaded here -->
        </div>
    </div>

    <!-- Cart Panel -->
    <div class="w-96 flex flex-col bg-white rounded-lg shadow h-full">
        <div class="p-4 border-b bg-emerald-600 text-white">
            <h3 class="font-semibold text-lg">Current Sale</h3>
        </div>
        
        <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-2 min-h-[300px]">
            <p class="text-gray-500 text-center py-8">No items in cart</p>
        </div>
        
        <div class="border-t p-4 space-y-3">
            <div class="flex justify-between text-lg font-semibold">
                <span>Total:</span>
                <span id="cart-total">0.00</span>
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
                    <span id="change-amount" class="font-semibold">0.00</span>
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
    <div class="bg-white rounded-lg shadow-xl w-[340px] mx-4" style="background: white !important;">
        <div class="p-2" id="receipt-content" style="max-height: 70vh; overflow-y: auto; background: white !important;">
            <!-- Receipt content will be here -->
        </div>
        <div class="px-4 pb-4 flex gap-3">
            <button id="print-receipt" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Print
            </button>
            <button id="download-receipt" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
                Download
            </button>
            <button id="close-receipt" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg">
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
    
    console.log('Loading products with search:', search, 'category:', categoryId);
    fetch('<?php echo e(route("pos.products")); ?>?search=' + encodeURIComponent(search) + '&category_id=' + categoryId, {
        credentials: 'include'
    })
        .then(res => {
            console.log('Response status:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('Products loaded:', data.length);
            products = data;
            renderProducts();
        })
        .catch(err => console.error('Error loading products:', err));
}

let searchTimeout;
document.getElementById('search-product').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(loadProducts, 300);
});
document.getElementById('filter-category').addEventListener('change', loadProducts);

function renderProducts() {
    const grid = document.getElementById('products-grid');
    
    grid.innerHTML = products.map(product => {
        const stock = product.stock_quantity || 0;
        return `
            <div class="border rounded cursor-pointer hover:border-emerald-500 hover:shadow-md transition h-16 mb-0.5" onclick="addToCart(${product.id})">
                <h4 class="font-medium text-xs truncate px-1" title="${product.name}">${product.name}</h4>
                <p class="text-emerald-600 font-bold text-sm px-1">${currencySymbol}${parseFloat(product.sell_price).toFixed(2)}</p>
                <p class="text-xs px-1 ${stock < 10 ? 'text-red-500' : 'text-gray-500'}">Stock: ${stock}</p>
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
        totalEl.textContent = '0.00';
        completeBtn.disabled = true;
        return;
    }
    
    let total = 0;
    container.innerHTML = cart.map(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        return `
            <div class="flex items-center justify-between p-4 border rounded-lg bg-gray-50 shadow-sm">
                <div class="flex-1 min-w-0 pr-2">
                    <h4 class="font-semibold text-gray-800 truncate">${item.name}</h4>
                    <p class="text-emerald-600 font-bold">${currencySymbol}${item.price.toFixed(2)} x ${item.quantity}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})" class="w-8 h-8 flex items-center justify-center bg-emerald-500 text-white rounded hover:bg-emerald-600 font-bold">-</button>
                    <span class="text-base font-bold w-8 text-center">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})" class="w-8 h-8 flex items-center justify-center bg-emerald-500 text-white rounded hover:bg-emerald-600 font-bold">+</button>
                    <button onclick="removeFromCart(${item.product_id})" class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-100 rounded ml-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    totalEl.textContent = currencySymbol + total.toFixed(2);
    
    const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
    const change = amountPaid - total;
    document.getElementById('change-amount').textContent = currencySymbol + Math.max(0, change).toFixed(2);
    
    completeBtn.disabled = amountPaid < total;
}

document.getElementById('amount-paid').addEventListener('input', renderCart);
document.getElementById('clear-cart').addEventListener('click', () => {
    cart = [];
    document.getElementById('amount-paid').value = '';
    renderCart();
});

document.getElementById('complete-sale').addEventListener('click', () => {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const paid = parseFloat(document.getElementById('amount-paid').value);
    
    console.log('Complete sale clicked - Total:', total, 'Paid:', paid);
    console.log('Cart items:', cart);
    
    if (paid < total) {
        alert('Insufficient payment amount');
        return;
    }
    
    fetch('<?php echo e(route("pos.process-sale")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include',
        body: JSON.stringify({
            items: cart,
            customer_id: document.getElementById('customer-select').value || null,
            payment_method: document.querySelector('input[name="payment_method"]:checked').value,
            paid_amount: paid
        })
    })
    .then(res => {
        console.log('Sale response status:', res.status);
        if (!res.ok) {
            return res.text().then(text => {
                console.log('Error response:', text);
                alert('Error: ' + text);
                throw new Error('Server error: ' + res.status);
            });
        }
        return res.json();
    })
    .then(data => {
        console.log('Sale response data:', data);
        if (data.success) {
            showReceipt(data.sale_id);
            cart = [];
            document.getElementById('amount-paid').value = '';
            renderCart();
        } else {
            alert(data.error);
        }
    })
    .catch(err => {
        console.error('Sale error:', err);
        alert('Error: ' + err.message);
    });
});

function showReceipt(saleId) {
    fetch('<?php echo e(route("pos.index")); ?>/receipt/' + saleId, {
        credentials: 'include'
    })
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
    document.getElementById('receipt-content').innerHTML = '';
    loadProducts();
});

function openReceiptWindow() {
    const printContent = document.getElementById('receipt-content').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Receipt</title>
            <style>
                body { 
                    font-family: 'Courier New', monospace; 
                    font-size: 12px; 
                    width: 80mm; 
                    margin: 0 auto; 
                    padding: 10px;
                }
                @media print {
                    body { 
                        width: 80mm; 
                        margin: 0; 
                        padding: 0;
                    }
                }
            </style>
        </head>
        <body>${printContent}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    return printWindow;
}

document.getElementById('print-receipt').addEventListener('click', () => {
    const printWindow = openReceiptWindow();
    setTimeout(() => {
        printWindow.print();
    }, 250);
});

document.getElementById('download-receipt').addEventListener('click', () => {
    const printWindow = openReceiptWindow();
    setTimeout(() => {
        printWindow.print();
    }, 250);
});

loadProducts();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/pos.blade.php ENDPATH**/ ?>