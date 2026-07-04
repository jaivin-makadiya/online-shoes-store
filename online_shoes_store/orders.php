<?php include 'header.php';
if(!isset($_SESSION['user_id'])){ echo '<div class="alert alert-warning">Please <a href="login.php">login</a>.</div>'; include 'footer.php'; exit; }
$uid = $_SESSION['user_id'];
$res = $mysqli->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");
?>
<h2>My Orders</h2>
<?php if($res->num_rows==0) echo '<div class="alert alert-info">No orders yet.</div>'; ?>
<?php while($o=$res->fetch_assoc()): ?>
  <div class="card mb-3"><div class="card-body">
    <h5>Order #<?php echo $o['id']; ?> - ₹ <?php echo number_format($o['total'],2); ?></h5>
    <p><small><?php echo $o['created_at']; ?></small></p>
    <?php
      $it = $mysqli->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=".$o['id']);
      while($row=$it->fetch_assoc()){
        echo '<div>'.htmlspecialchars($row['name']).' x '.$row['qty'].' - ₹ '.number_format($row['price'],2).'</div>';
      }
    ?>
  </div></div>
<?php endwhile; ?>
<?php include 'footer.php'; ?>