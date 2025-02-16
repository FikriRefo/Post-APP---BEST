@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Tambah Post</h2>
        
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Konten</label>
                <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content') }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail</label>
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                
                <!-- Container Preview Gambar -->
                <div id="preview-container" class="d-none mt-3" style="position: relative; display: inline-block;">
                    <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 200px; display: block;">
                    <button type="button" id="remove-image" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border: none;">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Tombol Batal dan Simpan Bersebelahan -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/ui/trumbowyg.min.css">
@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/trumbowyg.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/plugins/justify/trumbowyg.justify.min.js"></script>

<script>
    $(document).ready(function() {
        $('#content').trumbowyg({
            btns: [
                ['formatting'],
                ['bold', 'italic', 'underline'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['removeformat'],
                ['fullscreen']
            ]
        });

        $('#thumbnail').change(function(event) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#preview-container').removeClass('d-none');
            }
            reader.readAsDataURL(event.target.files[0]);
        });

        $('#remove-image').click(function() {
            $('#thumbnail').val('');
            $('#preview-container').addClass('d-none');
            $('#image-preview').attr('src', '#');
        });
    });
</script>
@endsection

