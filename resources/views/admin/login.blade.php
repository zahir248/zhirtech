<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZhirTech | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
</head>
<body>

<div class="login-card">
  <div class="logo-container">
    <h4 class="fw-bold">ZhirTech</h4>
  </div>
  
  <form method="POST" action="{{ route('admin.login.submit') }}">
    @csrf
    
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" id="email" name="email" required 
             value="{{ old('email', 'admin@example.com') }}">
    </div>
    
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" required 
             value="Admin1234">
    </div>
    
    <div class="d-grid">
      <button type="submit" class="btn btn-primary btn-login">Login</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>