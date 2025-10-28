<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sliding Login/Register Form</title>

    {{-- External CSS --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="container" id="container">
    <!-- Login Form -->
    <div class="form-container login-container">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1>Login</h1>

            @if(session('status'))
                <div class="status-message">{{ session('status') }}</div>
            @endif

            <div class="input-group">
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder=" " required autofocus autocomplete="username">
                <label for="email">Email</label>
                <div class="error-message">{{ $errors->first('email') }}</div>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" placeholder=" " required autocomplete="current-password">
                <label for="password">Password</label>
                <div class="error-message">{{ $errors->first('password') }}</div>
            </div>

            <div class="remember-forgot">
                <div class="remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>
                <a id="forgotPasswordLink">Forgot password?</a>
            </div>

            <button type="submit">Log in</button>
        </form>
    </div>

    <!-- Register Form -->
    <div class="form-container register-container">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h1>Create Account</h1>

            <div class="input-group">
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder=" " required autofocus autocomplete="name" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
                <label for="name">Full Name</label>
                <div class="error-message">{{ $errors->first('name') }}</div>
            </div>

            <div class="input-group">
                <input type="number" id="age" name="age" value="{{ old('age') }}" placeholder=" " required min="0">
                <label for="age">Age</label>
                <div class="error-message">{{ $errors->first('age') }}</div>
            </div>

            <div class="input-group">
                <input type="email" id="email_reg" name="email" value="{{ old('email') }}" placeholder=" " required autocomplete="username">
                <label for="email_reg">Email</label>
                <div class="error-message">{{ $errors->first('email') }}</div>
            </div>

            <div class="input-group">
                <input type="password" id="password_reg" name="password" placeholder=" " required autocomplete="new-password">
                <label for="password_reg">Password</label>
                <div class="error-message">{{ $errors->first('password') }}</div>
            </div>

            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder=" " required autocomplete="new-password">
                <label for="password_confirmation">Confirm Password</label>
                <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>

    <!-- Toggle Panels -->
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome Back!</h1>
                <p>Insert your personal details to Login</p>
                <button class="toggle-button" id="login">Login</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello!</h1>
                <p>If you dont have any account click Register</p>
                <button class="toggle-button" id="register">Register</button>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reset Password</h2>
        <p>Enter your email address and we will send you a password reset link.</p>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder=" " required>
                <label>Email</label>
            </div>
            <button type="submit">Send Password Reset Link</button>
        </form>
    </div>
</div>

<script>
    // Toggle login/register
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    registerBtn.addEventListener('click', () => container.classList.add('active'));
    loginBtn.addEventListener('click', () => container.classList.remove('active'));

    // Forgot password modal
    const modal = document.getElementById("forgotPasswordModal");
    const forgotLink = document.getElementById("forgotPasswordLink");
    const closeBtn = document.querySelector(".modal .close");

    forgotLink.addEventListener("click", function(e){
        e.preventDefault();
        modal.classList.add("show");
    });

    closeBtn.addEventListener("click", function(){
        modal.classList.remove("show");
    });

    window.addEventListener("click", function(e){
        if(e.target == modal){
            modal.classList.remove("show");
        }
    });
</script>
</body>
</html>
