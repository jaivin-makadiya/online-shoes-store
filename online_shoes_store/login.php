<?php include 'header.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
  $email = trim($_POST['email']);
  $pass = $_POST['password'];
  $stmt = $mysqli->prepare('SELECT id,password,name FROM users WHERE email=?');
  $stmt->bind_param('s',$email);
  $stmt->execute();
  $res = $stmt->get_result();
  if($u = $res->fetch_assoc()){
    if(password_verify($pass,$u['password'])){
      $_SESSION['user_id']=$u['id'];
      $_SESSION['user_name']=$u['name'];
      header('Location: index.php'); exit;
    } else $err='Invalid credentials.';
  } else $err='Invalid credentials.';
}
?>

<style>
  /* Center the card vertically */
  .login-container {
    min-height: calc(100vh - 160px); 
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  /* Sleek Glassmorphism Card */
  .login-card {
    width: 100%;
    max-width: 420px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(15, 15, 15, 0.7); /* Deep dark transparency */
    backdrop-filter: blur(15px); /* Glass effect */
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    color: #fff;
  }

  .login-card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
    padding: 30px 20px 20px;
  }

  /* Dark Themed Input Fields */
  .login-card .form-control {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 12px;
    padding: 12px 15px;
    transition: all 0.3s ease;
  }

  .login-card .form-control:focus {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    color: #fff;
  }

  .login-card .form-control::placeholder {
    color: rgba(255, 255, 255, 0.3);
  }

  /* High Contrast Login Button */
  .btn-login {
    width: 100%;
    border-radius: 12px;
    padding: 12px;
    font-weight: 700;
    background-color: #fff;
    color: #000;
    border: none;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
  }

  .btn-login:hover {
    background-color: #e0e0e0;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
  }

  /* Links Styling */
  .text-link {
    color: #fff;
    text-decoration: underline;
    text-decoration-color: rgba(255, 255, 255, 0.3);
    transition: 0.3s;
  }

  .text-link:hover {
    color: #fff;
    text-decoration-color: #fff;
  }
</style>

<div class="login-container">
  <div class="card login-card">
    <div class="card-header border-0 pb-0">
      <h3 class="fw-bold mb-2">👟 Welcome Back</h3>
      <p class="text-light opacity-75 small mb-0">Sign in to continue to your account</p>
    </div>

    <div class="card-body p-4 mt-2">
      <?php if(isset($err)): ?>
        <div class="alert alert-danger bg-danger text-white border-0 text-center mb-4" style="background-color: rgba(220, 53, 69, 0.8) !important; backdrop-filter: blur(5px);">
          <?php echo htmlspecialchars($err); ?>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-4">
          <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-login mt-2">Login</button>

        <div class="text-center mt-4">
          <p class="mb-0 text-light opacity-75 small">Don't have an account? <br>
            <a href="register.php" class="text-link fw-bold mt-1 d-inline-block">Register here</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>