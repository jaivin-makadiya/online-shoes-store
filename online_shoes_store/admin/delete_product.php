<?php include '../config.php'; if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit; }
$id = intval($_GET['id'] ?? 0);
$mysqli->query('DELETE FROM products WHERE id='.$id);
header('Location: index.php'); exit;