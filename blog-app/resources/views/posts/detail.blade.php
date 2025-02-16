@extends('layouts.main')

@section('content')
<div class="container">
    <h1 class="text-center fw-bold text-primary">{{ $post->title }}</h1>
    
    @if($post->thumbnail)
        <div class="text-center mt-3">
            <img src="{{ asset($post->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded shadow w-75">
        </div>
    @endif

    <p class="text-muted mt-3">
        <small><strong>Kategori:</strong> {{ optional($post->category)->name }}</small> |
        <small><strong>Penulis:</strong> {{ optional($post->user)->name }}</small>
    </p>

    <div class="mt-4">
        {!! $post->content !!}
    </div>

    <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left"></i> Kembali ke Tabel
    </a>

</div>
@endsection
