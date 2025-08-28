<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CVHRDC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle with Popper (required for modal, dropdown, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-VkW1ibzE0sm7iV8YwnTZb8SnHqGpB1gQnNHH2jl+vOpNv4QYAGbhvX7MCi6e/hfB" crossorigin="anonymous">
    </script>

    <!-- Include CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/5c14b0052b.js" crossorigin="anonymous"></script>

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{ asset('css/sweet.css') }}">

    <!-- Custom styles for enhanced modals -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        .modal-xl {
            max-width: 1140px;
        }
        
        .card.border-0.bg-light {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        
        .card.border-0.bg-light:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .badge.bg-primary {
            background-color: #007bff !important;
        }
        
        .badge.bg-info {
            background-color: #17a2b8 !important;
        }
        
        .badge.bg-secondary-subtle {
            background-color: #e9ecef !important;
            color: #495057 !important;
        }
        
        .text-primary {
            color: #007bff !important;
        }
        
        .modal-header .btn-close-white {
            filter: brightness(0) invert(1);
        }
        
        .cursor-pointer {
            cursor: pointer;
        }
        
        .cursor-pointer:hover {
            background-color: #f8f9fa !important;
            border-color: #007bff !important;
        }
        
        .modal-xl .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .modal-xl .modal-header {
            border-radius: 15px 15px 0 0;
            border-bottom: none;
        }
        
        .modal-xl .modal-footer {
            border-radius: 0 0 15px 15px;
            border-top: none;
        }
        
        .badge {
            font-size: 0.875em;
            padding: 0.5em 0.75em;
        }
        
        .bg-white.rounded.border {
            transition: all 0.3s ease;
        }
        
        .bg-white.rounded.border:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Custom Search and Sort Controls Styling */
        #searchSortControls {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #dee2e6;
        }
        
        #searchInput {
            border-radius: 0 0.375rem 0.375rem 0;
            border-left: none;
            transition: all 0.3s ease;
        }
        
        #searchInput:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }
        
        .input-group-text {
            border-radius: 0.375rem 0 0 0 0.375rem;
            border-right: none;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        
        #sortSelect {
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        #sortSelect:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }
        
        #clearFilters {
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        
        #clearFilters:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        #resultsInfo {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e9ecef;
        }
        
        /* Enhanced list item styling */
        .output-item {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .output-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        
        .output-item.highlight {
            background-color: #fff3cd;
            border-color: #ffc107;
            animation: highlightPulse 0.6s ease-in-out;
        }
        
        @keyframes highlightPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        /* Search result highlighting */
        .search-highlight {
            background-color: #fff3cd;
            padding: 0.1em 0.2em;
            border-radius: 0.2em;
            font-weight: 600;
        }
        
        /* Loading state */
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Enhanced search input styling */
        .input-group:hover .input-group-text {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }
        
        .input-group:hover #searchInput {
            border-color: #adb5bd;
        }
        
        /* Smooth transitions for all interactive elements */
        .form-control, .form-select, .btn {
            transition: all 0.2s ease;
        }
        
        /* Focus states for better accessibility */
        .form-control:focus, .form-select:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }
        
        /* Mobile responsiveness using Bootstrap utilities */
        @media (max-width: 576px) {
            #searchSortControls {
                padding: 0.75rem;
            }
            
            .output-item {
                padding: 0.75rem !important;
            }
            
            .output-item .fw-semibold {
                font-size: 0.95rem;
            }
            
            .output-item .small {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 768px) {
            #searchSortControls .row {
                gap: 0.75rem;
            }
            
            .output-item .d-flex {
                gap: 0.5rem;
            }
            
            .badge {
                font-size: 0.75rem;
                padding: 0.25em 0.5em;
            }
        }
        
        @media (max-width: 992px) {
            #searchSortControls {
                margin-bottom: 1rem;
            }
        }
        
        /* Mobile compact view */
        .mobile-compact-view .output-item {
            padding: 0.5rem !important;
            margin-bottom: 0.25rem;
        }
        
        .mobile-compact-view .output-item .fw-semibold {
            font-size: 0.9rem;
            line-height: 1.2;
        }
        
        .mobile-compact-view .output-item .small {
            font-size: 0.75rem;
            line-height: 1.1;
        }
        
        .mobile-compact-view .output-item .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
        }
        
        /* Touch-friendly interactions for mobile */
        @media (hover: none) and (pointer: coarse) {
            .output-item {
                min-height: 60px;
            }
            
            .btn, .form-control, .form-select {
                min-height: 44px;
            }
            
            .input-group-text {
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        
        /* Table view styling */
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
            white-space: nowrap;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .cursor-pointer {
            cursor: pointer;
        }
        
        /* Responsive table improvements */
        @media (max-width: 1200px) {
            .table-responsive {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 992px) {
            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>

    @vite(['resources/css/app.css','resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.Users.navigation')

        {{-- <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
    </div>
    </header>
    @endif --}}

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
    </div>
    @php
    $footer = \App\Models\Footer::first();
    @endphp
    @include('layouts.Users.footer', ['footer' => $footer])


    <!-- Bootstrap Bundle with Popper (required for modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- SweetALert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>
