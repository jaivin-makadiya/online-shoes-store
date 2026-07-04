<?php include 'header.php'; ?>

<?php
// 1. Database Query Logic
$category = isset($_GET['category']) ? $_GET['category'] : '';

if($category == 'men' || $category == 'women'){
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE category=? ORDER BY id DESC");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $mysqli->query("SELECT * FROM products ORDER BY id DESC");
}

// 2. Dynamic Image & Text Logic
$hero_title = "Step Into Style.";
$hero_bg_image = 'https://images.unsplash.com/photo-1614252209316-3e4b3e8e78e4?q=80&w=2000&auto=format&fit=crop'; 
$hero_subtitle = "Discover our latest collections and find your perfect fit today.";

if ($category == 'men') {
    $hero_bg_image = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2000&auto=format&fit=crop'; // Default (Red Nike Sneakers)
    $hero_title = "Men's Collection";
    $hero_subtitle = "Explore the best in men's footwear, built for comfort and performance.";
} elseif ($category == 'women') {
    $hero_bg_image = 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=2000&auto=format&fit=crop'; 
    $hero_title = "Women's Collection";
    $hero_subtitle = "Step out in confidence with our latest styles in women's shoes.";
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

   body {
        font-family: 'Poppins', sans-serif;
        
        /* The Background Image */
        background-image: url('https://images.unsplash.com/photo-1604147706283-d7119b5b822c?q=80&w=2000&auto=format&fit=crop'); 
        
        /* Make it cover the whole screen */
        background-size: cover;
        background-position: center;
        
        /* Keeps the image perfectly still while the user scrolls down the page */
        background-attachment: fixed; 
        
        /* This adds a slight white overlay to the image so your text and cards stay easy to read */
        background-color: rgba(248, 249, 250, 0.85);
        background-blend-mode: overlay;
    }
    /* Modern Category Pills */
    .category-filters .btn {
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }
    
    .category-filters .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Product Card Hover Effects */
    .product-card {
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: #fff;
        border: 1px solid #eee;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }

    /* Image Container for consistent sizing */
    .img-wrapper {
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: #f4f4f4;
        position: relative;
    }

    .img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .product-card:hover .img-wrapper img {
        transform: scale(1.1) rotate(-5deg);
    }

    /* Premium Price Tag */
    .price-tag {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #ff4757;
        color: white;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(255, 71, 87, 0.3);
        z-index: 2;
    }

    .product-title {
        font-size: 1.1rem;
        color: #2f3542;
    }
</style>

<div class="container mt-4 mb-5">
    
    <div class="text-center mb-5 p-5 shadow-lg" 
         style="background: linear-gradient(rgba(17, 17, 17, 0.75), rgba(30, 30, 30, 0.8)), url('<?php echo $hero_bg_image; ?>'); 
                background-size: cover; 
                background-position: center; 
                background-repeat: no-repeat; 
                color: white; 
                border-radius: 20px; 
                overflow: hidden; 
                position: relative; 
                min-height: 350px; 
                display: flex; 
                flex-direction: column; 
                justify-content: center; 
                align-items: center;">
        
        <h1 class="fw-bold mb-3 display-4 text-white"><?php echo $hero_title; ?></h1>
        <p class="lead mb-4 text-light opacity-75"><?php echo $hero_subtitle; ?></p>
        <a href="#products" class="btn btn-light btn-lg shadow-sm fw-bold px-5 rounded-pill text-dark">Shop Now</a>
    </div>

    <div class="category-filters text-center mb-5">
        <a href="index.php" class="btn <?php echo $category == '' ? 'btn-dark' : 'btn-outline-dark'; ?> me-2 mb-2">
            All Shoes
        </a>
        <a href="index.php?category=men" class="btn <?php echo $category == 'men' ? 'btn-dark' : 'btn-outline-dark'; ?> me-2 mb-2">
            👞 Men's
        </a>
        <a href="index.php?category=women" class="btn <?php echo $category == 'women' ? 'btn-dark' : 'btn-outline-dark'; ?> mb-2">
            👠 Women's
        </a>
    </div>

    <div class="d-flex justify-content-center align-items-center mb-4 mt-5">
        <h2 id="products" class="fw-bold m-0 text-dark">✨ Featured Footwear ✨</h2>
    </div>

    <div class="row g-4 justify-content-center">
        <?php while($p = $res->fetch_assoc()): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card h-100 border-0">
                    <div class="img-wrapper">
                        <span class="price-tag">₹ <?php echo number_format($p['price'], 2); ?></span>
                        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title fw-bold product-title mb-1 text-center">
                            <?php echo htmlspecialchars($p['name']); ?>
                        </h5>
                        <p class="text-muted small mb-4 text-center">Premium comfort & style</p>

                        <div class="mt-auto d-flex flex-column gap-2">
                            <a href="product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-dark btn-sm w-100 fw-semibold rounded-pill">
                                👀 View Details
                            </a>
                            <form method="post" action="cart.php" class="m-0">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold rounded-pill">
                                    🛒 Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if($res->num_rows == 0): ?>
        <div class="text-center py-5 my-5 rounded-4 shadow-sm" 
             style="background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.95)), url('https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=1000&auto=format&fit=crop'); 
                    background-size: cover; 
                    background-position: center; 
                    border: 1px dashed #ccc;">
            <h1 class="display-1 text-muted mb-3">👟</h1>
            <h4 class="fw-bold text-dark">No shoes found</h4>
            <p class="text-muted">We couldn't find any products in this category right now. Check back later!</p>
            <a href="index.php" class="btn btn-dark mt-3 rounded-pill px-4">View All Shoes</a>
        </div>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>