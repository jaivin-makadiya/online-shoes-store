<?php
session_start();
include '../config.php';

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/';
        $file_name = basename($_FILES['image']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image = 'assets/' . $file_name;
        } else {
            $msg = "<div class='alert custom-alert text-center rounded-3 mb-4' style='background-color: rgba(220, 53, 69, 0.8) !important;'>❌ Image upload failed!</div>";
        }
    }

    if ($name !== '' && $price !== '' && $category !== '' && $description !== '' && $image !== '') {
        $stmt = $mysqli->prepare("INSERT INTO products (name, price, description, image, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $name, $price, $description, $image, $category);

        if ($stmt->execute()) {
            $msg = "<div class='alert custom-alert text-center rounded-3 mb-4' style='background-color: rgba(25, 135, 84, 0.8) !important;'>✅ Product added successfully!</div>";
        } else {
            $msg = "<div class='alert custom-alert text-center rounded-3 mb-4' style='background-color: rgba(220, 53, 69, 0.8) !important;'>❌ Database error: " . $stmt->error . "</div>";
        }
    } else {
        if(empty($msg)) {
            $msg = "<div class='alert custom-alert text-center rounded-3 mb-4' style='background-color: rgba(255, 193, 7, 0.8) !important; color: #000;'>⚠️ Please fill all fields and upload an image.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Product - Online Shoes Store</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

    body {
        padding-top: 80px;
        font-family: 'Poppins', sans-serif;
        
        /* Premium Dark Mode Background */
        background-image: url('https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=2000&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-color: rgba(15, 15, 15, 0.85);
        background-blend-mode: overlay;
        color: #f8f9fa; 
    }

    /* Sleek Dark Navbar with Glassmorphism */
    .navbar {
        background: rgba(10, 10, 10, 0.9) !important; 
        backdrop-filter: blur(12px); 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .navbar-brand {
        font-weight: 800;
        letter-spacing: 1px;
        color: #fff !important;
        font-size: 1.2rem;
        text-transform: uppercase;
    }

    .navbar-brand:hover {
        color: #e0e0e0 !important;
    }

    .btn-light-custom {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-light-custom:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .btn-danger-custom {
        background-color: rgba(220, 53, 69, 0.2);
        color: #ff4757;
        border: 1px solid rgba(220, 53, 69, 0.4);
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-danger-custom:hover {
        background-color: #ff4757;
        color: #fff;
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
    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    /* Fix dropdown options readability in dark mode */
    option {
        background-color: #1a1a1a;
        color: #fff;
    }

    /* High Contrast Action Button */
    .btn-primary-custom {
        border-radius: 12px;
        padding: 12px 30px;
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

    .btn-secondary-custom {
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 700;
        background-color: transparent;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    /* Alert Styling */
    .custom-alert {
        backdrop-filter: blur(5px);
        border: none;
        color: #fff;
    }

    footer {
        margin-top: 60px;
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
        padding-bottom: 20px;
    }
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">👟 Admin Panel</a>

        <div class="ms-auto d-flex gap-2">
            <a href="dashboard.php" class="btn btn-light-custom btn-sm px-3 py-2">Dashboard</a>
            <a href="../logout.php" class="btn btn-danger-custom btn-sm px-3 py-2">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5 pb-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card admin-card p-5 border-0">
                <h2 class="text-center fw-bold mb-4">✨ Add New Product</h2>

                <?php if ($msg): ?>
                    <?php echo $msg; ?>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Price (₹)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="men">👞 Men</option>
                                <option value="women">👠 Women</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Description</label>
                        <textarea name="description" class="form-control" placeholder="Enter product description" rows="4" required></textarea>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold text-light opacity-75 small text-uppercase">Upload Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="dashboard.php" class="btn btn-secondary-custom px-4">← Back</a>
                        <button type="submit" class="btn btn-primary-custom px-5">+ Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>© <?php echo date("Y"); ?> Online Shoes Store | Designed with 💙 for Customers</p>
</footer>

</body>
</html>