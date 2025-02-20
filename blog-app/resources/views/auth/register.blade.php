<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome untuk ikon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .card-header {
      background-color: transparent;
      border-bottom: none;
    }
    .form-control {
      border-radius: 10px;
    }
    .btn-success {
      border-radius: 10px;
      background-color: #ff4b2b;
      border: none;
    }
    .btn-success:hover {
      background-color: #e63920;
    }
    a {
      color: #ff4b2b;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    /* Styling untuk tombol toggle password */
    .btn-toggle {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      background: transparent;
      border: none;
      cursor: pointer;
    }
    .btn-toggle:focus {
      outline: none;
      box-shadow: none;
    }
  </style>
</head>
<body>
  <div class="card p-4" style="width: 100%; max-width: 400px;">
    <div class="card-header text-center">
      <h2 class="fw-bold">Create Account</h2>
      <p class="text-muted mb-0">Join us today!</p>
    </div>
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" name="name" id="name" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <!-- Input group untuk password dengan tombol toggle -->
          <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" required>
            <button type="button" id="togglePassword" class="btn btn-toggle">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <!-- Input group untuk confirm password dengan tombol toggle -->
          <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            <button type="button" id="togglePasswordConfirmation" class="btn btn-toggle">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn btn-success w-100">Register</button>
      </form>
    </div>
    <div class="card-footer text-center">
      <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility for password field
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      const icon = this.querySelector('i');
      if (type === 'text') {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });

    // Toggle password visibility for confirm password field
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    togglePasswordConfirmation.addEventListener('click', function () {
      const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordConfirmationInput.setAttribute('type', type);
      const icon = this.querySelector('i');
      if (type === 'text') {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  </script>
</body>
</html>
