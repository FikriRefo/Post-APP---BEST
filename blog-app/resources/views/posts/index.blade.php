@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg rounded-lg border-0">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">📌 Post-App</h4>
                <div class="ms-auto">
                    <a href="{{ route('posts.create') }}" 
                        class="btn fw-bold shadow-sm d-flex align-items-center overflow-hidden position-relative add-post-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span class="btn-text ms-2" style="opacity: 0; transition: opacity 0.3s ease;">
                            Tambah Post
                        </span>
                    </a>
                </div>
            </div>
            </div>  
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>✅ Sukses!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="postTable" class="table table-hover table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Jumlah Like</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $post->title }}</strong></td>
                                    <td>{{ $post->category->name }}</td>
                                    <td>{{ $post->user->name }}</td>
                                    <td class="text-center">{{ $post->likes_count }}</td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex flex-column align-items-center">
                                            <input class="form-check-input toggle-publish" type="checkbox" id="togglePublish{{ $post->id }}" 
                                                   data-id="{{ $post->id }}" {{ $post->is_published ? 'checked' : '' }}>        
                                        </div>
                                        <small class="mt-1 fw-bold text-muted" id="publishStatus{{ $post->id }}">
                                            {{ $post->is_published ? '✅ Published' : '📌 Draft' }}
                                        </small>
                                    </td>                                                                                                                                                                               
                                    <td class="text-center">
                                        <a href="{{ route('posts.detail', $post->id) }}" class="btn btn-info btn-sm rounded-circle"><i class="fas fa-info-circle"></i></a>
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm rounded-circle"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle delete-button" data-title="{{ $post->title }}">
                                                <i class="fas fa-trash"></i>
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
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <style>
        /* Style untuk tombol "Tambah Post" */
        .add-post-btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 50px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            width: 50px;
            white-space: nowrap;
        }

        .add-post-btn:hover {
            width: 180px !important;
            background: linear-gradient(135deg, #0056b3, #003580);
        }

        .add-post-btn:hover .btn-text {
            opacity: 1 !important;
        }

       /* Menyesuaikan ukuran switch */
        .form-check-input {
            width: 2.5rem;
            height: 1.4rem;
            cursor: pointer;
        }

        /* Menyesuaikan teks status */
        #publishStatus {
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #146c43;
        }

        .form-check-input:focus {
            box-shadow: none;
        }
    </style>
@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#postTable').DataTable({
                responsive: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                pageLength: 10,
                language: {
                    lengthMenu: '<label>Tampilkan <select class="custom-select custom-select-sm form-control form-control-sm">' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="-1">Semua</option>' +
                        '</select> entri</label>',
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada entri tersedia",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    search: "🔍 Cari:",
                    paginate: {
                        first: "⏮ Awal",
                        last: "⏭ Akhir",
                        next: "⏩",
                        previous: "⏪"
                    }
                }
            });
        });

        // Toggle Publish Status dengan Slider
        $(document).on('change', '.toggle-publish', function () {
            let checkbox = $(this);
            let postId = checkbox.data('id');
            let statusText = $('#publishStatus' + postId);
            let isChecked = checkbox.is(':checked');

            $.ajax({
                url: `/posts/${postId}/publish`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        statusText.text(isChecked ? '✅ Published' : '📌 Draft');

                        Swal.fire({
                            title: '✅ Sukses!',
                            text: 'Status post diperbarui!',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: '❌ Error!',
                        text: 'Gagal mengubah status!',
                        icon: 'error'
                    });
                }
            });
        });

        // SweetAlert Konfirmasi Hapus
        $(document).on('click', '.delete-button', function (e) {
            e.preventDefault();
            
            let form = $(this).closest('form'); 
            let postTitle = $(this).data('title'); 

            Swal.fire({
                title: `🗑 Hapus Post?`,
                text: `Anda yakin ingin menghapus post "${postTitle}"? Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Auto close alert after 2 seconds
        setTimeout(function() {
            $(".alert-success").fadeOut("slow");
        }, 2000);
    </script>
@endsection
