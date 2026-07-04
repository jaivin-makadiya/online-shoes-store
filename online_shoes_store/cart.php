<?php
include 'header.php';

// Compatible retrieval of action from POST or GET
$action = null;
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
}

// Add to cart
if ($action == 'add') {
    $pid = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $qty = isset($_POST['qty']) ? max(1, intval($_POST['qty'])) : 1;
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] += $qty;
    } else {
        $_SESSION['cart'][$pid] = $qty;
    }
    header('Location: cart.php');
    exit;
}

// Remove from cart
if ($action == 'remove') {
    $pid = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]);
    }
    header('Location: cart.php');
    exit;
}

// Update cart quantities
if ($action == 'update') {
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $pid => $q) {
            $pid_int = intval($pid);
            $q_int = max(1, intval($q));
            $_SESSION['cart'][$pid_int] = $q_int;
        }
    }
    header('Location: cart.php');
    exit;
}

// Show cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();

if (!empty($cart)) {
    $ids_arr = array();
    foreach (array_keys($cart) as $k) {
        $ids_arr[] = intval($k);
    }
    $ids = implode(',', $ids_arr);
    if ($ids != '') {
        $res = $mysqli->query("SELECT * FROM products WHERE id IN ($ids)");
        while ($r = $res->fetch_assoc()) {
            $products[$r['id']] = $r;
        }
    }
}
?>

<style>
    /* Sleek Glassmorphism Container */
    .cart-card {
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
        padding: 20px 15px;
        vertical-align: middle;
    }

    .table-dark-glass tbody tr {
        transition: background-color 0.3s ease;
    }

    .table-dark-glass tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Custom Input for Quantity */
    .qty-input {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
        border-radius: 10px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .qty-input:focus {
        border-color: rgba(255, 255, 255, 0.4) !important;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1) !important;
    }

    /* Product Images */
    .product-img {
        border-radius: 12px;
        object-fit: contain;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 5px;
    }

    /* Custom Buttons */
    .btn-custom {
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-checkout {
        background-color: #fff;
        color: #000;
        border: none;
    }
    
    .btn-checkout:hover {
        background-color: #e0e0e0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
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

    .btn-delete {
        background-color: rgba(220, 53, 69, 0.1);
        color: #ff4757;
        border: 1px solid rgba(220, 53, 69, 0.4);
        padding: 8px 15px;
        font-size: 0.85rem;
    }

    .btn-delete:hover {
        background-color: #ff4757;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    /* Price Tags */
    .price-badge {
        background-color: rgba(255, 71, 87, 0.2);
        color: #ff4757;
        font-size: 0.95rem;
        padding: 6px 12px;
        border: 1px solid rgba(255, 71, 87, 0.4);
        border-radius: 30px;
        font-weight: 700;
    }

    .total-section {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        padding: 15px 30px;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>

<div class="container pb-5">
    <div class="cart-card">
        
        <div class="text-center mb-5">
            <i class="bi bi-cart3 text-light opacity-75" style="font-size: 3rem; text-shadow: 0 0 20px rgba(255,255,255,0.2);"></i>
            <h2 class="fw-bold mt-2 mb-1">Your Shopping Cart</h2>
            <p class="text-light opacity-50 small text-uppercase letter-spacing-1">Review your items before checkout</p>
        </div>

        <?php if (empty($cart)): ?>
            <div class="text-center py-5">
                <i class="bi bi-basket text-light opacity-25" style="font-size: 5rem;"></i>
                <h4 class="fw-bold mt-4 mb-3">Your cart is completely empty</h4>
                <p class="text-light opacity-50 mb-4">Looks like you haven't made your choice yet.</p>
                <a href="index.php" class="btn btn-checkout btn-custom px-5">
                    <i class="bi bi-arrow-left-short fs-4"></i> Start Shopping
                </a>
            </div>
        <?php else: ?>
            <form method="post" action="cart.php">
                <input type="hidden" name="action" value="update">

                <div class="table-responsive">
                    <table class="table table-dark-glass text-center align-middle">
                        <thead>
                            <tr>
                                <th class="text-start">Product Details</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0; 
                            foreach ($cart as $pid => $q): 
                                if (!isset($products[$pid])) continue;
                                $p = $products[$pid];
                                $sub = $p['price'] * $q;
                                $total += $sub;
                            ?>
                                <tr>
                                    <td class="text-start">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo htmlspecialchars($p['image']); ?>" width="80" height="80" class="product-img shadow-sm" alt="Product Image">
                                            <div>
                                                <h6 class="mb-1 fw-bold fs-5"><?php echo htmlspecialchars($p['name']); ?></h6>
                                                <small class="text-light opacity-50 d-none d-md-block"><?php echo htmlspecialchars(substr($p['description'], 0, 45)); ?>...</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="price-badge">₹ <?php echo number_format($p['price'], 2); ?></span>
                                    </td>
                                    
                                    <td>
                                        <input type="number" name="qty[<?php echo intval($pid); ?>]" 
                                               value="<?php echo intval($q); ?>" 
                                               class="form-control qty-input mx-auto" min="1" style="width: 80px;">
                                    </td>
                                    
                                    <td>
                                        <span class="fw-bold fs-5">₹ <?php echo number_format($sub, 2); ?></span>
                                    </td>
                                    
                                    <td>
                                        <a href="cart.php?action=remove&id=<?php echo intval($pid); ?>" 
                                           class="btn btn-delete btn-custom"
                                           onclick="return confirm('Remove this item from your cart?')">
                                           <i class="bi bi-trash3"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center mt-5 pt-3 border-top border-secondary border-opacity-25 gap-4">
                    
                    <a href="index.php" class="btn btn-outline-glass btn-custom">
                        <i class="bi bi-arrow-left-short fs-4"></i> Continue Shopping
                    </a>
                    
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                        <button type="submit" class="btn btn-outline-glass btn-custom">
                            <i class="bi bi-arrow-repeat"></i> Update Cart
                        </button>
                        
                        <div class="total-section ms-md-3">
                            Total: <span class="text-danger">₹ <?php echo number_format($total, 2); ?></span>
                        </div>
                        
                        <a href="checkout.php" class="btn btn-checkout btn-custom px-4 ms-md-2">
                            Checkout <i class="bi bi-credit-card ms-1"></i>
                        </a>
                    </div>
                </div>
                
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>