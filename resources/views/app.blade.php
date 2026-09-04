<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        /* Reset & Base Style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --sidebar-width: 240px;
            --navbar-height: 60px;
            --bg-color: #f4f6f9;
            --sidebar-bg: #1e293b;
            --primary-color: #3b82f6;
            --text-dark: #334155;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #38bdf8;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.08);
            border-left: 4px solid var(--primary-color);
        }

        /* Main Container */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            height: var(--navbar-height);
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Main Content */
        .content {
            padding: 30px;
        }

        .page-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }


        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-pending {
            background: #fef9c3;
            color: #a16207;
        }

        /* Layout Responsive untuk HP */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
                /* Sembunyikan sidebar di layar kecil */
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-brand">{{ $settings['app_name'] ?? '' }}</div>
        <ul class="sidebar-menu">
            @if (auth()->user()->role_id == 3)
                <li><a href="{{ url('dashboard') }}" class="active">Dashboard</a></li>
                <li><a href="{{ url('product') }}">Products</a></li>
                <li><a href="{{ url('order') }}">Transaction Order</a></li>
            @elseif (auth()->user()->role_id == 2)
                <li><a href="{{ url('cashier/dashboard') }}" class="active">Dashboard</a></li>
                <li><a href="{{ url('order') }}">Transaction Order</a></li>
            @elseif (auth()->user()->role_id == 1)
                <li><a href="{{ url('admin/dashboard') }}" class="active">Dashboard</a></li>
                <li><a href="{{ url('role') }}">Roles</a></li>
                <li><a href="{{ url('menu') }}">Menu</a></li>
                <li><a href="{{ url('order') }}">Transaction Order</a></li>
                <li><a href="{{ url('product') }}">Products</a></li>
                <li><a href="{{ url('peserta') }}">Peserta</a></li>
                <li><a href="{{ url('category') }}">Category</a></li>
                <li><a href="{{ url('setting') }}">Setting</a></li>
            @endif
            <li><a href="{{ url('setting') }}"
                    onclick="event.preventDefault();document.getElementById('logout').submit()">Logout</a></li>
            <form action="{{ route('logout') }}" method="post" id="logout" class="d-none"></form>
        </ul>
    </aside>

    <!-- Main Area -->
    <div class="main-wrapper">

        <!-- Navbar -->
        <header class="navbar">
            <div><strong>{{ $settings['app_name'] ?? '' }} Dashboard</strong></div>
            <div class="user-profile">
                <span>{{ auth()->user()->name }}</span>
                <div class="avatar">A</div>
            </div>
        </header>

        <!-- Content -->
        <main class="content">

            <div class="row">
                <div class="col-lg-12">
                    <h1>{{ $title ?? '' }}</h1>
                    @yield('content')
                </div>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
