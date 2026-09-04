    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <style>
            body {
                background-color: #f5f6f8;
                font-family: Arial, Helvetica, sans-serif;
            }

            .product-item {
                cursor: pointer;
            }

            .product-card {
                border: none;
                border-radius: 15px;
                transition: 0.2s;
                overflow: hidden;

            }

            .product-card:hover {
                transform: translateY(-4);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
            }

            .product-image {
                height: 130px;
                display: flex;
                /* align-items: center; */
                justify-content: center;
            }

            .product-image img {
                object-fit: cover;
                width: 100%;
            }

            .price {
                color: #6f4e37;
                font-weight: bold;
            }

            .cart-box {
                position: sticky;
                top: 20px;
            }

            .cart-item {
                border-bottom: 1px solid #eee;
                padding: 12px 0;
            }

            .cart-item:last-child {
                border-bottom: none;
            }

            .quantity-btn {
                width: 30px;
                height: 30px;
                padding: 0;
                border-radius: 50%;
            }

            .total-price {
                font-size: 25px;
                font-weight: bold;
                color: #6f4e37;
            }

            .payment-btn {
                border-radius: 10px;
            }
        </style>
        <title>Kopi PPKD Jakarta Pusat</title>
    </head>

    <body>
        <div class="container-fluid">
            <main class="col-lg-12 p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Point Of Sales</h3>
                        <p class="text-muted">POS - Toko Kopi PPKD Jakarta Pusat</p>

                    </div>
                    <button class="btn btn-dark">Empty Cart</button>
                </div>
                <div class="row g-5 mb-5">
                    <div class="col-md-4">
                        <div class="card shadow p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <i class="bi bi-cart" style="font-size: 2rem"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Today's Transaction</small>
                                    <h4 class="mb-0 fw-bold">10</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <i class="bi bi-cart" style="font-size: 2rem"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Today's Sales</small>
                                    <h4 class="mb-0 fw-bold">Rp. 10.000.000,-</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <i class="bi bi-cart" style="font-size: 2rem"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Product Sold</small>
                                    <h4 class="mb-0 fw-bold">100</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-7">
                                        <h5 class="fw-bold">Select Product</h5>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="" id="searchProduct" class="form-control"
                                            placeholder="Search Product..." onkeyup="searchProduct()">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button class="btn btn-dark btn-sm me-1 category-btn"
                                        onclick="filterCategory('all', this)">Semua</button>
                                    @foreach ($categories as $category)
                                        <button class="btn btn-outline-dark btn-sm me-1 category-btn"
                                            onclick="filterCategory({{ $category->id }}, this)">{{ $category->name ?? '' }}</button>
                                    @endforeach
                                </div>
                                <div class="row g-3" id="productList">
                                    @foreach ($products as $product)
                                        <div class="col-md-4 col-sm-6 product-item"
                                            data-category="{{ $product->category_id }}"
                                            onclick="addToCart({{ $product->id }}, this)" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                            <div class="card product-card shadow h-100">
                                                <div class="product-image"><img
                                                        src="{{ asset('storage/' . $product->photo) }}" alt="">
                                                </div>
                                                <div class="card-body">
                                                    <span class="badge bgt-light text-dark mb-2">
                                                        {{ $product->description ?? '' }}
                                                    </span>
                                                    <h6 class="fw-bold">{{ $product->name ?? '' }}</h6>
                                                    <span
                                                        class="price">{{ number_format($product->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow cart-box p-3">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-cart"></i> Cart
                                </h5>
                                <span class="badge bg-dark" id="cartCount">
                                    0
                                </span>
                            </div>
                            <div class="mb-3" id="cartItems">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-cart4"></i>
                                    <p>Cart Still Empty</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sub Total</span>
                                <strong id="subtotal">Rp. 0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak (11%)</span>
                                <strong id="tax" data-percent="11">Rp. 0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Total</span>
                                <span class="total-price" id="total">Rp. 0</span>
                            </div>
                            <button onclick="processPayment()" class="btn btn-success w-100 py-3">Payment</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
        <script>
            let cart = [];

            async function processPayment() {
                if (cart.length === 0) {
                    alert('Cart is Empty')
                    return;

                    try {
                        const respone = await fetch("{{ route('order.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                items: cart.map((item) => {
                                    return {
                                        id: item.id,
                                        qty: item.qty
                                    }
                                }),
                                payment_method: "cash"
                            })
                        })

                        const result = await response.json();
                        cart = [];
                        displayCart();
                        location.reload();
                    } catch (error) {
                        console.log(error);
                    }
                }
            }

            function filterCategory(categoryId, button) {
                const products = document.querySelectorAll('.product-item');
                products.forEach((product) => {
                    const categoryName = product.dataset.category;
                    if (categoryId === "all" || categoryName === String(categoryId)) {
                        product.style.display = "";
                    } else {
                        product.style.display = "none";
                    }
                });

                document.querySelectorAll('.category-btn').forEach((btn) => {
                    btn.classList.remove('btn-dark', 'active');
                    btn.classList.add('btn-outline-dark');
                });

                button.classList.remove('btn-outline-dark');
                button.classList.add('btn-dark', 'active');
            }

            function addToCart(productId, element) {

                const products = element;
                const productName = products.dataset.name;
                const productPrice = Number(products.dataset.price);

                const existingItem = cart.find((item) => {
                    return Number(item.id) === Number(productId);
                });
                if (existingItem) {
                    existingItem.qty++;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        qty: 1
                    })
                }
                console.log(cart);
                displayCart();

            }

            function displayCart() {
                const cartItems = document.getElementById('cartItems');

                if (cart.length === 0) {
                    cartItems.innerHTML = `<div class="text-center text-muted py-5">
                                    <i class="bi bi-cart4"></i>
                                    <p>Cart Still Empty</p>
                                </div>`;
                    updateCart();
                    return;
                }
                cartItems.innerHTML = '';

                cart.forEach((item, index) => {
                    cartItems.innerHTML += `
                    <div class="cart-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${item.name}</strong>
                                <div class="small text-muted">Rp. ${rupiahFormat(item.price)}</div>
                                </div>
                                <strong>Rp. ${rupiahFormat(item.qty * item.price)}</strong>
                            </div>
                            <div class="d-flex align-items-center mt-3 gap-2">
                                    <button class="btn btn-outline-danger quantity-btn rounded-2" onclick="changeItem(${index}, -1)">-</button>
                                    <span>${item.qty}</span>
                                    <button class="btn btn-outline-success quantity-btn rounded-2" onclick="changeItem(${index}, 1)">+</button>
                                    <button class="btn btn-outline-dark ms-auto" onclick="dumpItem(${index})">
                                        <i class="bi bi-trash"></i>
                                        </button>
                                </div>

                    </div>
                    `;
                });

                updateCart();
            }

            const cartCount = document.getElementById('cartCount');
            const subTotal = document.getElementById('subtotal');
            const tax = document.getElementById('tax');
            const total = document.getElementById('total');

            function updateCart() {


                cartCount.innerText = `${cart.length}`;
                console.log(cart.length);

                let subTotalCount = 0;
                const taxes = tax.dataset.percent / 100;
                console.log(taxes);
                console.log(tax);
                console.log(subTotal);
                console.log(total);

                cart.forEach((item, index) => {
                    subTotalCount += item.price * item.qty;
                });
                tax.innerText = `Rp. ${rupiahFormat(subTotalCount * taxes)}`;
                subTotal.innerText = `Rp. ${rupiahFormat(subTotalCount)}`;
                total.textContent = `Rp. ${rupiahFormat(subTotalCount * taxes + subTotalCount)}`;


            }

            function changeItem(index, change) {
                if (cart[index].qty === 1 && change === -1) {
                    dumpItem(index);
                    return;
                }
                cart[index].qty += change;
                displayCart();
                return;
            }

            function dumpItem(index) {
                cart.splice(index, 1);
                displayCart();
                return;
            }

            function rupiahFormat(number) {
                return number.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                })
            }

            const search = document.getElementById('searchProduct');

            function searchProduct() {
                const searchValue = search.value.toLowerCase().trim();
                const products = document.querySelectorAll('.product-item');

                products.forEach((product) => {
                    const productName = product.dataset.name.toLowerCase();

                    if (productName.includes(searchValue)) {
                        product.style.display = "";
                    } else {
                        product.style.display = "none";
                    }
                });
            }
        </script>
    </body>

    </html>
