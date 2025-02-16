@extends('layouts.main')

@section('content')

<div class="container animate__animated animate__fadeIn">
    @if(session('success'))
        <div class="alert alert-success animate__animated animate__fadeInLeft">{{ session('success') }}</div>
    @endif
    <!-- Judul Dashboard -->
    <h1 class="my-4 text-center fw-bold text-primary">Dashboard Admin</h1>
    
    <!-- Statistik Kartu -->
    <div class="row g-4">
        <!-- Total Pengguna -->
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-lg rounded-3 card-hover">
                <div class="card-header">Total Pengguna</div>
                <div class="card-body text-center">
                    <h3 class="fw-bold">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
        <!-- Total Postingan -->
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-lg rounded-3 card-hover">
                <div class="card-header">Total Postingan</div>
                <div class="card-body text-center">
                    <h3 class="fw-bold">{{ $totalPosts }}</h3>
                </div>
            </div>
        </div>
        <!-- Total Kategori -->
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-lg rounded-3 card-hover">
                <div class="card-header">Total Kategori</div>
                <div class="card-body text-center">
                    <h3 class="fw-bold">{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 3 Post dengan Like Terbanyak -->
    <div class="container mt-5 animate__animated animate__fadeInUp">
        <h2 class="fw-bold text-primary mb-4 text-center">Top 3 Post dengan Like Terbanyak</h2>
        <div class="row g-4">
            @foreach($topPosts as $post)
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3 mb-4 card-hover">
                        @if($post->thumbnail)
                            <img src="{{ asset($post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                            <p class="card-text text-muted"><small>By {{ optional($post->user)->name }} | {{ optional($post->category)->name }}</small></p>
                            <p class="card-text"><strong>{{ $post->likes_count }} Likes</strong></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top 3 Post dengan Viewers Terbanyak -->
    <div class="container mt-5 animate__animated animate__fadeInUp">
        <h2 class="fw-bold text-danger mb-4 text-center">🔥 Top 3 Post dengan Viewers Terbanyak</h2>
        <div class="row g-4">
            @foreach($topViewedPosts as $post)
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3 mb-4 card-hover">
                        @if($post->thumbnail)
                            <img src="{{ asset($post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                            <p class="card-text text-muted"><small>By {{ optional($post->user)->name }} | {{ optional($post->category)->name }}</small></p>
                            <p class="card-text"><strong><i class="fas fa-eye"></i> {{ $post->views }} Views</strong></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Grafik & Tabel Postingan per Kategori -->
    <div class="card mt-5 shadow-lg rounded-3 border-0 animate__animated animate__fadeInUp">
        <div class="card-header bg-gradient text-dark fw-bold text-center" style="background: linear-gradient(135deg, #36D1DC, #5B86E5);">
            <h5>📊 Jumlah Postingan per Kategori</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Grafik -->
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <div style="height: 300px; width: 100%;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <!-- Tabel -->
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jumlah Postingan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($postsBycategories as $category)
                                    <tr>
                                        <td class="fw-semibold">{{ $category->name }}</td>
                                        <td class="text-primary fw-bold">{{ $category->posts_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kategori -->
<div class="container mt-5 animate__animated animate__fadeIn">
    <h2 class="mb-4 fw-bold text-primary">Daftar Kategori</h2>

    <div class="mb-3">
        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>

        <!-- Tabel Kategori -->
        <div class="table-responsive">
            <table id="categoryTable" class="table table-hover table-bordered animate__animated animate__fadeInUp">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning edit-button" 
                                    data-id="{{ $category->id }}" 
                                    data-name="{{ $category->name }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editCategoryModal">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('destroy.categories', $category->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-button" 
                                        data-id="{{ $category->id }}" 
                                        data-name="{{ $category->name }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade animate__animated animate__fadeInDown" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded-lg">
            <form action="{{ route('store.categories') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create-name">Nama Kategori</label>
                        <input type="text" class="form-control" name="name" id="create-name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade animate__animated animate__fadeInDown" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded-lg">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-category-id">
                    <div class="form-group">
                        <label for="edit-name">Nama Kategori</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
    <style>
        #categoryChart {
            max-width: 100%;
            max-height: 300px; /* Sesuaikan tinggi grafik */
            margin: auto;
        }

    </style>
@endsection

@section('footer')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Load Chart.js -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let ctx = document.getElementById('categoryChart').getContext('2d');

            // Ambil data dari PHP
            let labels = {!! json_encode($postsBycategories->pluck('name')) !!};
            let data = {!! json_encode($postsBycategories->pluck('posts_count')) !!};

            // Generate warna acak untuk setiap kategori
            function getRandomColors(count) {
                let colors = [];
                for (let i = 0; i < count; i++) {
                    let randomColor = `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.8)`;
                    colors.push(randomColor);
                }
                return colors;
            }

            let backgroundColors = getRandomColors(labels.length);

            let categoryChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Postingan',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.8', '1')), // Warna border lebih pekat
                        borderWidth: 1.5,
                        borderRadius: 8, // Buat ujung batang lebih rounded
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 2000,
                        easing: 'easeOutBounce'
                    },
                    plugins: {
                        legend: {
                            display: true // Sembunyikan legend agar lebih clean
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#333',
                                font: { weight: 'bold' }
                            }
                        },
                        x: {
                            ticks: {
                                color: '#555',
                                font: { weight: 'bold' }
                            }
                        }
                    }
                }
            });
        });

    </script>
    <script>
         $(document).ready(function () {
            $('#categoryTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-primary',
                        text: '<i class="fas fa-copy"></i> Copy'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-success',
                        text: '<i class="fas fa-file-csv"></i> CSV'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success',
                        text: '<i class="fas fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger',
                        text: '<i class="fas fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info',
                        text: '<i class="fas fa-print"></i> Print'
                    }
                ]
            });
        });
    </script>
    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.dataset.id;
                const categoryName = this.dataset.name;
    
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Kategori "${categoryName}" akan dihapus!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Temukan form yang sesuai dengan tombol yang diklik
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
    {{-- Edit --}}
    <script>
        $(document).ready(function() {
            // Handle Edit Button Click
            $('.edit-button').on('click', function() {
                let categoryId = $(this).data('id');
    
                $.ajax({
                    url: `/admin/categories/${categoryId}`,
                    type: 'GET',
                    success: function(response) {
                        $('#edit-category-id').val(response.id);
                        $('#edit-name').val(response.name);
                        $('#editCategoryForm').attr('action', `/admin/categories/${response.id}`);
                        $('#editCategoryModal').modal('show');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal mengambil data kategori.', 'error');
                    }
                });
            });
    
            // Handle Form Submission
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let actionUrl = form.attr('action');
                let formData = form.serialize();
    
                $.ajax({
                    url: actionUrl,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#editCategoryModal').modal('hide');
    
                        // SweetAlert Success Notification
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Kategori berhasil diperbarui.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload tabel setelah sukses
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Terjadi kesalahan! Coba lagi.', 'error');
                    }
                });
            });
        });
    </script>    
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                let successAlert = document.querySelector(".alert-success");
                if (successAlert) {
                    successAlert.classList.add("animate__fadeOut");
                    setTimeout(() => successAlert.remove(), 500);
                }
            }, 2000);
        });
    </script>
    
@endsection
