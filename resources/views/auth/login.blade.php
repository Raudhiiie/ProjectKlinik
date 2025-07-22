<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Pretty's Clinic</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logobulat.png') }}">

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Bootstrap & AdminLTE -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- Custom Login Style -->
    <link rel="stylesheet" href="{{ asset('dist/css/login.css') }}">
</head>

<body>

    <div class="login-box">
        <img src="{{ asset('dist/img/logobulat.png') }}" alt="Logo Pretty's Clinic">
        <h4 class="mb-4 font-weight-bold">Pretty’s Clinic</h4>

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            </div>

            <div class="form-group mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-pink btn-block">Login</button>
        </form>

        <p class="text-muted mt-4 mb-0" style="font-size: 0.9rem;">&copy; {{ date('Y') }} Pretty’s Clinic</p>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

</body>

</html>
