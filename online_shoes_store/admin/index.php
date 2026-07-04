<?php 
session_start();
include '../config.php'; 

// Check if admin is logged in
if(!isset($_SESSION['admin_id']) && !isset($_SESSION['admin'])){ 
  header('Location: login.php'); 
  exit; 
} 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - View Products</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        padding-top: 40px;
        padding-bottom: 60px;
        min-height: 100vh;
    }

    /* Sleek Glassmorphism Card for Admin */
    .admin-card {
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(15, 15, 15, 0.7); 
        backdrop-filter: blur(15px); 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        padding: 40px;
        color: #fff;
    }

    /* Custom Buttons */
    .btn-custom {
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add {
        background-color: #fff;
        color: #000;
        border: none;
    }
    
    .btn-add:hover {
        background-color: #e0e0e0;
        transform: translateY(-2px);
    }

    .btn-outline-glass {
        background-color: rgba(255, 255, 255, 0.05);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-outline-glass:hover {
        background-color: rgba(255, 255, 255, 0.15);
        color: #fff;
        transform: translateY(-2px);
    }

    /* Action Buttons in Table */
    .btn-action {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background-color: rgba(13, 110, 253, 0.2);
        color: #74b9ff;
        border: 1px solid rgba(13, 110, 253, 0.4);
    }
    
    .btn-edit:hover {
        background-color: rgba(13, 110, 253, 0.8);
        color: #fff;
    }

    .btn-delete {
        background-color: rgba(220, 53, 69, 0.2);
        color: #ff4757;
        border: 1px solid rgba(220, 53, 69, 0.4);
    }

    .btn-delete:hover {
        background-color: #ff4757;
        color: #fff;
    }

    /* Dark Mode Table Styling */
    .table-dark-glass {
        color: #fff;
        margin-top: 20px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-dark-glass th {
        background: transparent;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
    }

    .table-dark-glass td {
        background: transparent;
        color: #f8f9fa;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 18px 15px;
        vertical-align: middle;
    }

    /* Subtle row hover effect */
    .table-dark-glass tbody tr {
        transition: background-color 0.3s ease;
    }

    .table-dark-glass tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
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
  </style>
</head>

<body>
  <div class="container">
    <div class="mb-4">
      <a href="dashboard.php" class="text-link fw-semibold">← Back to Dashboard</a>
    </div>

    <div class="admin-card border-0">
      
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0 mb-3 mb-md-0">📦 Manage Products</h2>
        <div class="d-flex gap-2">
          <a href="orders.php" class="btn btn-outline-glass btn-custom">
            <i class="bi bi-receipt"></i> View Orders
          </a>
          <a href="add_product.php" class="btn btn-add btn-custom">
            <i class="bi bi-plus-lg"></i> Add Product
          </a>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-dark-glass align-middle">
          <thead>
            <tr>
              <th scope="col" width="10%">ID</th>
              <th scope="col" width="45%">Product Name</th>
              <th scope="col" width="20%">Price</th>
              <th scope="col" width="25%" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $res = $mysqli->query('SELECT * FROM products ORDER BY id DESC'); 
            while($r = $res->fetch_assoc()): 
            ?>
              <tr>
                <td class="fw-bold text-light opacity-75">#<?php echo $r['id']; ?></td>
                <td class="fw-semibold fs-6"><?php echo htmlspecialchars($r['name']); ?></td>
                <td>
                  <span class="badge" style="background-color: rgba(255, 71, 87, 0.2); color: #ff4757; font-size: 0.9rem; padding: 6px 10px; border: 1px solid rgba(255, 71, 87, 0.4);">
                    ₹ <?php echo number_format($r['price'], 2); ?>
                  </span>
                </td>
                <td class="text-end">
                  <a href="edit_product.php?id=<?php echo $r['id']; ?>" class="btn btn-action btn-edit me-1">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="delete_product.php?id=<?php echo $r['id']; ?>" class="btn btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">
                    <i class="bi bi-trash3"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
            
            <?php if($res->num_rows == 0): ?>
              <tr>
                <td colspan="4" class="text-center py-5 text-light opacity-50">
                  <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                  No products found in the database.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>