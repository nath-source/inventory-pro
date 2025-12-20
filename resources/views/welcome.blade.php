<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory & Sales System</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">INVENTORY PRO</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="btn btn-light fw-bold w-100">Go to Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                                <a href="{{ route('login') }}" class="btn btn-outline-light w-100">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-light w-100">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="p-5 mb-4 bg-light rounded-3 shadow-sm border">
            <div class="container-fluid py-md-5">
                <h1 class="display-5 fw-bold text-primary">Inventory & Sales Management</h1>
                <p class="col-md-8 fs-4 text-muted">A comprehensive solution to manage stock, suppliers, and point-of-sale transactions with ease.</p>
                <hr class="my-4">
                
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-start">
                    @if (Route::has('login'))
                        @auth
                            <a class="btn btn-primary btn-lg px-4 gap-3" href="{{ url('/home') }}">Open Dashboard</a>
                        @else
                            <a class="btn btn-primary btn-lg px-4" href="{{ route('login') }}">Login to Start</a>
                            <a class="btn btn-outline-secondary btn-lg px-4" href="{{ route('register') }}">Create Account</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>

        <div class="row align-items-md-stretch mt-4">
            <div class="col-md-4 mb-3">
                <div class="h-100 p-4 bg-white border rounded-3 shadow-sm">
                    <h2 class="h4 text-dark">📦 Inventory Control</h2>
                    <p class="text-muted">Track product stock levels, set reorder alerts, and manage categories effortlessly.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="h-100 p-4 bg-white border rounded-3 shadow-sm">
                    <h2 class="h4 text-dark">🛒 Point of Sale (POS)</h2>
                    <p class="text-muted">Fast checkout process with barcode scanning and instant receipt generation.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="h-100 p-4 bg-white border rounded-3 shadow-sm">
                    <h2 class="h4 text-dark">📊 Analytics & Reports</h2>
                    <p class="text-muted">Get insights on daily sales, profit/loss, and best-selling products.</p>
                </div>
            </div>
        </div>

        <footer class="pt-3 mt-4 text-muted border-top text-center pb-4">
            &copy; {{ date('Y') }} Inventory System v1.0
        </footer>
    </div>

</body>
</html>