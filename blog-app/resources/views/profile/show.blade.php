@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <img src="{{ $profile && $profile->avatar ? asset('avatars/' . $profile->avatar) : asset('/default-avatar.png') }}" 
                         alt="Avatar" 
                         class="rounded-circle border" 
                         width="150" 
                         height="150">
                    
                    <h4 class="mt-3 fw-bold">{{ $profile->full_name ?? 'Nama tidak tersedia' }}</h4>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <p class="fw-medium">Jenis Kelamin: 
                        @switch(optional($profile)->gender)
                            @case('male')
                                <span class="badge bg-primary">Laki-laki</span>
                                @break
                            @case('female')
                                <span class="badge bg-danger">Perempuan</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Tidak diketahui</span>
                        @endswitch
                    </p>
                    
                    <!-- Button Edit Profile -->
                    <button type="button" class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Update Profil -->
    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="updateProfileModalLabel">Update Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Foto Profil</label>
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" id="avatar">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ ($profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ ($profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if($errors->any())
            var updateProfileModal = new bootstrap.Modal(document.getElementById('updateProfileModal'));
            updateProfileModal.show();
        @endif
    </script>
@endsection
