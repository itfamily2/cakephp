<?php $this->assign('title', 'Point of Sale (Create Order)'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* AJAX Search Results Dropdown */
.search-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    z-index: 1050;
    max-height: 300px;
    overflow-y: auto;
    display: none;
}
.search-result-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s;
}
.search-result-item:hover {
    background-color: #f1f3f5;
}
.search-result-item:last-child {
    border-bottom: none;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-cash-register text-primary me-2"></i>Point of Sale</h4>
        <p class="text-muted mb-0">Create new orders and ring up products.</p>
    </div>
</div>

<?= $this->Form->create($order, ['id' => 'pos-form']) ?>
<?php 
// Unlock dynamically generated JS fields so FormProtection doesn't crash
$this->Form->unlockField('order_items'); 
?>
<div class="row g-4">
    <!-- Left Column: Product Selection -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-barcode text-info me-2"></i>Add Products</h6>
            </div>
            <div class="card-body p-4">
                
                <!-- Global AJAX Search -->
                <div class="mb-4 position-relative">
                    <label class="form-label text-muted small fw-bold">Search Products (AJAX)</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="ajax-product-search" class="form-control border-start-0 ps-0" placeholder="Type product name to search..." autocomplete="off">
                    </div>
                    <!-- Search Results Container -->
                    <div id="search-results" class="search-results-dropdown"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="cart-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 text-secondary font-monospace small">PRODUCT</th>
                                <th class="border-0 text-secondary font-monospace small" width="120">PRICE</th>
                                <th class="border-0 text-secondary font-monospace small" width="120">QTY</th>
                                <th class="border-0 text-secondary font-monospace small text-end" width="100">TOTAL</th>
                                <th class="border-0" width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <!-- Items will be injected here via JS -->
                            <tr id="empty-cart-row">
                                <td colspan="5" class="text-center text-muted py-4">Cart is empty. Search for a product above.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Order Details & Checkout -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-receipt text-warning me-2"></i>Order Summary</h6>
            </div>
            <div class="card-body p-4 bg-light">
                
                <div class="mb-3">
                    <?= $this->Form->control('status', [
                        'options' => ['Draft' => 'Draft', 'Pending' => 'Pending', 'Confirmed' => 'Confirmed', 'Completed' => 'Completed'],
                        'default' => 'Confirmed',
                        'class' => 'form-select text-dark shadow-sm',
                        'label' => ['class' => 'form-label text-muted small fw-bold']
                    ]) ?>
                </div>
                
                <div class="mb-4">
                    <?= $this->Form->control('notes', [
                        'type' => 'textarea',
                        'rows' => 2,
                        'class' => 'form-control text-dark shadow-sm',
                        'placeholder' => 'Optional order notes...',
                        'label' => ['class' => 'form-label text-muted small fw-bold']
                    ]) ?>
                </div>

                <div class="bg-white p-4 rounded shadow-sm border mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold font-monospace text-dark" id="summary-subtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (10%)</span>
                        <span class="fw-bold font-monospace text-dark" id="summary-tax">$0.00</span>
                    </div>
                    <hr class="text-muted">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">Grand Total</span>
                        <span class="fw-bold font-monospace text-success fs-4" id="summary-total">$0.00</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm fw-bold" id="checkout-btn" disabled>
                    <i class="fa-solid fa-lock me-2"></i>Complete Order
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ajax-product-search');
    const searchResults = document.getElementById('search-results');
    const cartBody = document.getElementById('cart-body');
    const emptyCartRow = document.getElementById('empty-cart-row');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    let cart = [];
    let searchTimeout = null;

    // --- AJAX Search Logic ---
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const term = this.value.trim();
        
        if (term.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch('<?= $this->Url->build(['controller' => 'Products', 'action' => 'autocomplete']) ?>?term=' + encodeURIComponent(term), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';
                if (data.suggestions && data.suggestions.length > 0) {
                    data.suggestions.forEach(product => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item d-flex justify-content-between align-items-center';
                        const brand = product.brand ? product.brand.name : 'Unknown Brand';
                        const category = product.category ? product.category.name : 'Unknown Category';
                        const context = `${brand} &bull; ${category} &bull; Stock: ${product.stock}`;

                        div.innerHTML = `
                            <div>
                                <span class="fw-bold text-dark">${product.name}</span>
                                <small class="text-muted d-block">${context}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill font-monospace fs-6">$${parseFloat(product.price).toFixed(2)}</span>
                        `;
                        div.addEventListener('click', () => {
                            addToCart(product.id, product.name, product.price, product.stock);
                            searchResults.style.display = 'none';
                            searchInput.value = '';
                            searchInput.focus();
                        });
                        searchResults.appendChild(div);
                    });
                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = '<div class="p-3 text-muted text-center">No products found.</div>';
                    searchResults.style.display = 'block';
                }
            });
        }, 300); // 300ms debounce
    });

    // Close dropdown on click outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // --- Cart Logic ---
    function addToCart(id, name, price, maxStock) {
        let existing = cart.find(i => i.id === id);
        if (existing) {
            if (existing.qty < maxStock) existing.qty++;
        } else {
            cart.push({ id: id, name: name, price: parseFloat(price), qty: 1, maxStock: maxStock });
        }
        renderCart();
    }

    function updateSummary() {
        let subtotal = 0;
        cart.forEach(item => { subtotal += (item.price * item.qty); });
        let tax = subtotal * 0.10; // 10% flat tax for POS
        let grandTotal = subtotal + tax;
        
        document.getElementById('summary-subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('summary-tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('summary-total').textContent = '$' + grandTotal.toFixed(2);
        checkoutBtn.disabled = cart.length === 0;
    }

    function renderCart() {
        if (cart.length === 0) {
            cartBody.innerHTML = '';
            cartBody.appendChild(emptyCartRow);
            emptyCartRow.style.display = 'table-row';
            updateSummary();
            return;
        }

        emptyCartRow.style.display = 'none';
        cartBody.innerHTML = '';
        
        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            let hiddenInputs = `
                <input type="hidden" name="order_items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="order_items[${index}][quantity]" value="${item.qty}">
                <input type="hidden" name="order_items[${index}][price]" value="${item.price}">
                <input type="hidden" name="order_items[${index}][tax]" value="${(item.price * item.qty * 0.10).toFixed(2)}">
                <input type="hidden" name="order_items[${index}][discount]" value="0">
            `;
            
            tr.innerHTML = `
                ${hiddenInputs}
                <td class="fw-bold text-dark">${item.name}</td>
                <td class="font-monospace text-muted">$${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center qty-input" data-index="${index}" value="${item.qty}" min="1" max="${item.maxStock}" style="width: 70px;">
                </td>
                <td class="font-monospace text-dark fw-bold text-end">$${(item.price * item.qty).toFixed(2)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-btn" data-index="${index}"><i class="fa-solid fa-times"></i></button>
                </td>
            `;
            cartBody.appendChild(tr);
        });
        
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                let idx = this.getAttribute('data-index');
                let newQty = parseInt(this.value);
                if(newQty > 0) {
                    cart[idx].qty = newQty;
                    renderCart();
                }
            });
        });
        
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let idx = this.getAttribute('data-index');
                cart.splice(idx, 1);
                renderCart();
            });
        });

        updateSummary();
    }

    // --- Form Submit via AJAX ---
    const posForm = document.getElementById('pos-form');
    posForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }

        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Processing...';

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Show SweetAlert success with Create Invoice button
                Swal.fire({
                    title: 'Order Completed!',
                    text: data.message || 'Order was created successfully.',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-file-invoice me-2"></i>Generate Invoice',
                    cancelButtonText: 'New Order',
                    reverseButtons: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the fantastic Invoice Generator UI we built, pre-selecting this order!
                        window.location.href = '<?= $this->Url->build(['controller' => 'Invoices', 'action' => 'add']) ?>?order_id=' + data.order.id;
                    } else {
                        // Clear cart for a new order
                        cart = [];
                        renderCart();
                        searchInput.focus();
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = '<i class="fa-solid fa-lock me-2"></i>Complete Order';
                    }
                });
            } else {
                // Show error notification
                Swal.fire('Error', data.message || 'Error processing order.', 'error');
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = '<i class="fa-solid fa-lock me-2"></i>Complete Order';
            }
        })
        .catch(err => {
            Swal.fire('Error', 'An unexpected error occurred.', 'error');
            console.error(err);
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '<i class="fa-solid fa-lock me-2"></i>Complete Order';
        });
    });
});
</script>
