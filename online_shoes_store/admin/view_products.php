<?php
session_start();
include '../config.php';

// Redirect if admin not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Delete product if requested
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // First delete related order items
    $stmt1 = $mysqli->prepare("DELETE FROM order_items WHERE product_id = ?");
    $stmt1->bind_param("i", $delete_id);
    $stmt1->execute();
    $stmt1->close();

    // Then delete the product
    $stmt2 = $mysqli->prepare("DELETE FROM products WHERE id = ?");
    $stmt2->bind_param("i", $delete_id);
    $stmt2->execute();
    $stmt2->close();

    header("Location: view_products.php");
    exit;
}

// Fetch all products
$result = $mysqli->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Products | ShoeStore Admin</title>

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
        padding: 6px 14px;
        font-size: 12px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .btn-delete {
        background-color: rgba(220, 53, 69, 0.1);
        color: #ff4757;
        border: 1px solid rgba(220, 53, 69, 0.4);
    }

    .btn-delete:hover {
        background-color: #ff4757;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
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
        text-align: center;
    }

    .table-dark-glass td {
        background: transparent;
        color: #f8f9fa;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 15px;
        vertical-align: middle;
        text-align: center;
    }

    .table-dark-glass tbody tr {
        transition: background-color 0.3s ease;
    }

    .table-dark-glass tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Styled Product Images */
    .product-img {
        border-radius: 12px;
        object-fit: contain;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 5px;
        transition: transform 0.3s ease;
    }
    
    .product-img:hover {
        transform: scale(1.1);
    }

    .footer-text {
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
        margin-top: 25px;
    }
</style>

</head>

<body>

<div class="container mt-4">

    <div class="admin-card border-0">
        
        <div class="text-center mb-5">
            <i class="bi bi-box-seam text-light opacity-75" style="font-size: 3rem; text-shadow: 0 0 20px rgba(255,255,255,0.2);"></i>
            <h2 class="fw-bold mt-2 mb-1">Manage Products</h2>
            <p class="text-light opacity-50 small text-uppercase letter-spacing-1">View, manage, or delete products from your store</p>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="dashboard.php" class="btn btn-outline-glass btn-custom">
                <i class="bi bi-arrow-left-short fs-5"></i> Back to Dashboard
            </a>
            <a href="add_product.php" class="btn btn-add btn-custom">
                <i class="bi bi-plus-lg"></i> Add New Product
            </a>
        </div>

        <div class="table-responsive mt-2">
            <table class="table table-dark-glass align-middle">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Image</th>
                        <th width="20%">Name</th>
                        <th width="15%">Price</th>
                        <th width="35%">Description</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold text-light opacity-75">#<?php echo $row['id']; ?></td>
                        
                        <td>
                            <img src="../<?php echo htmlspecialchars($row['image']); ?>" width="80" height="80" class="product-img shadow-sm" alt="Product Image">
                        </td>
                        
                        <td class="fw-semibold fs-6">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>
                        
                        <td>
                            <span class="badge" style="background-color: rgba(255, 71, 87, 0.2); color: #ff4757; font-size: 0.9rem; padding: 6px 12px; border: 1px solid rgba(255, 71, 87, 0.4); border-radius: 30px;">
                                ₹ <?php echo number_format($row['price'], 2); ?>
                            </span>
                        </td>
                        
                        <td class="text-light opacity-75 small text-start">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </td>
                        
                        <td>
                            <a href="view_products.php?delete=<?php echo $row['id']; ?>"
                               onclick="return confirm('Are you sure you want to completely delete this product?');"
                               class="btn btn-delete btn-action">
                                <i class="bi bi-trash3"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-light opacity-50">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            No products found in the database.
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>

    <p class="footer-text">
        © <?php echo date('Y'); ?> ShoeStore Admin Panel. All Rights Reserved.
    </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>