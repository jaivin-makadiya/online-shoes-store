<?php include 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Online Shoes Store</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

    body {
      padding-top: 80px;
      font-family: 'Poppins', sans-serif;
      
      /* Premium Dark Mode Background (Applied globally via header) */
      background-image: url('https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=2000&auto=format&fit=crop');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      background-color: rgba(15, 15, 15, 0.85);
      background-blend-mode: overlay;
      
      /* Default text color for dark mode */
      color: #f8f9fa; 
    }

    /* Sleek Dark Navbar with Glassmorphism */
    .navbar {
      background: rgba(10, 10, 10, 0.9) !important; /* Deep dark transparency */
      backdrop-filter: blur(12px); /* Blurred glass effect */
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      transition: background 0.4s ease-in-out;
    }

    .navbar-brand {
      font-weight: 800;
      letter-spacing: 1px;
      color: #fff !important;
      font-size: 1.5rem;
      text-transform: uppercase;
      transition: 0.3s;
    }

    .navbar-brand:hover {
      transform: scale(1.05);
      color: #e0e0e0 !important;
    }

    /* Navbar Links Styling */
    .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.75) !important;
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      transition: all 0.3s ease;
      padding: 8px 18px;
      margin: 0 4px;
      border-radius: 50px;
    }

    .navbar-nav .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: #fff !important;
      transform: translateY(-2px);
    }

    /* Admin Link Special Styling */
    .navbar-nav .nav-link.text-warning {
      color: #e1b12c !important; /* Muted premium gold */
    }

    /* Premium Cart Count Badge */
    .cart-count {
      background: #ff4757; /* Matches the price tag from the products page */
      color: #fff;
      border-radius: 50px;
      font-size: 0.75rem;
      padding: 4px 10px;
      margin-left: 6px;
      font-weight: 700;
      box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
    }

    /* Mobile Menu Toggler Adjustment */
    .navbar-toggler {
      border-color: rgba(255, 255, 255, 0.2);
    }
    
    .navbar-toggler-icon {
      filter: invert(1); /* Makes the hamburger icon white */
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      👟 Footwear
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Shop</a></li>

        <?php if(isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="orders.php">My Orders</a></li>
          <li class="nav-item">
            <a class="nav-link" href="cart.php">
              Cart 
              <span class="cart-count">
                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
              </span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>

        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="cart.php">
              Cart 
              <span class="cart-count">
                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
              </span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link text-warning fw-bold" href="admin/login.php">Admin</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">