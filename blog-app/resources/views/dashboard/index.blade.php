@extends('layouts.main')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="text-center my-4">
            <h1 class="fw-bold text-orange stylish-title">
                <i class="fas fa-newspaper"></i> Dashboard Post-App
            </h1>
            <p class="text-muted">Jelajahi berbagai artikel menarik yang telah diposting!</p>
        </div>

        <!-- Notifikasi sukses -->
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <!-- Filter Form -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="category" class="form-control select2-filter-category">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="user" class="form-control select2-filter-user">
                        <option value=""></option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari ..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="sort_by" class="form-select">
                        <option value="">Urutkan</option>
                        <option value="title_asc" {{ request('sort_by') == 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                        <option value="title_desc" {{ request('sort_by') == 'title_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
                        <option value="date_newest" {{ request('sort_by') == 'date_newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="date_oldest" {{ request('sort_by') == 'date_oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-gradient">🔍 Filter</button>
            </div>
        </form>

        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow border-0 rounded-lg overflow-hidden position-relative">
                        @if($post->thumbnail)
                            <div class="position-relative">
                                <img src="{{ asset($post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}">
                                <div class="info-overlay p-2" style="position: absolute; top: 10px; left: 10px;"> 
                                    <span class="badge bg-primary">{{ $post->category->name }}</span>
                                    <span class="badge bg-dark"><i class="fas fa-user"></i> {{ $post->user->name }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold elegant-title">{{ $post->title }}</h5><br><br>
                            <!-- Menampilkan jumlah like dan share menggunakan withCount atau likes relation -->  
                            <p class="mb-2">
                                <i class="fas fa-heart text-danger"></i> 
                                {{ isset($post->likes_count) ? $post->likes_count : $post->likes->count() }} Likes
                                &nbsp; | &nbsp;
                                <i class="fas fa-eye text-primary"></i> 
                                {{ $post->views }} Views
                            </p>
                            <a href="{{ route('dashboard.show', $post) }}" class="btn btn-gradient w-100 fw-bold">
                                📖 Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        

        @if(method_exists($posts, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Select2 CSS --> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom style untuk select2 single selection */
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: #fff;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            color: #333;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        /* Styling untuk tombol clear pada Select2 */
        .select2-container--default .select2-selection__clear {
            color: #d9534f; /* Warna merah sesuai Bootstrap danger */
            font-size: 16px;
            margin-right: 4px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .select2-container--default .select2-selection__clear:hover {
            opacity: 1;
        }
    </style>
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .card-img-top {
            width: 100%;
            height: 200px; 
            object-fit: cover; 
            border-radius: 8px; 
        }

        /* Gaya untuk Tombol Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #1e90ff, #00c853); /* Biru ke Hijau */
            border: none;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
            border-radius: 8px;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #00c853, #1e90ff); /* Hijau ke Biru */
            transform: scale(1.05);
        }

        /* Gaya untuk Judul */
        .stylish-title {
            font-size: 36px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #1e90ff, #00c853);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Gaya untuk Badge */
        .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 12px;
        }

        .bg-blue-green {
            background: linear-gradient(135deg, #1e90ff, #00c853); /* Biru ke Hijau */
            color: white;
        }

        /* Efek hover pada kartu */
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 200, 83, 0.3); /* Efek hijau neon */
        }

    </style>
@endsection
@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS --> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {            
            // Initialize select2 pada dropdown filter            
            $('.select2-filter-category').select2({
                placeholder: 'Pilih Kategori',
                allowClear: true,
                width: '100%'
            });

            $('.select2-filter-user').select2({
                placeholder: 'Pilih Penulis',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection