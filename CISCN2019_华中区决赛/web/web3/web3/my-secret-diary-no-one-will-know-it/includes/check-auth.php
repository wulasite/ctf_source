<?php
if (!isset($_SESSION['user'])) {
  header('location: ?action=pages/login');
  exit;
}
$user = &$_SESSION['user'];
?>
