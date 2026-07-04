<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | ShoeStore</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            
            /* Center the dashboard vertically */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Sleek Glassmorphism Card */
        .admin-card {
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(15, 15, 15, 0.7); 
            backdrop-filter: blur(15px); 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            padding: 50px 40px;
            transition: all 0.3s ease;
            color: #fff;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .admin-icon {
            font-size: 65px;
            color: #fff;
            margin-bottom: 15px;
            display: inline-block;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        /* Custom Themed Buttons */
        .btn-custom {
            padding: 12px 25px;
            font-size: 15px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* High Contrast Action Button (Add Product) */
        .btn-add {
            background-color: #fff;
            color: #000;
        }
        .btn-add:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        /* Glass Outline Button (View Products) */
        .btn-view {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .btn-view:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Danger Outline Button (Logout) */
        .btn-logout {
            background-color: rgba(220, 53, 69, 0.1);
            color: #ff4757;
            border: 1px solid rgba(220, 53, 69, 0.4);
        }
        .btn-logout:hover {
            background-color: #ff4757;
            color: #fff;
            border-color: #ff4757;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        hr {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .footer-text {
            margin-top: 40px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="admin-card text-center">
                    <i class="bi bi-person-badge-fill admin-icon"></i>
                    <h2 class="fw-bold mb-2">Admin Dashboard</h2>
                    <p class="text-light opacity-75 mb-4">Welcome back, <b><?php echo htmlspecialchars($_SESSION['admin']['email']); ?></b></p>
                    
                    <hr class="w-75 mx-auto mb-4">

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="add_product.php" class="btn btn-add btn-custom">
                            <i class="bi bi-plus-circle me-2 fs-5"></i> Add Product
                        </a>

                        <a href="view_products.php" class="btn btn-view btn-custom">
                            <i class="bi bi-box-seam me-2 fs-5"></i> View Products
                        </a>

                        <a href="../logout.php" class="btn btn-logout btn-custom">
                            <i class="bi bi-box-arrow-right me-2 fs-5"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <p class="footer-text">© <?php echo date('Y'); ?> ShoeStore Admin Panel. All Rights Reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>