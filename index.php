<?php

require_once 'config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

$stmt = $pdo->query("
    SELECT
        p.id,
        p.name,
        c.name AS category,
        s.name AS supplier,
        p.price,
        p.stock
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN suppliers s ON p.supplier_id = s.id
    ORDER BY p.id DESC
");

$products = $stmt->fetchAll();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<div class="container">

    <h1>CRUD Inventaris</h1>

    <?php if ($success !== ''): ?>
        <div class="alert success">
            <?= e($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert error">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <a href="create.php" class="btn">+ Tambah Produk</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php if (count($products) > 0): ?>

                <?php foreach ($products as $product): ?>

                    <tr>
                        <td><?= e($product['id']) ?></td>

                        <td><?= e($product['name']) ?></td>

                        <td><?= e($product['category']) ?></td>

                        <td><?= e($product['supplier']) ?></td>

                        <td>
                            Rp <?= number_format($product['price'], 0, ',', '.') ?>
                        </td>

                        <td><?= e($product['stock']) ?></td>

                        <td>
                            <a
                                href="edit.php?id=<?= e($product['id']) ?>"
                                class="btn-edit"
                            >
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?= e($product['id']) ?>"
                                class="btn-delete"
                                onclick="return confirm('Apakah kamu yakin ingin menghapus produk ini?');"
                            >
                                Hapus
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7">
                        Belum ada data produk.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>
    </table>

</div>

</body>
</html>