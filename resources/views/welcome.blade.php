<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <title>Login</title>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.login-button').addEventListener('click', function() {
        // Get the entered username and password
        var username = document.querySelector('.login-input').value;
        var password = document.querySelector('.login-password-input').value;

        // Check if the entered username and password are 'admin'
        if (username === 'admin' && password === 'admin') {
          // Redirect to the /admin page
          window.location.href = '/admin';
        } else {
          // Display an error message
          document.querySelector('#error').innerText = 'Invalid username or password';
        }
      });
    });
  </script>
</head>
<body>

  <div class="login-body"> 
    <div class="login-div">
      <div class="login-inner-div">
        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="logo">
        <p class="title-text">Speed Up Garage Inventory<br />and POS System</p>
        <div>
          <label for="uname" class="login-label">Username</label>
          <input type="text" placeholder="juan_delacruz@gmail.com" name="uname" class="login-input" required />
          <label for="psw" class="login-label">Password</label>
          <input type="password" placeholder="Password" name="psw" class="login-password-input" required />
          <button class="login-button">Login</button>
        </div>
        <div>
          <span class="login-error" id="error"></span>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
