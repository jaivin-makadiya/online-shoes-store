<?php
session_start();
include '../config.php';

// Initialize message variable
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email !== '' && $password !== '') {
        $stmt = $mysqli->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();

            if ($password === $row['password']) {
                $_SESSION['admin'] = $row;
                // Also set admin_id to match your other scripts
                $_SESSION['admin_id'] = $row['id']; 
                header("Location: dashboard.php");
                exit;
            } else {
                $msg = "Invalid password.";
            }
        } else {
            $msg = "No admin found with that email.";
        }
    } else {
        $msg = "Please fill in both fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Online Shoes Store</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            
            /* Premium Dark Mode Background */
            background-image: url('https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: rgba(15, 15, 15, 0.85);
            background-blend-mode: overlay;
            color: #f8f9fa; 
            
            /* Center the login box vertically */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        /* Sleek Glassmorphism Container */
        .login-container {
            background: rgba(15, 15, 15, 0.75); 
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 420px;
        }

        .brand-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            text-align: center;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Dark Themed Input Fields */
        .form-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* High Contrast Login Button */
        .btn-primary-custom {
            background-color: #fff;
            color: #000;
            border: none;
            font-weight: 700;
            border-radius: 12px;
            padding: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary-custom:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        /* Custom Alert Styling */
        .custom-alert {
            background-color: rgba(220, 53, 69, 0.8) !important;
            backdrop-filter: blur(5px);
            border: none;
            color: #fff;
            text-align: center;
            border-radius: 10px;
            font-size: 0.9rem;
            padding: 10px;
            margin-bottom: 25px;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
        }
        
        .back-link {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
            display: inline-block;
            margin-top: 20px;
        }
        
        .back-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="text-center mb-2">
        <i class="bi bi-shield-lock text-light fs-1 opacity-75"></i>
    </div>
    <h2 class="brand-title">ShoeStore</h2>
    <p class="card-subtitle">Admin Portal</p>

    <?php if ($msg): ?>
        <div class="alert custom-alert">
            <i class="bi bi-exclamation-triangle me-1"></i> <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-4">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter admin email" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100">
            Secure Login <i class="bi bi-arrow-right-short ms-1"></i>
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="../index.php" class="back-link"><i class="bi bi-arrow-left-short"></i> Back to Storefront</a>
    </div>

    <div class="text-center mt-4">
        <small class="footer-text">© <?php echo date("Y"); ?> ShoeStore Admin Panel</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>