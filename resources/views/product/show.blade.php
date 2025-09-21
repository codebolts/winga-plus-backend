<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $product->name }} - Wingaplus</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .text-teal {
            color: #14b8a6 !important;
        }
        .bg-teal {
            background-color: #14b8a6 !important;
        }
        .btn-teal {
            background-color: #14b8a6;
            border-color: #14b8a6;
        }
        .btn-teal:hover {
            background-color: #0f766e;
            border-color: #0f766e;
        }
        .bg-teal-light {
            background-color: rgba(20, 184, 166, 0.1) !important;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow sticky-top">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand fw-bold text-teal fs-4">
                <i class="fa fa-wifi"></i>
                Winga+
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="#categories" class="nav-link">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a href="#products" class="nav-link">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link">Contact</a>
                    </li>
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

    <!-- Product Details Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            @if($product->images->isNotEmpty())
                                <div id="productCarousel" class="carousel slide">
                                    <div class="carousel-inner">
                                        @foreach($product->images as $index => $image)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ asset($image->image_path) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($product->images->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-secondary bg-opacity-25" style="height: 400px;">
                                    <svg class="text-secondary" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h1 class="card-title display-5 fw-bold text-dark mb-3">{{ $product->name }}</h1>

                            <div class="mb-3">
                                <span class="badge bg-teal text-white me-2">{{ $product->category->name }}</span>
                                @if($product->subcategory)
                                    <span class="badge bg-light text-dark">{{ $product->subcategory->name }}</span>
                                @endif
                            </div>

                            <p class="text-teal fw-bold display-6 mb-3">Tz {{ number_format($product->price, 2) }}</p>

                            <div class="mb-3">
                                <p class="text-muted mb-2">Stock: {{ $product->stock }} available</p>
                                <div class="d-flex align-items-center">
                                    <div class="text-warning me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="bi bi-star-fill" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">({{ $product->reviews->count() }} reviews)</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-semibold mb-2">Description</h5>
                                <p class="text-muted">{{ $product->description }}</p>
                            </div>

                            @if($product->custom_attributes)
                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-2">Specifications</h5>
                                    <div class="row">
                                        @foreach($product->custom_attributes as $key => $value)
                                            <div class="col-sm-6 mb-2">
                                                <strong class="text-dark">{{ ucfirst($key) }}:</strong>
                                                @if(is_array($value))
                                                    {{ implode(', ', $value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex gap-2 mb-4">
                                <button class="btn btn-teal text-white flex-fill">Add to Cart</button>
                                <button class="btn btn-outline-teal">Add to Wishlist</button>
                            </div>

                            <div class="border-top pt-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <svg class="text-muted" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $product->seller->name }}</p>
                                        <p class="mb-0 text-muted small">Seller</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            @if($product->reviews->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h4 class="mb-0">Customer Reviews ({{ $product->reviews->count() }})</h4>
                            </div>
                            <div class="card-body">
                                @foreach($product->reviews as $review)
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <strong>{{ $review->user->name }}</strong>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="bi bi-star-fill" width="14" height="14" viewBox="0 0 16 16">
                                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <h4 class="fw-bold mb-3">Wingaplus</h4>
                    <p class="text-muted">
                        Your ultimate shopping destination in Tanzania. Connecting buyers and sellers nationwide.
                    </p>
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <h5 class="fw-semibold mb-3">Quick Links</h5>
                    <ul class="list-unstyled text-muted">
                        <li><a href="#" class="text-decoration-none text-muted">About Us</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">How It Works</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Seller Center</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Contact Us</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <h5 class="fw-semibold mb-3">Customer Service</h5>
                    <ul class="list-unstyled text-muted">
                        <li><a href="#" class="text-decoration-none text-muted">Help Center</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Shipping Info</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Returns</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Size Guide</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="fw-semibold mb-3">Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted text-decoration-none">
                            <svg class="bi bi-twitter" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 0 3.067 2.281A6.655 6.655 0 0 0 0 13.865a9.316 9.316 0 0 0 5.034 1.475z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-muted text-decoration-none">
                            <svg class="bi bi-facebook" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-muted text-decoration-none">
                            <svg class="bi bi-instagram" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="text-center text-muted">
                <p>&copy; 2024 Wingaplus. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script>
        // Add any custom JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
