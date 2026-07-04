<?php include 'header.php'; ?>

<style>
  /* Center the card vertically */
  .auth-container {
    min-height: calc(100vh - 160px); 
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  /* Sleek Glassmorphism Card */
  .auth-card {
    width: 100%;
    max-width: 480px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(15, 15, 15, 0.7); /* Deep dark transparency */
    backdrop-filter: blur(15px); /* Glass effect */
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    color: #fff;
  }

  .auth-card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
    padding: 30px 20px 20px;
  }

  /* Dark Themed Input Fields */
  .auth-card .form-control {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 12px;
    padding: 12px 15px;
    transition: all 0.3s ease;
  }

  .auth-card .form-control:focus {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    color: #fff;
  }

  .auth-card .form-control::placeholder {
    color: rgba(255, 255, 255, 0.3);
  }

  /* High Contrast Action Button */
  .btn-auth {
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

  .btn-auth:hover {
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

  /* Alert Styling */
  .custom-alert {
    backdrop-filter: blur(5px);
    border: none;
    color: #fff;
  }
</style>

<div class="auth-container">
  <div class="card auth-card">
    <div class="card-header border-0 pb-0">
      <h3 class="fw-bold mb-2">👟 Create Account</h3>
      <p class="text-light opacity-75 small mb-0">Join us to start shopping for premium footwear</p>
    </div>

    <div class="card-body p-4 mt-2">
      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = $_POST['password'];

        if ($name == '' || $email == '' || $pass == '') {
          $err = '⚠️ All fields are required.';
        } else {
          $h = password_hash($pass, PASSWORD_DEFAULT);
          $stmt = $mysqli->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
          $stmt->bind_param('sss', $name, $email, $h);
          if ($stmt->execute()) {
            echo '<div class="alert custom-alert text-center rounded-3 mb-4" style="background-color: rgba(25, 135, 84, 0.8) !important;">
                    ✅ Registration successful.<br>
                    <a href="login.php" class="text-white fw-bold text-decoration-underline mt-2 d-inline-block">Login now</a>
                  </div>';
          } else {
            $err = '⚠️ Email might already be registered.';
          }
        }
      }

      if (isset($err)) {
        echo '<div class="alert custom-alert text-center rounded-3 mb-4" style="background-color: rgba(220, 53, 69, 0.8) !important;">' 
             . htmlspecialchars($err) . 
             '</div>';
      }
      ?>

      <form method="post">
        <div class="mb-4">
          <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Full Name</label>
          <input class="form-control" name="name" placeholder="Enter your full name" required>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Email Address</label>
          <input class="form-control" name="email" type="email" placeholder="Enter your email" required>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Password</label>
          <input class="form-control" name="password" type="password" placeholder="Create a password" required>
        </div>

        <button class="btn btn-auth mt-2">Register</button>

        <div class="text-center mt-4">
          <p class="mb-0 text-light opacity-75 small">Already have an account? <br>
            <a href="login.php" class="text-link fw-bold mt-1 d-inline-block">Login here</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>