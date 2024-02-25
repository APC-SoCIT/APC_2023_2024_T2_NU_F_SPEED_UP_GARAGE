
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <title>Login</title>
  
</head>
<body>

  <div class="login-body"> 
    <div class="login-div">
      <div class="login-inner-div">
        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="logo">
        <p class="title-text">Speed Up Garage Inventory<br />and POS System</p>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}">
            @csrf
    
            <!-- Email Address -->
            <div>
                <label for="uname" class="login-label">Username</label>
                <x-text-input id="email" class="login-input" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <label for="psw" class="login-label">Password</label>
    
                <x-text-input id="password" class="login-password-input"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
    
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
                <x-primary-button class="login-button">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>

