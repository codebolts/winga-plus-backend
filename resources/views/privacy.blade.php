<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Privacy Policy - Wingaplus</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        body { font-family: 'Inter', sans-serif; }
        .text-teal { color: #14b8a6 !important; }
        .bg-teal { background-color: #14b8a6 !important; }
        .btn-teal { background-color: #14b8a6; border-color: #14b8a6; }
        .btn-teal:hover { background-color: #0f766e; border-color: #0f766e; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow sticky-top">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand fw-bold text-teal fs-4">Wingaplus</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a href="#categories" class="nav-link">Categories</a></li>
                    <li class="nav-item"><a href="#products" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                        <a href="#" class="btn btn-teal text-white">Dashboard</a>
                    @else
                        <a href="#" class="btn btn-outline-teal me-2">Sign In</a>
                        <a href="#" class="btn btn-teal text-white">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Privacy Policy Content -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <h1 class="display-4 fw-bold text-center text-teal mb-5">Privacy Policy</h1>
                            <p class="text-muted text-center mb-4">Last updated: {{ date('F j, Y') }}</p>

                            <h2 class="h3 fw-bold text-dark mb-3">1. Information We Collect</h2>
                            <p class="mb-4">We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>

                            <h2 class="h3 fw-bold text-dark mb-3">2. How We Use Your Information</h2>
                            <p class="mb-4">We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>

                            <h2 class="h3 fw-bold text-dark mb-3">3. Information Sharing</h2>
                            <p class="mb-4">We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>

                            <h2 class="h3 fw-bold text-dark mb-3">4. Data Security</h2>
                            <p class="mb-4">We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                            <h2 class="h3 fw-bold text-dark mb-3">5. Your Rights</h2>
                            <p class="mb-4">You have the right to access, update, or delete your personal information. Contact us if you wish to exercise these rights.</p>

                            <h2 class="h3 fw-bold text-dark mb-3">6. Contact Us</h2>
                            <p class="mb-4">If you have any questions about this Privacy Policy, please contact us at privacy@wingaplus.com</p>

                            <div class="text-center mt-5">
                                <a href="{{ route('home') }}" class="btn btn-teal text-white px-4 py-2">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <h4 class="fw-bold mb-3">Wingaplus</h4>
                    <p class="text-muted">Your ultimate shopping destination in Tanzania.</p>
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <h5 class="fw-semibold mb-3">Quick Links</h5>
                    <ul class="list-unstyled text-muted">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li><a href="{{ route('terms') }}" class="text-decoration-none text-muted">Terms & Conditions</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-decoration-none text-muted">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <p>&copy; 2024 Wingaplus. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
