<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @section('head')

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.2.1/dataTables.bootstrap5.min.css"
            integrity="sha512-pVSTZJo4Kj/eLMUG1w+itkGx+scwF00G5dMb02FjgU9WwF7F/cpZvu1Bf1ojA3iAf8y94cltGnuPV9vwv3CgZw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
            integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

    @show

    <style>
        .text-pink {
            color: #ec4899;
        }
        .btn-pink {
            background-color: #ec4899;
            border-color: #ec4899;
            color: white;
        }
        .btn-pink:hover {
            background-color: #db2777;
            border-color: #db2777;
            color: white;
        }
        .btn-outline-pink {
            color: #ec4899;
            border-color: #ec4899;
        }
        .btn-outline-pink:hover {
            background-color: #ec4899;
            border-color: #ec4899;
            color: white;
        }
        .border-pink {
            border-color: #ec4899 !important;
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 10px;

        }
        .shade-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid #fff;
            box-shadow: 0 0 0 1px #ddd;
            transition: all 0.2s ease-in-out;
        }
        .shade-swatch.active {
            box-shadow: 0 0 0 2px #ec4899;
            transform: scale(1.15);
        }
        .thumb-select:hover {
            border-color: #ec4899;
            opacity: 0.8;
        }
        /* Reset Bootstrap Defaults to GLOW Theme */
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d; /* Muted grey for ALL inactive tabs */
            transition: all 0.2s ease;
        }
        /* Only the active tab gets the pink treatment */
        .nav-tabs .nav-link.active {
            color: #ec4899 !important;
            border-bottom: 3px solid #ec4899 !important;
            background: none !important;
            font-weight: bold;
        }
        .nav-tabs .nav-link:hover {
            color: #ec4899;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #ec4899 !important;
        border-color: #ec4899 !important;
        color: white !important;
        border-radius: 5px;
        }
        
        table.dataTable thead th {
            border-bottom: 2px solid #f9a8d4 !important; /* Light pink header border */
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        /* Subtle pink glow on row hover */
        .table-hover tbody tr:hover {
            background-color: #fff1f2 !important; 
        }
        .group:hover .border-bottom-hover {
            text-decoration: underline;
            color: #ec4899 !important;
        }
        .group:hover .bg-pink {
            background-color: #be185d !important; /* Slightly darker pink on hover */
        }
        .border-pink-focus:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 0.25rem rgba(236, 72, 153, 0.1);
        }
        .form-check-input:checked {
            background-color: #ec4899;
            border-color: #ec4899;
        }
  
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    @include('layouts.header')

    <div class="container mt-3">
        @include('layouts.flash-messages') {{-- Place it here --}}
    </div>

    <div class="flex-grow-1">
        @yield('body')
    </div>

    @include('layouts.footer')
    
    @stack('scripts')
</body>

</html>