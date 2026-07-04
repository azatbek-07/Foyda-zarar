<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
    exit;
}

include './db.php';

$db = new DB();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ./index.php");
    exit;
}
$db->delete('products', [
    'id' => $id
]);

header("Location: ./");
exit;

?>