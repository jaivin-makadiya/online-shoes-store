<?php include 'header.php'; ?>
<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param('i',$id);
$stmt->execute();
$res = $stmt->get_result();
$p = $res->fetch_assoc();
if(!$p){ echo '<div class="alert alert-danger">Product not found.</div>'; include 'footer.php'; exit; }
?>

<style>
  /* Import a sleek, sporty, yet premium font */
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: radial-gradient(circle at 10% 20%, rgb(20, 20, 25) 0%, rgb(10, 10, 12) 100%);
    color: #fff;
    margin: 0;
  }

  /* Main Container Layout */
  .product-detail-container {
    padding: 60px 20px;
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
  }

  /* Premium Product Image Panel */
  .product-image-wrapper {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 40px;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    transition: transform 0.3s ease;
  }

  .product-image-wrapper:hover {
    transform: scale(1.02);
  }

  .product-image-wrapper img {
    max-height: 450px;
    object-fit: contain;
    filter: drop-shadow(0 15px 25px rgba(0,0,0,0.6));
  }

  /* Right Side content block */
  .product-info-panel {
    padding-left: 40px;
  }

  @media (max-width: 768px) {
    .product-info-panel { padding-left: 0; margin-top: 40px; }
  }

  /* Typography Rules */
  .product-title {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: -0.5px;
    margin-bottom: 12px;
    background: linear-gradient(180deg, #FFFFFF 0%, rgba(255, 255, 255, 0.8) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .product-price {
    font-size: 1.75rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 24px;
    display: inline-block;
  }

  .product-description {
    color: rgba(255, 255, 255, 0.6);
    font-size: 1rem;
    line-height: 1.7;
    margin-bottom: 30px;
  }

  /* Premium Custom Quantity Input */
  .qty-label {
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 8px;
    display: block;
  }

  .product-info-panel .form-control-qty {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    text-align: center;
    font-size: 1rem;
    transition: all 0.25s ease;
  }

  .product-info-panel .form-control-qty:focus {
    background-color: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.05);
    outline: none;
  }

  /* Sneaker Size Badges (Bonus Visual UI) */
  .size-selector-wrap {
    margin-bottom: 30px;
  }
  .size-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
  }
  .size-badge {
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.02);
    transition: all 0.2s ease;
  }
  .size-badge.active, .size-badge:hover {
    background: #fff;
    color: #000;
    border-color: #fff;
  }

  /* High Contrast Interactive Checkout Button */
  .btn-add-cart {
    background-color: #fff;
    color: #000;
    border: none;
    border-radius: 14px;
    padding: 16px 32px;
    font-weight: 700;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    width: 100%;
    max-width: 300px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
  }

  .btn-add-cart:hover {
    background-color: #f0f0f0;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
  }

  .btn-add-cart:active {
    transform: translateY(-1px);
  }

  /* Error Alert Overrides */
  .alert-danger {
    background-color: rgba(220, 53, 69, 0.2) !important;
    border: 1px solid rgba(220, 53, 69, 0.4) !important;
    color: #ff808b !important;
    border-radius: 14px;
    padding: 20px;
    text-align: center;
    backdrop-filter: blur(10px);
  }
</style>

<div class="container product-detail-container">
  <div class="row align-items-center w-100">
    <div class="col-md-5">
      <div class="product-image-wrapper">
        <img src="<?php echo $p['image']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($p['name']); ?>">
      </div>
    </div>
    
    <div class="col-md-7">
      <div class="product-info-panel">
        <h2 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h2>
        <div class="product-price">₹ <?php echo number_format($p['price'], 2); ?></div>
        
        <p class="product-description"><?php echo htmlspecialchars($p['description']); ?></p>
        
        <div class="size-selector-wrap">
          <span class="qty-label">Select UK Size</span>
          <div class="size-badges">
            <div class="size-badge">UK 7</div>
            <div class="size-badge">UK 8</div>
            <div class="size-badge">UK 9</div>
            <div class="size-badge">UK 10</div>
          </div>
        </div>

        <form method="post" action="cart.php">
          <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
          <input type="hidden" name="action" value="add">
          
          <div class="mb-4">
            <label class="qty-label">Quantity</label>
            <input type="number" name="qty" value="1" min="1" class="form-control-qty" style="width:100px;">
          </div>
          
          <button class="btn btn-add-cart">Add to cart</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>