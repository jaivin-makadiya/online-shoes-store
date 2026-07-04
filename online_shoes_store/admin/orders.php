<?php include '../config.php'; if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit; }
$res = $mysqli->query('SELECT o.*, u.name FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.created_at DESC');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Orders</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4"><div class="container"><h3>Orders</h3>
<table class="table"><thead><tr><th>ID</th><th>User</th><th>Total</th><th>When</th></tr></thead><tbody>
<?php while($o=$res->fetch_assoc()): ?>
<tr><td><?php echo $o['id']; ?></td><td><?php echo htmlspecialchars($o['name']); ?></td><td>₹ <?php echo number_format($o['total'],2); ?></td><td><?php echo $o['created_at']; ?></td></tr>
<?php endwhile; ?>
</tbody></table></div></body></html>