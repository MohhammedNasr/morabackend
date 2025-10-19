@extends('layouts.landing')

@section('content')
    <!-- Hero Banner Section -->
    <section class="hero-banner text-white py-5" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Welcome to Mora</h1>
                    <p class="lead">Your trusted partner in business solutions</p>
                    <a href="{{ route('store.register') }}" class="btn btn-light btn-lg">Get Started</a>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('images/hero-banner.png') }}" alt="Hero Banner" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="who-we-are py-5" id="about">
        <div class="container">
            <h2 class="text-center mb-4">Who We Are</h2>
            <div class="row">
                <div class="col-md-6">
                    <p class="lead">Mora is a leading platform that connects businesses with trusted suppliers and stores.
                        We provide a seamless experience for all your business needs.</p>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('images/logo.png') }}" alt="About Us" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Suppliers Section -->
    <section class="featured-suppliers bg-light py-5" id="suppliers">
        <div class="container">
            <h2 class="text-center mb-4">Featured Suppliers</h2>
            <div class="row">
                @foreach ($featuredSuppliers as $supplier)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $supplier->logo }}" class="card-img-top" alt="{{ $supplier->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $supplier->name }}</h5>
                                <p class="card-text">{{ $supplier->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Stores Section -->
    <section class="featured-stores py-5" id="stores">
        <div class="container">
            <h2 class="text-center mb-4">Featured Stores</h2>
            <div class="row">
                @foreach ($featuredStores as $store)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $store->logo }}" class="card-img-top" alt="{{ $store->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $store->name }}</h5>
                                <p class="card-text">{{ $store->testimonial }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="contact-us py-5" id="contact">
        <div class="container">
            <h2 class="text-center mb-4">Contact Us</h2>
            <div class="row">
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your Email">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="contact-info">
                        <h4>Contact Information</h4>
                        <p><i class="fas fa-map-marker-alt"></i> Address: Your Company Address</p>
                        <p><i class="fas fa-phone"></i> Phone: +1234567890</p>
                        <p><i class="fas fa-envelope"></i> Email: info@mora.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
