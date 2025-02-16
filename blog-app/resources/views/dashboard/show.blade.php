@extends('layouts.main')

@section('content')
<div class="container">
    <!-- Judul -->
    <h1 class="text-center fw-bold text-primary">{{ $post->title }}</h1>
    
    <!-- Thumbnail -->
    @if($post->thumbnail)
        <div class="text-center mt-3">
            <img src="{{ asset($post->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded shadow-lg w-75">
        </div>
    @endif

    <!-- Info -->
    <p class="text-muted mt-3 text-center">
        <small><strong>Kategori:</strong> {{ optional($post->category)->name }}</small> | 
        <small><strong>Penulis:</strong> {{ optional($post->user)->name }}</small>
    </p>

    <!-- Konten -->
    <div class="mt-4 p-4 border rounded bg-light shadow-sm">
        {!! $post->content !!}
        <div class="text-center mt-3">
            <div class="d-flex align-items-center justify-content-center gap-2">
                <button id="like-btn" 
                        class="btn like-button btn-outline-danger rounded-circle d-flex align-items-center justify-content-center shadow"
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ $post->isLikedByUser() ? 'true' : 'false' }}">
                    <i class="fas fa-heart" id="like-icon" style="color: {{ $post->isLikedByUser() ? 'red' : 'gray' }};"></i>
                </button>
                <span id="likes-count" class="text-muted fs-5">{{ $post->likes->count() }} Likes</span>
            </div>
        </div>
    </div>

    <!-- Tombol Bagikan -->
    <div class="mt-4 text-center">
        <h5 class="fw-bold">Bagikan ke:</h5>
        <div class="d-flex justify-content-center gap-3">
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
            target="_blank" class="btn btn-success btn-lg rounded-circle d-flex align-items-center justify-content-center shadow"
            style="width: 55px; height: 55px;">
                <i class="fab fa-whatsapp fs-3"></i>
            </a>

            <a href="https://social-plugins.line.me/lineit/share?url={{ urlencode(url()->current()) }}" 
            target="_blank" class="btn btn-success btn-lg rounded-circle d-flex align-items-center justify-content-center shadow"
            style="width: 55px; height: 55px;">
                <i class="fab fa-line fs-3"></i>
            </a>

            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
            target="_blank" class="btn btn-primary btn-lg rounded-circle d-flex align-items-center justify-content-center shadow"
            style="width: 55px; height: 55px;">
                <i class="fab fa-facebook fs-3"></i>
            </a>

            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
            target="_blank" class="btn btn-dark btn-lg rounded-circle d-flex align-items-center justify-content-center shadow"
            style="width: 55px; height: 55px;">
                <i class="fab fs-3">X</i>
            </a>
        </div>
    </div>
    
    <!-- Tombol Kembali -->
    <div class="text-center mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Komentar -->
    <div class="mt-5">
        <h3 class="fw-bold">Komentar</h3>

        @auth
            <div class="card p-3 shadow-sm">
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control rounded shadow-sm" rows="3" placeholder="Tulis komentar..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fas fa-paper-plane"></i> Kirim Komentar
                    </button>
                </form>
            </div>
        @else
            <div class="alert alert-info mt-3 text-center">
                <strong><a href="{{ route('login') }}" class="text-decoration-none text-dark"><u>Login</u></a></strong> untuk memberi komentar.
            </div>
        @endauth

        <!-- Daftar Komentar -->
        <ul class="list-group mt-4">
            @forelse($post->comments->where('parent_id', null) as $comment)
                <li class="list-group-item rounded shadow-sm mb-2">
                    <div class="d-flex align-items-start">
                        <img src="{{ $comment->user->profile && $comment->user->profile->avatar ? asset('avatars/' . $comment->user->profile->avatar) : asset('/default-avatar.png') }}" 
                        alt="Profile Picture" class="rounded-circle me-2 border" width="45" height="45">                   
                        <div>
                            <strong class="d-block">{{ $comment->user->name }}</strong> 
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <p class="mt-2">{{ $comment->content }}</p>
                    
                    @auth
                        <button class="btn btn-sm btn-link reply-btn text-primary" data-id="{{ $comment->id }}">
                            <i class="fas fa-reply"></i> Balas
                        </button>
        
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="reply-form d-none mt-2" id="reply-form-{{ $comment->id }}">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="form-group">
                                <textarea name="content" class="form-control rounded shadow-sm" rows="2" placeholder="Tulis balasan..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-paper-plane"></i> Kirim Balasan
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary mt-2 cancel-reply">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </form>
                    @endauth

                    <!-- Balasan -->
                    @if($comment->replies->count())
                        <ul class="list-group mt-2 ps-3">
                            @foreach($comment->replies as $reply)
                                <li class="list-group-item bg-light rounded shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <img src="{{ $reply->user->profile && $reply->user->profile->avatar ? asset('avatars/' . $reply->user->profile->avatar) : asset('/default-avatar.png') }}" 
                                            alt="Profile Picture" class="rounded-circle me-2 border" width="40" height="40">
                                        <div>
                                            <strong class="d-block">{{ $reply->user->name }}</strong> 
                                            <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <p class="mt-2">{{ $reply->content }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @empty
                <li class="list-group-item text-center text-muted">Belum ada komentar.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
@section('header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <style>
        .like-button {
            width: 50px;
            height: 50px;
            transition: transform 0.2s ease;
        }

        .like-button:hover {
            transform: scale(1.1);
        }

        .like-animation {
            animation: likePulse 0.3s ease-in-out;
        }

        @keyframes likePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

    </style>
@endsection
@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const replyForms = document.querySelectorAll('.reply-form');

            document.querySelectorAll('.reply-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const commentId = this.getAttribute('data-id');

                    // Hide all reply forms first
                    replyForms.forEach(form => form.classList.add('d-none'));

                    // Show the corresponding reply form
                    document.getElementById('reply-form-' + commentId).classList.remove('d-none');
                });
            });

            document.querySelectorAll('.cancel-reply').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('.reply-form').classList.add('d-none');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const likeBtn = document.getElementById('like-btn');
            const likeIcon = document.getElementById('like-icon');
            const likesCount = document.getElementById('likes-count');
            
            likeBtn.addEventListener('click', function (event) {
                event.preventDefault();
                const postId = likeBtn.getAttribute('data-post-id');
                
                fetch(`/post/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    likeBtn.setAttribute('data-liked', data.liked ? 'true' : 'false');
                    likeIcon.style.color = data.liked ? 'red' : 'gray';
                    likesCount.textContent = `${data.likes_count} Likes`;
                    
                    // Tambahkan animasi pulse pada ikon like
                    likeIcon.classList.add('like-animation');
                    setTimeout(() => likeIcon.classList.remove('like-animation'), 300);
                    
                    Swal.fire({
                        title: data.liked ? 'Liked!' : 'Unliked!',
                        icon: data.liked ? 'success' : 'info',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
    
@endsection
