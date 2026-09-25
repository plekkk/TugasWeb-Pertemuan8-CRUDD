<?php

require_once 'config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

$id = $_GET['id'] ?? '';

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id <= 0) {
    header('Location: index.php?error=ID produk tidak valid.');
    exit;
}

$id = (int)$id;

try {

    $stmt = $pdo->prepare("
        SELECT id
        FROM products
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $product = $stmt->fetch();

    if (!$product) {
        header('Location: index.php?error=Produk tidak ditemukan.');
        exit;
    }

    $stmt = $pdo->prepare("
        DELETE FROM products
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    header('Location: index.php?success=Produk berhasil dihapus.');
    exit;

} catch (PDOException $e) {

    header('Location: index.php?error=Produk gagal dihapus.');
    exit;
}