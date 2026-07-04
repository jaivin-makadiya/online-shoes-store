<?php
include 'header.php';
?>

<style>
    /* Sleek Glassmorphism Container */
    .checkout-card {
        background: rgba(15, 15, 15, 0.7); 
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        padding: 40px;
        color: #fff;
        margin-top: 20px;
        margin-bottom: 50px;
    }

    /* Custom List Group for Dark Mode */
    .list-group-custom .list-group-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        margin-bottom: 8px;
        border-radius: 12px !important;
        transition: all 0.3s ease;
    }

    .list-group-custom .list-group-item:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateX(5px);
    }

    .list-group-custom .total-row {
        background: rgba(255, 71, 87, 0.1) !important;
        border-color: rgba(255, 71, 87, 0.3) !important;
        margin-top: 15px;
    }

    /* Dark Themed Input Fields */
    .form-label {
        font-weight: 600;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 12px;
        padding: 15px;
        transition: all 0.3s ease;
        resize: none;
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

    /* High Contrast Checkout Button */
    .btn-checkout {
        background-color: #fff;
        color: #000;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .btn-checkout:hover {
        background-color: #e0e0e0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    }

    /* Price Badge */
    .price-text {
        color: #ff4757;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
</style>

<?php
if(!isset($_SESSION['user_id'])){
    echo '<div class="container mt-5 pb-5">
            <div class="checkout-card text-center py-5">
                <i class="bi bi-shield-lock text-light opacity-50 mb-3 d-block" style="font-size: 4rem;"></i>
                <h3 class="fw-bold text-white mb-3">Authentication Required</h3>
                <p class="text-light opacity-75 mb-4">You must securely log in to complete your purchase.</p>
                <a href="login.php" class="btn btn-checkout px-5">Login to Continue</a>
            </div>
          </div>';
    include 'footer.php';
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if(!$cart){
    echo '<div class="container mt-5 pb-5">
            <div class="checkout-card text-center py-5">
                <i class="bi bi-cart-x text-light opacity-50 mb-3 d-block" style="font-size: 4rem;"></i>
                <h3 class="fw-bold text-white mb-3">Your cart is empty!</h3>
                <p class="text-light opacity-75 mb-4">Looks like you haven\'t selected any shoes yet.</p>
                <a href="index.php" class="btn btn-checkout px-5"><i class="bi bi-arrow-left-short fs-4"></i> Go Shopping</a>
            </div>
          </div>';
    include 'footer.php';
    exit;
}

$ids = implode(',', array_map('intval', array_keys($cart)));
$res = $mysqli->query("SELECT * FROM products WHERE id IN ($ids)");
$products = [];
$total = 0;
while($r = $res->fetch_assoc()){
    $products[$r['id']] = $r;
    $total += $r['price'] * $cart[$r['id']];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = $_SESSION['user_id'];
    $address = $mysqli->real_escape_string($_POST['address']);
    $stmt = $mysqli->prepare('INSERT INTO orders (user_id,total,address,created_at) VALUES (?,?,?,NOW())');
    $stmt->bind_param('ids', $user_id, $total, $address);
    if($stmt->execute()){
        $order_id = $stmt->insert_id;
        $ins = $mysqli->prepare('INSERT INTO order_items (order_id,product_id,price,qty) VALUES (?,?,?,?)');
        foreach($cart as $pid => $q){
            $price = $products[$pid]['price'];
            $ins->bind_param('iidi', $order_id, $pid, $price, $q);
            $ins->execute();
        }
        unset($_SESSION['cart']);
        echo '<div class="container mt-5 pb-5">
                <div class="checkout-card text-center py-5">
                    <i class="bi bi-check-circle-fill mb-3 d-block" style="font-size: 4rem; color: #2ecc71; text-shadow: 0 0 20px rgba(46, 204, 113, 0.4);"></i>
                    <h2 class="fw-bold text-white mb-3">Order Placed Successfully!</h2>
                    <p class="text-light opacity-75 mb-4 fs-5">Thank you for shopping with us.<br>Your Order ID is <strong class="text-white">#'.intval($order_id).'</strong>.</p>
                    <a href="index.php" class="btn btn-checkout px-5 mt-2">Continue Shopping</a>
                </div>
              </div>';
        include 'footer.php';
        exit;
    } else {
        echo '<div class="container mt-5 pb-5">
                <div class="checkout-card text-center py-5" style="border-color: rgba(220, 53, 69, 0.3);">
                    <i class="bi bi-exclamation-triangle-fill text-danger mb-3 d-block" style="font-size: 4rem;"></i>
                    <h3 class="fw-bold text-white mb-3">Failed to place order</h3>
                    <p class="text-light opacity-75 mb-4">Something went wrong on our end. Please try again later.</p>
                    <a href="checkout.php" class="btn btn-checkout px-5">Try Again</a>
                </div>
              </div>';
    }
}
?>

<div class="container mt-4 pb-5">
    <div class="checkout-card border-0">
        
        <div class="text-center mb-5">
            <i class="bi bi-credit-card-2-front text-light opacity-75" style="font-size: 3rem; text-shadow: 0 0 20px rgba(255,255,255,0.2);"></i>
            <h2 class="fw-bold mt-2 mb-1">Secure Checkout</h2>
            <p class="text-light opacity-50 small text-uppercase" style="letter-spacing: 1px;">Review your details and confirm your order</p>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <h5 class="form-label mb-3"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                <ul class="list-group list-group-custom border-0">
                    <?php foreach($cart as $pid => $q): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span class="d-flex align-items-center gap-2">
                                <span class="fw-semibold"><?php echo htmlspecialchars($products[$pid]['name']); ?></span>
                                <span class="badge bg-light text-dark rounded-pill">x<?php echo $q; ?></span>
                            </span>
                            <span class="price-text">₹ <?php echo number_format($products[$pid]['price'] * $q, 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                    
                    <li class="list-group-item total-row d-flex justify-content-between align-items-center p-4">
                        <span class="fw-bold fs-5 text-white text-uppercase" style="letter-spacing: 1px;">Total Amount</span>
                        <span class="fw-bold fs-4 price-text">₹ <?php echo number_format($total, 2); ?></span>
                    </li>
                </ul>
            </div>

            <div class="col-lg-6">
                <h5 class="form-label mb-3"><i class="bi bi-geo-alt me-2"></i>Shipping Details</h5>
                <form method="post" class="mt-3">
                    <div class="mb-4">
                        <textarea name="address" class="form-control" rows="5" placeholder="Enter your complete shipping address (Street, City, ZIP Code)" required></textarea>
                    </div>
                    
                    <div class="d-flex align-items-center bg-dark bg-opacity-25 border border-secondary border-opacity-25 rounded-3 p-3 mb-4">
                        <i class="bi bi-shield-check fs-3 text-success me-3"></i>
                        <div>
                            <p class="mb-0 fw-semibold fs-6">Cash on Delivery (COD)</p>
                            <small class="text-light opacity-50">Pay securely when your shoes arrive.</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-checkout w-100">
                        Confirm & Place Order <i class="bi bi-bag-check-fill fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>