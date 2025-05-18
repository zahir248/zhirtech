<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZhirTech</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/client/style.css') }}">
</head>
<body data-theme="light">

<!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <div class="navbar-content">
            <div class="brand">
                ZhirTech
            </div>
            <div class="nav-items">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i id="theme-icon" class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Digital Solutions That Scale</h1>
            <p>Build your digital presence with cutting-edge technology and modern design</p>
            <a href="#services" class="cta-button">
                <span>Explore Services</span>
            </a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services" id="services">
    <div class="container">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Choose the perfect solution for your digital needs</p>
        </div>
        <div class="services-grid">
            @foreach($services as $service)
                <div class="service-card" data-service-id="{{ $service->id }}">
                    <div class="service-icon">
                        <i class="fas {{ $service->icon }}"></i>
                    </div>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>
                    <div class="service-price">RM {{ $service->price }}</div>
                    <div class="price-note">{{ $service->note }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Booking Form Section -->
<section class="booking-section" id="booking">
    <div class="container">
        <div class="section-header">
            <h2>Let's Get Started</h2>
            <p>Fill out the form below and we'll get back to you within 24 hours</p>
        </div>
        <div class="form-container">
            <!-- Display success/error messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('payment.process') }}">
                @csrf
                <div class="form-group">
                    <label for="customer_name" class="form-label">
                        Full Name <span style="color: red">*</span>
                    </label>
                    <input type="text" name="customer_name" id="customer_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address <span style="color: red">*</span>
                    </label>
                    <input type="email" name="email" id="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">
                        Phone Number (WhatsApp) <span style="color: red">*</span>
                    </label>
                    <input type="tel" name="phone" id="phone" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="service" class="form-label">
                        Select Service <span style="color: red">*</span>
                    </label>
                    <select name="service" id="service" class="form-input form-select" required>
                        <option value="">Choose a service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} (RM {{ $service->price }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="submit-button">
                    <span>Proceed to Payment</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 ZhirTech. All rights reserved. Made with <i class="fas fa-heart" style="color: #ef4444;"></i></p>
    </div>
</footer>

<!-- Payment Status Modal -->
<div id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-modal">&times;</span>
            <h2 id="modalTitle">Payment Status</h2>
        </div>
        <div class="modal-body">
            <div id="modalIcon" class="modal-icon">
                <!-- Icon will be inserted here via JavaScript -->
            </div>
            <p id="modalMessage">Your payment has been processed.</p>
        </div>
        <div class="modal-footer">
            <button id="modalCloseButton" class="modal-button">Close</button>
        </div>
    </div>
</div>

<script>
    // Theme toggle functionality
    function toggleTheme() {
        const body = document.body;
        const themeIcon = document.getElementById('theme-icon');
        const currentTheme = body.getAttribute('data-theme');
        
        if (currentTheme === 'light') {
            body.setAttribute('data-theme', 'dark');
            themeIcon.className = 'fas fa-sun';
            localStorage.setItem('theme', 'dark');
        } else {
            body.setAttribute('data-theme', 'light');
            themeIcon.className = 'fas fa-moon';
            localStorage.setItem('theme', 'light');
        }
    }
    
    // Load saved theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        const body = document.body;
        const themeIcon = document.getElementById('theme-icon');
        
        body.setAttribute('data-theme', savedTheme);
        themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Add scroll effect to navbar
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        const currentTheme = document.body.getAttribute('data-theme');
        
        if (window.scrollY > 50) {
            if (currentTheme === 'dark') {
                navbar.style.background = 'rgba(15, 23, 42, 0.95)'; // Dark theme background
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)'; // Light theme background
            }
            navbar.style.backdropFilter = 'blur(20px)';
        } else {
            navbar.style.background = 'var(--bg-primary)';
            navbar.style.backdropFilter = 'blur(20px)';
        }
    });

    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we should show the modal
        @if(session('show_modal'))
            const modal = document.getElementById('statusModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalIcon = document.getElementById('modalIcon');
            const modalMessage = document.getElementById('modalMessage');
            const closeModal = document.querySelector('.close-modal');
            const modalCloseButton = document.getElementById('modalCloseButton');
            const body = document.body;
            
            // Set modal content based on session data
            const modalType = "{{ session('modal_type') }}";
            modalTitle.textContent = "{{ session('modal_title') }}";
            modalMessage.textContent = "{{ session('modal_message') }}";
            
            // Set the appropriate icon based on status type
            if (modalType === 'success') {
                modalIcon.innerHTML = '<i class="fas fa-check-circle modal-icon success"></i>';
            } else if (modalType === 'error') {
                modalIcon.innerHTML = '<i class="fas fa-times-circle modal-icon error"></i>';
            } else {
                modalIcon.innerHTML = '<i class="fas fa-info-circle modal-icon info"></i>';
            }
            
            // Store the current scroll position
            const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
            
            // Show the modal and prevent body scrolling
            modal.style.display = "flex";
            
            // Apply styles to prevent scrolling
            body.style.overflow = 'hidden';
            body.style.position = 'fixed';
            body.style.top = `-${scrollPosition}px`;
            body.style.width = '100%';
            
            // Close modal functionality
            function closeModalAndRefresh() {
                // Remove scroll lock
                body.style.overflow = '';
                body.style.position = '';
                body.style.top = '';
                body.style.width = '';
                
                // Restore scroll position
                window.scrollTo(0, scrollPosition);
                
                // Hide modal
                modal.style.display = "none";
                
                // Refresh the page but clear the URL parameters
                window.location.href = window.location.pathname;
            }
            
            closeModal.onclick = closeModalAndRefresh;
            modalCloseButton.onclick = closeModalAndRefresh;
            
            // Also close when clicking outside the modal
            window.onclick = function(event) {
                if (event.target === modal) {
                    closeModalAndRefresh();
                }
            }
        @endif
    });
    
</script>

</body>
</html>