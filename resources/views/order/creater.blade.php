<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Simulated Laravel CSRF Token -->
    <meta name="csrf-token" content="mock-csrf-token-1234567890">
    <title>Kopi PPKD Jakarta Pusat - POS & Midtrans Payment</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-coffee: #6f4e37;
            --brand-coffee-dark: #4a3324;
        }

        body {
            background-color: #f5f6f8;
            font-family: 'Plus Jakarta Sans', Arial, Helvetica, sans-serif;
            color: #334155;
        }

        .mono-font {
            font-family: 'Space Mono', monospace;
        }

        .product-item {
            cursor: pointer;
        }

        .product-card {
            border: none;
            border-radius: 15px;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .product-image {
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            overflow: hidden;
        }

        .product-image img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .price {
            color: var(--brand-coffee);
            font-weight: bold;
        }

        .cart-box {
            position: sticky;
            top: 20px;
            border-radius: 16px;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .total-price {
            font-size: 25px;
            font-weight: bold;
            color: var(--brand-coffee);
        }

        .payment-btn {
            border-radius: 10px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Printable Receipt Card Styling */
        .receipt-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #4a3324 0%, #6f4e37 100%);
            color: #ffffff;
            padding: 2rem;
            text-align: center;
        }

        .paid-stamp {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            border: 3px solid #16a34a;
            color: #16a34a;
            font-weight: 800;
            font-size: 1.25rem;
            text-transform: uppercase;
            padding: 0.25rem 0.85rem;
            border-radius: 8px;
            transform: rotate(-8deg);
            letter-spacing: 2px;
            user-select: none;
            opacity: 0.95;
            z-index: 10;
        }

        .pending-stamp {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            border: 3px solid #d97706;
            color: #d97706;
            font-weight: 800;
            font-size: 1.1rem;
            text-transform: uppercase;
            padding: 0.25rem 0.85rem;
            border-radius: 8px;
            transform: rotate(-5deg);
            letter-spacing: 1.5px;
            user-select: none;
        }

        .receipt-body {
            padding: 2rem;
        }

        .receipt-divider {
            border-top: 2px dashed #cbd5e1;
            margin: 1.5rem 0;
        }

        /* Midtrans Simulation Top Toolbar */
        .sim-bar {
            background: #e2e8f0;
            border-bottom: 1px solid #cbd5e1;
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
        }

        .toast-container {
            z-index: 1095;
        }
    </style>
</head>

<body>

    <div class="sim-bar text-center">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="fw-semibold text-dark">
                <i class="bi bi-sliders me-1 text-primary"></i> Midtrans Snap Callback Simulator:
            </span>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-success" onclick="simulateCallback('onSuccess')">
                    <i class="bi bi-check-circle me-1"></i> Trigger onSuccess (Receipt)
                </button>
                <button type="button" class="btn btn-warning text-dark" onclick="simulateCallback('onPending')">
                    <i class="bi bi-clock-history me-1"></i> Trigger onPending
                </button>
                <button type="button" class="btn btn-danger" onclick="simulateCallback('onError')">
                    <i class="bi bi-x-circle me-1"></i> Trigger onError
                </button>
                <button type="button" class="btn btn-secondary" onclick="simulateCallback('onClose')">
                    <i class="bi bi-door-closed me-1"></i> Trigger onClose
                </button>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

    <div class="modal fade" id="paymentMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="paymentMethodLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="paymentMethodLabel">Konfirmasi Pembayaran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" value="Budi Santoso">
                    </div>
                    <div class="row mb-3" style="display:none" id="cashPaymentBody">
                        <div class="col-md-12 mb-2">
                            <strong class="bg-success p-2 text-white rounded d-block" id='total-paid'>
                                Harga : Rp.0
                            </strong>
                        </div>
                        <div class="row only-cash d-block align-items-center mt-3">
                            <div class="col-md-12 mb-2">
                                <label for="cash-paid" class="form-label" id="label-paid">
                                    Pembayaran Cash :
                                </label>
                                <input type="number" name="cash_paid" step="any" min="0" id="cash-paid" class="form-control" oninput="calculateChange()">
                            </div>
                            <div class="col-md-12">
                                <strong class="bg-danger p-2 text-white rounded d-block" id="change-paid">Kurang : Rp.0</strong>
                            </div>
                        </div>
                    </div>
                    <h5 class="mb-3 fw-bold">Pilih Metode Pembayaran</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="cash-option" class="w-100 cursor-pointer" onclick="displayCashPayment()">
                                <input type="radio" name="payment_method" value="cash" class="d-none payment-option" id="cash-option" checked>
                                <div class="card p-3 shadow-sm border payment-card text-center h-100 border-success bg-light">
                                    <h4 class="text-success fw-bold">
                                        <i class="bi bi-cash-stack"> Cash</i>
                                        <p class="text-muted small mt-2">Bayar langsung di kasir secara tunai</p>
                                    </h4>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="midtrans-option" class="w-100 cursor-pointer" onclick="undisplayCashPayment()">
                                <input type="radio" name="payment_method" value="midtrans" class="d-none payment-option" id="midtrans-option">
                                <div class="card p-3 shadow-sm border payment-card text-center h-100">
                                    <h4 class="text-success fw-bold">
                                        <i class="bi bi-qr-code-scan"> Midtrans</i>
                                        <p class="text-muted small mt-2">Bayar online via QRIS / E-Wallet</p>
                                    </h4>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="processPayment()">Pay Now!</button>
                </div>
            </div>
        </div>
    </div>

    <div id="posView" class="container-fluid">
        <main class="col-lg-12 p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Point Of Sales</h3>
                    <p class="text-muted">POS - Toko Kopi PPKD Jakarta Pusat</p>
                </div>
                <button class="btn btn-dark" onclick="emptyCart()"><i class="bi bi-trash me-1"></i> Empty Cart</button>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded-circle text-primary">
                                <i class="bi bi-cart-check" style="font-size: 1.8rem"></i>
                            </div>
                            <div>
                                <small class="text-muted">Today's Transaction</small>
                                <h4 class="mb-0 fw-bold">10</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded-circle text-success">
                                <i class="bi bi-currency-dollar" style="font-size: 1.8rem"></i>
                            </div>
                            <div>
                                <small class="text-muted">Today's Sales</small>
                                <h4 class="mb-0 fw-bold">Rp. 10.000.000,-</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded-circle text-warning">
                                <i class="bi bi-box-seam" style="font-size: 1.8rem"></i>
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
                        <div class="card-body p-4">
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-7">
                                    <h5 class="fw-bold mb-0">Select Product</h5>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="searchProduct" class="form-control" placeholder="Search Product..." onkeyup="searchProduct()">
                                </div>
                            </div>
                            <div class="mb-4">
                                <button class="btn btn-dark btn-sm me-1 category-btn" onclick="filterCategory('all', this)">Semua</button>
                                <button class="btn btn-outline-dark btn-sm me-1 category-btn" onclick="filterCategory(1, this)">Kopi</button>
                                <button class="btn btn-outline-dark btn-sm me-1 category-btn" onclick="filterCategory(2, this)">Non-Kopi</button>
                                <button class="btn btn-outline-dark btn-sm me-1 category-btn" onclick="filterCategory(3, this)">Makanan</button>
                            </div>
                            
                            <!-- Product List Grid -->
                            <div class="row g-3" id="productList">
                                <div class="col-md-4 col-sm-6 product-item" data-category="1" onclick="addToCart(1, this)" data-id="1" data-name="Kopi Susu PPKD" data-price="18000">
                                    <div class="card product-card shadow-sm h-100">
                                        <div class="product-image">
                                            <img src="https://placehold.co/400x300/6f4e37/ffffff?text=Kopi+Susu" alt="Kopi Susu PPKD">
                                        </div>
                                        <div class="card-body">
                                            <span class="badge bg-light text-dark mb-2">Best Seller</span>
                                            <h6 class="fw-bold mb-1">Kopi Susu PPKD</h6>
                                            <span class="price">Rp. 18.000</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 product-item" data-category="1" onclick="addToCart(2, this)" data-id="2" data-name="Espresso Double" data-price="15000">
                                    <div class="card product-card shadow-sm h-100">
                                        <div class="product-image">
                                            <img src="https://placehold.co/400x300/4a3324/ffffff?text=Espresso" alt="Espresso Double">
                                        </div>
                                        <div class="card-body">
                                            <span class="badge bg-light text-dark mb-2">Strong</span>
                                            <h6 class="fw-bold mb-1">Espresso Double</h6>
                                            <span class="price">Rp. 15.000</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 product-item" data-category="2" onclick="addToCart(3, this)" data-id="3" data-name="Matcha Latte" data-price="22000">
                                    <div class="card product-card shadow-sm h-100">
                                        <div class="product-image">
                                            <img src="https://placehold.co/400x300/16a34a/ffffff?text=Matcha+Latte" alt="Matcha Latte">
                                        </div>
                                        <div class="card-body">
                                            <span class="badge bg-light text-dark mb-2">Non-Coffee</span>
                                            <h6 class="fw-bold mb-1">Matcha Latte</h6>
                                            <span class="price">Rp. 22.000</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 product-item" data-category="3" onclick="addToCart(4, this)" data-id="4" data-name="Butter Croissant" data-price="20000">
                                    <div class="card product-card shadow-sm h-100">
                                        <div class="product-image">
                                            <img src="https://placehold.co/400x300/d97706/ffffff?text=Croissant" alt="Butter Croissant">
                                        </div>
                                        <div class="card-body">
                                            <span class="badge bg-light text-dark mb-2">Bakery</span>
                                            <h6 class="fw-bold mb-1">Butter Croissant</h6>
                                            <span class="price">Rp. 20.000</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 product-item" data-category="1" onclick="addToCart(5, this)" data-id="5" data-name="Americano Ice" data-price="16000">
                                    <div class="card product-card shadow-sm h-100">
                                        <div class="product-image">
                                            <img src="https://placehold.co/400x300/334155/ffffff?text=Americano" alt="Americano Ice">
                                        </div>
                                        <div class="card-body">
                                            <span class="badge bg-light text-dark mb-2">Fresh</span>
                                            <h6 class="fw-bold mb-1">Americano Ice</h6>
                                            <span class="price">Rp. 16.000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow cart-box p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-cart me-2"></i> Cart
                            </h5>
                            <span class="badge bg-dark" id="cartCount">0</span>
                        </div>
                        <div class="mb-3" id="cartItems">
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-cart4 fs-1 d-block mb-2"></i>
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
                        <div class="d-flex justify-content-between mb-3 pt-2 border-top">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="total-price" id="total">Rp. 0</span>
                        </div>
                        <button class="btn btn-success w-100 py-3 fw-bold" onclick="openModalPayment()">
                            <i class="bi bi-credit-card me-1"></i> Payment
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="successView" class="container py-5 d-none">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="alert alert-success d-flex align-items-center rounded-4 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-2 me-3 text-success"></i>
                    <div>
                        <h5 class="alert-heading fw-bold mb-0">Pembayaran Berhasil!</h5>
                        <p class="mb-0 small">Transaksi Anda telah dikonfirmasi dan ditandai sebagai <strong class="text-success text-uppercase">PAID</strong>.</p>
                    </div>
                </div>

                <div class="receipt-card">
                    <div class="paid-stamp">PAID</div>
                    
                    <div class="receipt-header">
                        <i class="bi bi-cup-hot-fill fs-1 mb-2"></i>
                        <h4 class="fw-bold mb-1">KOPI PPKD JAKARTA PUSAT</h4>
                        <p class="small text-white-50 mb-0">Order Ref: <span id="receiptOrderRef" class="mono-font text-white">TRX-001</span></p>
                    </div>

                    <div class="receipt-body">
                        <div class="row g-3 small mb-3">
                            <div class="col-6">
                                <span class="text-secondary d-block">BUYER NAME</span>
                                <strong id="receiptBuyerName" class="fs-6 text-dark">Budi Santoso</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-secondary d-block">DATE & TIME</span>
                                <strong id="receiptDate" class="mono-font text-dark">2026-08-30 21:30</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary d-block">PAYMENT METHOD</span>
                                <span id="receiptPaymentType" class="badge bg-success text-uppercase">Midtrans (QRIS/VA)</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-secondary d-block">STATUS</span>
                                <span class="badge bg-success">SETTLEMENT</span>
                            </div>
                        </div>

                        <div class="receipt-divider"></div>

                        <h6 class="fw-bold mb-3 text-secondary small text-uppercase">Items Purchased</h6>
                        <div id="receiptItemsContainer">
                            <!-- Injected dynamically -->
                        </div>

                        <div class="receipt-divider"></div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary">Subtotal</span>
                            <span id="receiptSubtotal" class="mono-font fw-semibold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary">Pajak (11%)</span>
                            <span id="receiptTax" class="mono-font fw-semibold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center fs-4 fw-bold text-dark pt-2 border-top">
                            <span>Total Paid</span>
                            <span id="receiptTotal" class="mono-font text-success">Rp 0</span>
                        </div>
                    </div>

                    <div class="bg-light p-3 text-center border-top d-flex gap-2">
                        <button class="btn btn-outline-dark w-100 fw-semibold" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> Cetak Struk
                        </button>
                        <button class="btn btn-dark w-100 fw-semibold" onclick="resetToCart()">
                            <i class="bi bi-arrow-left me-1"></i> Transaksi Baru
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="pendingView" class="container py-5 d-none">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 position-relative">
                    <div class="pending-stamp">PENDING</div>
                    <div class="text-center mb-4">
                        <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex p-3 mb-2">
                            <i class="bi bi-hourglass-split fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Menunggu Pembayaran</h4>
                        <p class="text-secondary small">Selesaikan pembayaran sebelum batas waktu berakhir.</p>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-secondary">Virtual Account / Pay Code:</span>
                            <span class="badge bg-secondary">BCA Virtual Account</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 px-3 border rounded">
                            <span id="pendingVaCode" class="mono-font fs-5 fw-bold text-dark">88012 9481 0293 841</span>
                            <button class="btn btn-sm btn-outline-primary" onclick="copyVaCode()">
                                <i class="bi bi-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between small text-secondary mb-4">
                        <span>Customer Name: <strong id="pendingCustomerName" class="text-dark">Budi Santoso</strong></span>
                        <span>Total Tagihan: <strong id="pendingTotalAmount" class="text-dark mono-font">Rp 0</strong></span>
                    </div>

                    <h6 class="fw-bold mb-2 small text-uppercase text-secondary">Cara Pembayaran:</h6>
                    <ol class="small text-secondary ps-3 mb-4">
                        <li class="mb-1">Buka aplikasi mobile banking atau ATM terdekat.</li>
                        <li class="mb-1">Pilih menu <strong>Transfer &gt; Virtual Account</strong>.</li>
                        <li class="mb-1">Masukkan Nomor Virtual Account yang tertera di atas.</li>
                        <li class="mb-1">Periksa total tagihan dan selesaikan transaksi.</li>
                    </ol>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success w-100 fw-semibold" onclick="simulateCallback('onSuccess')">
                            <i class="bi bi-arrow-clockwise me-1"></i> Cek Status Pembayaran
                        </button>
                        <button class="btn btn-outline-secondary w-100 fw-semibold" onclick="resetToCart()">
                            Kembali ke Kasir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentErrorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Pembayaran Gagal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-2 fw-semibold text-dark" id="errorMessageText">Transaksi tidak dapat diproses saat ini.</p>
                    <p class="small text-secondary mb-0">Penyebab umum: Saldo tidak mencukupi, batas waktu sesi habis, atau dibatalkan oleh bank penerbit.</p>
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="retryPayment()">Coba Lagi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="mock-client-key"></script>

    <script>
        let cart = [];

        const cartCount = document.getElementById('cartCount');
        const subTotal = document.getElementById('subtotal');
        const tax = document.getElementById('tax');
        const total = document.getElementById('total');
        const totalPaid = document.getElementById('total-paid');
        const cashPaid = document.getElementById('cash-paid');
        const changePaid = document.getElementById('change-paid');
        const labelPaid = document.getElementById('label-paid');
        let totalCurrent = 0;
        let lastOrderRef = "TRX-1001";
        let errorModalInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            errorModalInstance = new bootstrap.Modal(document.getElementById('paymentErrorModal'));
            updatePaymentHighlight();
        });

        const paymentInputs = document.querySelectorAll('.payment-option');

        function updatePaymentHighlight() {
            document.querySelectorAll('.payment-card').forEach(card => {
                card.classList.remove('border-success', 'border-primary', 'bg-light');
            });

            paymentInputs.forEach(input => {
                if (input.checked) {
                    const card = input.nextElementSibling;
                    card.classList.add('border-success', 'bg-light');
                }
            });
        }

        paymentInputs.forEach(input => {
            input.addEventListener('change', updatePaymentHighlight);
        });

        function openModalPayment() {
            if (cart.length === 0) {
                showToast('warning', 'Keranjang Kosong', 'Silakan pilih produk terlebih dahulu.');
                return;
            }
            const modal = new bootstrap.Modal(document.getElementById('paymentMethod'));
            calculateChange();
            modal.show();
        }

        // Integrated Payment Processing Logic
        async function processPayment() {
            if (cart.length === 0) {
                showToast('warning', 'Keranjang Kosong', 'Keranjang belanja masih kosong.');
                return;
            }

            const selectMethod = document.querySelector('input[name=payment_method]:checked');
            const paymentMethod = selectMethod ? selectMethod.value : 'cash';
            const customerName = document.getElementById('customer_name').value || 'Pelanggan';
            
            lastOrderRef = "TRX-" + Math.floor(100000 + Math.random() * 900000);

            try {
                // Mocking the backend API call response (Laravel order.store)
                const mockResult = {
                    payment_method: paymentMethod,
                    customer_name: customerName,
                    snap_token: "mock-snap-token-xyz",
                    order_id: lastOrderRef,
                    gross_amount: totalCurrent
                };

                if (mockResult.payment_method === "midtrans") {
                    if (window.snap && typeof window.snap.pay === 'function' && window.snap.pay.name !== 'pay') {
                        // Live Midtrans Popup trigger
                        window.snap.pay(mockResult.snap_token, {
                            onSuccess: function(result) {
                                handleOnSuccess(result);
                            },
                            onPending: function(result) {
                                handleOnPending(result);
                            },
                            onError: function(result) {
                                handleOnError(result);
                            },
                            onClose: function() {
                                handleOnClose();
                            }
                        });
                    } else {
                        // Demo Simulator fallback
                        showToast('info', 'Midtrans Simulator Mode', 'Memproses callback sukses otomatis...');
                        handleOnSuccess({
                            order_id: mockResult.order_id,
                            payment_type: "QRIS / GoPay",
                            gross_amount: totalCurrent,
                            transaction_status: "settlement",
                            transaction_time: new Date().toISOString().replace('T', ' ').substring(0, 19)
                        });
                    }
                } else {
                    // Cash payment handler
                    showToast('success', 'Pembayaran Tunai', 'Transaksi cash berhasil dicatat.');
                    handleOnSuccess({
                        order_id: mockResult.order_id,
                        payment_type: "Tunai (Cash)",
                        gross_amount: totalCurrent,
                        transaction_status: "settlement",
                        transaction_time: new Date().toISOString().replace('T', ' ').substring(0, 19)
                    });
                }
            } catch (error) {
                console.error(error);
                handleOnError({ status_message: error.message });
            }
        }

        function handleOnSuccess(result) {
            console.log("Midtrans onSuccess:", result);
            renderReceipt(result);
            cart = [];
            displayCart();
            showView('successView');
            showToast("success", "Pembayaran Sukses!", "Transaksi telah berhasil diproses.");
        }

        function handleOnPending(result) {
            console.log("Midtrans onPending:", result);
            document.getElementById('pendingCustomerName').innerText = document.getElementById('customer_name').value || 'Budi Santoso';
            document.getElementById('pendingTotalAmount').innerText = rupiahFormat(totalCurrent);
            document.getElementById('pendingVaCode').innerText = "88012 " + Math.floor(1000 + Math.random() * 9000) + " " + Math.floor(1000 + Math.random() * 9000);

            showView('pendingView');
            showToast("warning", "Menunggu Pembayaran", "Silakan selesaikan pembayaran sesuai petunjuk.");
        }

        function handleOnError(result) {
            console.log("Midtrans onError:", result);
            document.getElementById('errorMessageText').innerText = result?.status_message || "Pembayaran gagal diproses. Silakan coba lagi atau gunakan metode lain.";
            errorModalInstance.show();
        }

        function handleOnClose() {
            console.log("Midtrans onClose");
            showToast("warning", "Pembayaran Dibatalkan", "Anda menutup pop-up sebelum menyelesaikan pembayaran.");
        }

        function renderReceipt(result) {
            const itemsContainer = document.getElementById('receiptItemsContainer');
            const customerName = document.getElementById('customer_name').value || 'Budi Santoso';
            
            let subtotalVal = 0;
            cart.forEach(item => subtotalVal += item.price * item.qty);
            if (subtotalVal === 0) subtotalVal = totalCurrent / 1.11;
            
            const taxVal = Math.round(subtotalVal * 0.11);
            const totalVal = subtotalVal + taxVal;

            document.getElementById('receiptBuyerName').innerText = customerName;
            document.getElementById('receiptOrderRef').innerText = result?.order_id || lastOrderRef;
            document.getElementById('receiptDate').innerText = result?.transaction_time || new Date().toLocaleString('id-ID');
            document.getElementById('receiptPaymentType').innerText = (result?.payment_type || "MIDTRANS").toUpperCase();

            if (cart.length > 0) {
                itemsContainer.innerHTML = cart.map(item => `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-semibold text-dark">${item.name}</span>
                            <small class="text-secondary d-block">${item.qty} x Rp. ${rupiahFormat(item.price)}</small>
                        </div>
                        <span class="mono-font fw-semibold">Rp. ${rupiahFormat(item.price * item.qty)}</span>
                    </div>
                `).join('');
            }

            document.getElementById('receiptSubtotal').innerText = "Rp. " + rupiahFormat(subtotalVal);
            document.getElementById('receiptTax').innerText = "Rp. " + rupiahFormat(taxVal);
            document.getElementById('receiptTotal').innerText = "Rp. " + rupiahFormat(totalVal);
        }

        // Simulator Action Trigger
        function simulateCallback(type) {
            if (cart.length === 0 && type === 'onSuccess') {
                // Add sample items for receipt demo
                cart = [
                    { id: 1, name: "Kopi Susu PPKD", price: 18000, qty: 2 },
                    { id: 4, name: "Butter Croissant", price: 20000, qty: 1 }
                ];
                updateCart();
            }

            const mockData = {
                order_id: "TRX-" + Math.floor(100000 + Math.random() * 900000),
                payment_type: "qris",
                gross_amount: totalCurrent || 56000,
                status_message: "Transaksi ditolak oleh penerbit kartu / bank."
            };

            if (type === 'onSuccess') handleOnSuccess(mockData);
            else if (type === 'onPending') handleOnPending(mockData);
            else if (type === 'onError') handleOnError(mockData);
            else if (type === 'onClose') handleOnClose();
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
            const productName = element.dataset.name;
            const productPrice = Number(element.dataset.price);

            const existingItem = cart.find((item) => Number(item.id) === Number(productId));
            if (existingItem) {
                existingItem.qty++;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    qty: 1
                });
            }
            displayCart();
        }

        function displayCart() {
            const cartItems = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItems.innerHTML = `<div class="text-center text-muted py-5">
                                <i class="bi bi-cart4 fs-1 d-block mb-2"></i>
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
                    <div class="d-flex align-items-center mt-2 gap-2">
                        <button class="btn btn-outline-danger quantity-btn" onclick="changeItem(${index}, -1)">-</button>
                        <span class="fw-bold px-1">${item.qty}</span>
                        <button class="btn btn-outline-success quantity-btn" onclick="changeItem(${index}, 1)">+</button>
                        <button class="btn btn-sm btn-outline-dark ms-auto" onclick="dumpItem(${index})">
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

            cart.forEach((item) => {
                subTotalCount += item.price * item.qty;
            });

            tax.innerText = `Rp. ${rupiahFormat(subTotalCount * taxes)}`;
            subTotal.innerText = `Rp. ${rupiahFormat(subTotalCount)}`;
            totalCurrent = subTotalCount * taxes + subTotalCount;
            total.textContent = `Rp. ${rupiahFormat(totalCurrent)}`;
            
            changePaid.innerText = "Kurang : Rp. " + rupiahFormat(totalCurrent);
            totalPaid.innerText = "Harga : " + total.textContent;
        }

        function calculateChange() {
            const cashInput = parseFloat(cashPaid.value) || 0;
            const changer = totalCurrent - cashInput;
            
            if (!cashPaid.value) {
                changePaid.innerText = "Kurang : Rp. " + rupiahFormat(totalCurrent);
                changePaid.classList.remove("bg-success");
                changePaid.classList.add("bg-danger");
                return;
            }

            if (changer <= 0) {
                changePaid.innerText = "Kembalian : Rp. " + rupiahFormat(Math.abs(changer));
                changePaid.classList.remove("bg-danger");
                changePaid.classList.add("bg-success");
            } else {
                changePaid.innerText = "Kurang : Rp. " + rupiahFormat(changer);
                changePaid.classList.remove("bg-success");
                changePaid.classList.add("bg-danger");
            }
        }

        function changeItem(index, change) {
            if (cart[index].qty === 1 && change === -1) {
                dumpItem(index);
                return;
            }
            cart[index].qty += change;
            displayCart();
        }

        function dumpItem(index) {
            cart.splice(index, 1);
            displayCart();
        }

        function emptyCart() {
            cart = [];
            displayCart();
        }

        function rupiahFormat(number) {
            return Number(number).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function searchProduct() {
            const searchValue = document.getElementById('searchProduct').value.toLowerCase().trim();
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

        function displayCashPayment() {
            document.getElementById('cashPaymentBody').style.display = "";
        }

        function undisplayCashPayment() {
            document.getElementById('cashPaymentBody').style.display = "none";
        }

        function showView(viewId) {
            document.getElementById('posView').classList.add('d-none');
            document.getElementById('successView').classList.add('d-none');
            document.getElementById('pendingView').classList.add('d-none');

            document.getElementById(viewId).classList.remove('d-none');
        }

        function resetToCart() {
            showView('posView');
        }

        function retryPayment() {
            showView('posView');
            openModalPayment();
        }

        function copyVaCode() {
            const code = document.getElementById('pendingVaCode').innerText;
            document.execCommand('copy');
            showToast("info", "Disalin", "Nomor Virtual Account telah disalin!");
        }

        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const bgClass = {
                success: 'bg-success text-white',
                warning: 'bg-warning text-dark',
                danger: 'bg-danger text-white',
                info: 'bg-primary text-white'
            }[type] || 'bg-dark text-white';

            const toastHTML = `
                <div id="${toastId}" class="toast align-items-center ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}</strong><br>
                            <small>${message}</small>
                        </div>
                        <button type="button" class="btn-close ${type === 'warning' ? '' : 'btn-close-white'} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }
    </script>
</body>
</html>