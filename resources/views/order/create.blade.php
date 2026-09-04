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
                transition: transform 0.25s ease, box-shadow 0.25s ease;
                overflow: hidden;

            }

            .product-card:hover {
                transform: translateY(-4);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
                transform: translateY(-6px);
                box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
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

            .cursor-pointer {
                cursor: pointer;
            }

            @media print {

                @page {
                    margin: 0;
                }

                body * {
                    visibility: hidden;
                }


                #receipt-print-area,
                #receipt-print-area * {
                    visibility: visible;
                }

                #receipt-print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
            }
        </style>
        <title>Kopi PPKD Jakarta Pusat</title>
    </head>

    <body>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="paymentMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="paymentMethodLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="paymentMethodLabel">Status</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label fw-semibold">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name">
                        </div>
                        <div class="row mb-3" style="display:none" id="cashPaymentBody">
                            <div class="col-md-12 mb01">
                                <strong class="bg-success p-2 text-white rounded" id='total-paid'>
                                    Harga : Rp.0
                                </strong>
                            </div>
                            <div class="row only-cash d-block align-items-center mt-3">
                                <div class="col-md-12 mb-2">
                                    <label for="cash_paid" class="form-label" id="label-paid">
                                        Pembayaran Cash :
                                    </label>
                                    <input type="number" name="" step="any" min="0" max="2"
                                        id="cash-paid" class="form-control" oninput="calculateChange()">
                                </div>
                                <div class="col-md-12">
                                    <strong class="bg-danger p-2 text-white rounded" id="change-paid">Kurang :
                                        Rp.0</strong>
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">Pilih Metode Pembayaran</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cash-option" class="w-100 cursor-pointer" onclick="displayCashPayment()">
                                    <input type="radio" name="payment_method" value="cash"
                                        class="d-none payment-option" id="cash-option">
                                    <div
                                        class="card p-3 shadow-sm border payment-card text-center h-100 border-success bg-light">
                                        <h4 class="text-success fw-bold">
                                            <i class="bi bi-cash-stack"> Cash</i>
                                            <p class="text-muted small">Bayar langsung di kasir secara tunai</p>
                                        </h4>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label for="midtrans-option" class="w-100 cursor-pointer"
                                    onclick="undisplayCashPayment()">
                                    <input type="radio" name="payment_method" value="midtrans"
                                        class="d-none payment-option" id="midtrans-option">
                                    <div class="card p-3 shadow-sm border payment-card text-center h-100">
                                        <h4 class="text-success fw-bold">
                                            <i class="bi bi-cash-stack"> Midtrans</i>
                                            <p class="text-muted small">Bayar online via QRIS / E-Wallet</p>
                                        </h4>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                            onclick="processPayment()">Pay Now!</button>
                    </div>
                </div>
            </div>
        </div>
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
                    <!-- Left Column: Product Selection -->
                    <div class="col-lg-8">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <!-- Header & Search -->
                                <div class="row mb-4 align-items-center g-3">
                                    <div class="col-md-7">
                                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                            <i class="bi bi-grid-fill"></i> Select Product
                                        </h5>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="bi bi-search text-muted"></i>
                                            </span>
                                            <input type="text" name="" id="searchProduct"
                                                class="form-control border-start-0" placeholder="Search Product..."
                                                onkeyup="searchProduct()">
                                        </div>
                                    </div>
                                </div>

                                <!-- Category Filters -->
                                <div class="mb-4">
                                    <button class="btn btn-dark btn-sm me-1 category-btn shadow-sm"
                                        onclick="filterCategory('all', this)">
                                        <i class="bi bi-border-all me-1"></i> Semua
                                    </button>
                                    @foreach ($categories as $category)
                                        <button class="btn btn-outline-dark btn-sm me-1 category-btn"
                                            onclick="filterCategory({{ $category->id }}, this)">
                                            <i class="bi bi-tag me-1"></i> {{ $category->name ?? '' }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Product Grid (Original Card Structure Restored) -->
                                <div class="row g-3" id="productList">
                                    @foreach ($products as $product)
                                        <div class="col-md-4 col-sm-6 product-item cursor-pointer"
                                            data-category="{{ $product->category_id }}"
                                            onclick="addToCart({{ $product->id }}, this)"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}">

                                            <div class="card product-card shadow h-100">
                                                <div class="product-image">
                                                    <img src="{{ asset('storage/' . $product->photo) }}"
                                                        alt="">
                                                </div>
                                                <div class="card-body">
                                                    <span class="badge bgt-light text-dark mb-2">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        {{ $product->description ?? '' }}
                                                    </span>
                                                    <h6 class="fw-bold">{{ $product->name ?? '' }}</h6>
                                                    <span class="price">Rp
                                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Cart -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow cart-box p-3 sticky-lg-top" style="top: 1.5rem;">
                            <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-2">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-cart3"></i> Cart
                                </h5>
                                <span class="badge bg-dark" id="cartCount">
                                    0
                                </span>
                            </div>
                            <div class="mb-3 custom-scrollbar" id="cartItems"
                                style="max-height: 320px; overflow-y: auto;">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-cart4 fs-1 opacity-50 d-block mb-2"></i>
                                    <p class="mb-0">Cart Still Empty</p>
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
                            <div class="d-flex justify-content-between mb-3 pt-2 border-top">
                                <span class="fw-bold">Total</span>
                                <span class="total-price fw-bold" id="total">Rp. 0</span>
                            </div>
                            <button
                                class="btn btn-success w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 btn-pay"
                                onclick="openModalPayment()">
                                <i class="bi bi-credit-card"></i> Payment
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div id="receipt-print-area" class="d-none d-print-block">
            <div
                style="width: 80mm; font-family: 'Courier New', Courier, monospace; font-size: 12px; margin: 0 auto; padding: 10px;">
                <div class="text-center mb-3">
                    <h4 class="fw-bold mb-1">{{ $settings['app_name'] }}</h4>
                    <p class="mb-0">{{ $settings['app_address'] }}</p>
                    <p class="mb-0">Telp: {{ $settings['app_phone'] }}</p>
                    <p class="mb-0">--------------------------------</p>
                </div>

                <div class="mb-2">
                    <div><strong>No. Order:</strong> <span id="receipt-order-id">-</span></div>
                    <div><strong>Code Order:</strong> <span id="receipt-order-code">-</span></div>
                    <div><strong>Tgl:</strong> <span id="receipt-date">-</span></div>
                    <div id="payment-method-success"><strong>Metode:</strong> Midtrans</div>
                </div>

                <p class="mb-1">--------------------------------</p>

                <table style="width: 100%; border-collapse: collapse;" id="receipt-items-table">
                </table>

                <p class="mb-1">--------------------------------</p>

                <div style="display: flex; justify-content: space-between;">
                    <span>Subtotal:</span>
                    <span id="receipt-subtotal">Rp 0</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Pajak (11%):</span>
                    <span id="receipt-tax">Rp 0</span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; font-weight: bold; font-size: 14px; margin-top: 4px;">
                    <span>TOTAL:</span>
                    <span id="receipt-total">Rp 0</span>
                </div>

                <p class="mb-1">--------------------------------</p>
                <div class="text-center mt-3">
                    <p class="mb-0">Terima Kasih</p>
                    <p class="mb-0">Selamat Belanja Kembali!</p>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener('afterprint', function() {
                location.reload();
            });



            const cartCount = document.getElementById('cartCount');
            const subTotal = document.getElementById('subtotal');
            const tax = document.getElementById('tax');
            const total = document.getElementById('total');
            const totalPaid = document.getElementById('total-paid');
            const cashPaid = document.getElementById('cash-paid');
            const changePaid = document.getElementById('change-paid');
            const labelPaid = document.getElementById('label-paid');
            let totalCurrent = 0;
            let changerCurrent = 0;

            let cart = [];

            const paymentInputs = document.querySelectorAll('.payment-option');

            function updatePaymentHighlight() {
                document.querySelectorAll('.payment-card').forEach(card => {
                    card.classList.remove('border-success', 'border-primary', 'bg-light');
                });

                paymentInputs.forEach(input => {
                    if (input.checked) {
                        const card = input.nextElementSibling;
                        card.classList.add(
                            input.value === 'cash' ? 'border-success' : 'border-success',
                            'bg-light'
                        );
                    }
                });
            }

            paymentInputs.forEach(input => {
                input.addEventListener('change', updatePaymentHighlight);
            });

            updatePaymentHighlight();

            function openModalPayment() {
                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cart is Empty!',
                        text: 'Please select at least one product before proceeding to payment.',
                        confirmButtonColor: '#198754', // Matches your success payment button
                        confirmButtonText: 'Got it'
                    });
                    return;
                }

                const modal = new bootstrap.Modal(document.getElementById('paymentMethod'));
                calculateChange();
                modal.show();

            }

            async function processPayment() {
                if (cart.length === 0) {
                    return;
                }


                const selectMethod = document.querySelector('input[name=payment_method]:checked') || 'cash';
                const paymentMethod = selectMethod ? selectMethod.value : 'cash';
                const customerName = document.getElementById('customer_name').value;

                if ((changerCurrent > 0 || changerCurrent == "0") && paymentMethod === 'cash') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Go back if you dont have money!',
                        text: 'Atleast have some penny you broke.',
                        confirmButtonColor: '#198754', // Matches your success payment button
                        confirmButtonText: 'Got it'
                    });
                    return;
                }


                try {
                    const response = await fetch("{{ route('order.store') }}", {
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
                            payment_method: paymentMethod,
                            customer_name: customerName,
                            change_amount: Math.abs(changerCurrent)
                        })
                    })

                    const result = await response.json();

                    if (result.payment_method === "midtrans") {
                        window.snap.pay(result.snap_token, {
                            onSuccess: function(res) {
                                console.log(res);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Transaksi telah selesai.',
                                    confirmButtonColor: '#198754',
                                    confirmButtonText: 'Lanjut'
                                }).then(() => {
                                    midtransNotificationHandler(res.order_id, res.transaction_status,
                                        res.fraud_status, 'midtrans');
                                }).then(() => {
                                    // Step 2: Ask if Admin wants to print the receipt
                                    Swal.fire({
                                        icon: 'question',
                                        title: 'Cetak Struk?',
                                        text: 'Apakah Anda ingin mencetak struk pembayaran ini?',
                                        showCancelButton: true,
                                        confirmButtonColor: '#198754',
                                        cancelButtonColor: '#6c757d',
                                        confirmButtonText: '<i class="bi bi-printer"></i> Cetak',
                                        cancelButtonText: 'Tutup'
                                    }).then((printResult) => {
                                        if (printResult.isConfirmed) {
                                            // Populate print template data, then trigger print dialog
                                            renderReceiptData(res, result);

                                            setTimeout(() => {
                                                window.print();
                                            }, 100);

                                        }

                                        // Step 3: Clear cart after decision
                                        cart = [];
                                        displayCart();
                                        //location.reload();
                                    });
                                });
                            },
                            onPending: function(res) {
                                console.log(res);
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Menunggu Pembayaran',
                                    text: 'Silakan selesaikan pembayaran Anda sesuai instruksi.',
                                    confirmButtonColor: '#ffc107',
                                    confirmButtonText: 'Paham'
                                }).then(() => {
                                    midtransNotificationHandler(res.order_id, res.transaction_status,
                                        res.fraud_status, 'midtrans');
                                    location.reload();
                                });
                            },
                            onError: function(res) {
                                console.log(res);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text: 'Terjadi kesalahan saat memproses transaksi Anda.',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Coba Lagi'
                                });
                                midtransNotificationHandler(res.order_id, res.transaction_status, res
                                    .fraud_status, 'midtrans');
                            },
                            onClose: function(res) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Transaksi Dibatalkan',
                                    text: 'Anda menutup halaman pembayaran sebelum transaksi selesai.',
                                    confirmButtonColor: '#0d6efd',
                                    confirmButtonText: 'OK'
                                });
                                midtransNotificationHandler(res.order_id, res.transaction_status, res
                                    .fraud_status, 'midtrans');
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Transaksi telah selesai.',
                            confirmButtonColor: '#198754',
                            confirmButtonText: 'Lanjut'
                        }).then(() => {
                            // Step 2: Ask if Admin wants to print the receipt
                            Swal.fire({
                                icon: 'question',
                                title: 'Cetak Struk?',
                                text: 'Apakah Anda ingin mencetak struk pembayaran ini?',
                                showCancelButton: true,
                                confirmButtonColor: '#198754',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '<i class="bi bi-printer"></i> Cetak',
                                cancelButtonText: 'Tutup'
                            }).then((printResult) => {
                                if (printResult.isConfirmed) {
                                    // Populate print template data, then trigger print dialog
                                    renderReceiptData('cash', result);
                                    setTimeout(() => {
                                        window.print();
                                    }, 100);
                                }

                                // Step 3: Clear cart after decision
                                cart = [];
                                displayCart();
                                midtransNotificationHandler(result.order_id, null, null, 'cash');
                                // location.reload();
                            });
                        });
                        console.log(paymentMethod);
                        console.log(customerName);
                    }

                    //location.reload();
                } catch (error) {
                    console.log(error);
                    alert('gagal memproses transaksi ' + error.message);
                }
            }

            // function midtransNotificationHandler(order_id, transaction_status, fraud_status) {

            //     fetch('{{ route('midtrans.notification') }}', {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'Accept': 'application/json',
            //             'X-CSRF-TOKEN': document
            //                 .querySelector('meta[name="csrf-token"]')
            //                 .getAttribute('content')
            //         },
            //         body: JSON.stringify({
            //             order_id: order_id,
            //             transaction_status: transaction_status,
            //             fraud_status: fraud_status
            //         })
            //     });
            // }

            async function midtransNotificationHandler(
                order_id,
                transaction_status,
                fraud_status,
                payment_method
            ) {

                console.log("Sending notification:", {
                    order_id,
                    transaction_status,
                    fraud_status,
                    payment_method
                });

                const response = await fetch('{{ route('midtrans.notification') }}', {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },

                    body: JSON.stringify({
                        order_id: order_id,
                        transaction_status: transaction_status,
                        fraud_status: fraud_status,
                        payment_method: payment_method
                    })
                });

                console.log("HTTP status:", response.status);

                const data = await response.json();

                console.log("Laravel response:", data);

                return data;
            }

            function renderReceiptData(midtransResult, result) {

                document.getElementById('receipt-order-id').innerText = midtransResult.order_id || result.order_id;
                document.getElementById('receipt-order-code').innerText = midtransResult.order_code || result.order_code;
                document.getElementById('receipt-date').innerText = new Date().toLocaleString('id-ID');

                const tableBody = document.getElementById('receipt-items-table');
                tableBody.innerHTML = '';

                cart.forEach(item => {
                    const itemRow = `
                        <tr>
                            <td style="padding: 2px 0;" colspan="2">${item.name}</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 4px; padding-left: 10px;">${item.qty} x ${item.price.toLocaleString('id-ID')}</td>
                            <td style="text-align: right; padding-bottom: 4px;">Rp ${(item.qty * item.price).toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += itemRow;
                });

                document.getElementById('receipt-subtotal').innerText = document.getElementById('subtotal').innerText;
                document.getElementById('receipt-tax').innerText = document.getElementById('tax').innerText;
                document.getElementById('receipt-total').innerText = document.getElementById('total').innerText;
                document.getElementById('payment-method-success').innerHTML =
                    `<strong>Metode:</strong> ${midtransResult.payment_type || result.payment_method}`;
            }

            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
            });

            // Format value when focus leaves the input
            changePaid.addEventListener('blur', (e) => {
                // 1. Strip non-numeric characters except decimal points
                const cleanValue = e.target.value.replace(/[^0-9.]/g, '');

                // 2. Parse as float before formatting
                const numericValue = parseFloat(cleanValue);

                // 3. Format if it's a valid number
                if (!isNaN(numericValue)) {
                    e.target.value = formatter.format(numericValue);
                } else {
                    e.target.value = '';
                }
            });

            // Strip currency formatting when user re-enters the field to edit
            changePaid.addEventListener('focus', (e) => {
                e.target.value = e.target.value.replace(/[^0-9.]/g, '');
            });

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
                displayCart();

            }

            const cartItems = document.getElementById('cartItems');

            function displayCart() {

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


            function updateCart() {

                cartCount.innerText = `${cart.length}`;

                let subTotalCount = 0;
                const taxes = tax.dataset.percent / 100;

                cart.forEach((item, index) => {
                    subTotalCount += item.price * item.qty;
                });
                tax.innerText = `Rp. ${rupiahFormat(subTotalCount * taxes)}`;
                subTotal.innerText = `Rp. ${rupiahFormat(subTotalCount)}`;
                total.textContent = `Rp. ${rupiahFormat(subTotalCount * taxes + subTotalCount)}`;
                totalCurrent = subTotalCount * taxes + subTotalCount;
                changePaid.innerText = "Kurang : Rp. " + rupiahFormat(totalCurrent);
                totalPaid.innerText = "Harga : " + total.textContent;


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

            function calculateChange() {
                const changer = totalCurrent - parseFloat(cashPaid.value);
                changerCurrent = isNaN(changer) ? 0 : changer;
                if (cashPaid.value == "") {
                    changePaid.innerText = "Kurang : Rp. " + rupiahFormat(totalCurrent);
                    changePaid.classList.remove("bg-success");
                    changePaid.classList.add("bg-danger");
                    return;
                }
                if (changer < 0) {
                    changePaid.innerText = "Kembalian : Rp. " + rupiahFormat(Math.abs(changer)) + " + piring";
                    changePaid.classList.remove("bg-danger");
                    changePaid.classList.add("bg-success");
                    return;
                }
                changePaid.innerText = "Kurang : Rp. " + rupiahFormat(changer);
                changePaid.classList.remove("bg-success");
                changePaid.classList.add("bg-danger");
            }

            const cashBody = document.getElementById('cashPaymentBody');


            function displayCashPayment() {
                cashBody.style.display = "";
            }

            function undisplayCashPayment() {
                cashBody.style.display = "none";
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

    </html>
