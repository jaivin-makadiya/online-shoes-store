<?php 
session_start();
include '../config.php'; 

// Using the session check from your snippet
if(!isset($_SESSION['admin_id']) && !isset($_SESSION['admin'])){ 
  header('Location: login.php'); 
  exit; 
}

$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare('SELECT * FROM products WHERE id=?'); 
$stmt->bind_param('i',$id); 
$stmt->execute(); 
$r = $stmt->get_result()->fetch_assoc();

$msg = '';

if(!$r){ 
  echo '<div class="container py-5"><div class="alert custom-alert text-center" style="background-color: rgba(220, 53, 69, 0.8) !important; color: white;">❌ Product not found.</div></div>'; 
  exit; 
}

if($_SERVER['REQUEST_METHOD']=='POST'){
  $name = trim($_POST['name']); 
  $price = floatval($_POST['price']); 
  $desc = trim($_POST['description']); 
  $image = trim($_POST['image']);
  
  $up = $mysqli->prepare('UPDATE products SET name=?,price=?,description=?,image=? WHERE id=?');
  $up->bind_param('sdssi',$name,$price,$desc,$image,$id);
  
  if($up->execute()){
    $msg = '<div class="alert custom-alert text-center rounded-3 shadow-sm mb-4" style="background-color: rgba(25, 135, 84, 0.8) !important;">✅ Product updated successfully.</div>';
    // Refresh data after update
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
  } else {
    $msg = '<div class="alert custom-alert text-center rounded-3 shadow-sm mb-4" style="background-color: rgba(220, 53, 69, 0.8) !important;">⚠️ Update failed. Please try again.</div>';
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Product | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
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
        
        /* Center the form vertically */
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 40px 0;
    }

    /* Sleek Glassmorphism Card for Admin */
    .admin-card {
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(15, 15, 15, 0.7); 
        backdrop-filter: blur(15px); 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        color: #fff;
    }

    /* Dark Themed Input Fields */
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

    /* High Contrast Action Button */
    .btn-primary-custom {
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        background-color: #fff;
        color: #000;
        border: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background-color: #e0e0e0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    }

    /* Links Styling */
    .text-link {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .text-link:hover {
        color: #fff;
        text-decoration: underline;
    }

    /* Alert Styling */
    .custom-alert {
        backdrop-filter: blur(5px);
        border: none;
        color: #fff;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-9">
        <div class="card admin-card p-5 border-0">
          <h2 class="text-center mb-4 fw-bold">✏️ Edit Product</h2>
          
          <?php if($msg) echo $msg; ?>

          <form method="post">
            <div class="mb-4">
              <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Product Name</label>
              <input class="form-control" name="name" value="<?php echo htmlspecialchars($r['name']); ?>" required>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Price (₹)</label>
              <input class="form-control" name="price" type="number" step="0.01" value="<?php echo $r['price']; ?>" required>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Image URL</label>
              <input class="form-control" name="image" value="<?php echo htmlspecialchars($r['image']); ?>" required>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Description</label>
              <textarea class="form-control" name="description" rows="4" required><?php echo htmlspecialchars($r['description']); ?></textarea>
            </div>

            <div class="d-grid mt-2">
              <button class="btn btn-primary-custom shadow-sm">Update Product</button>
            </div>
          </form>

          <div class="text-center mt-4">
            <a href="dashboard.php" class="text-link fw-semibold">← Back to Dashboard</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>