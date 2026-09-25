<?php

require_once 'config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$id = $_GET['id'] ?? '';

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id <= 0) {
    header('Location: index.php?error=ID produk tidak valid.');
    exit;
}

$id = (int)$id;

$stmt = $pdo->prepare("
    SELECT *
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

$categories = $pdo->query("
    SELECT id, name
    FROM categories
    ORDER BY name
")->fetchAll();

$suppliers = $pdo->query("
    SELECT id, name
    FROM suppliers
    ORDER BY name
")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $supplier_id = $_POST['supplier_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (
        $name === '' ||
        $category_id === '' ||
        $supplier_id === '' ||
        $price === '' ||
        $stock === ''
    ) {
        $error = 'Semua field wajib diisi.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Harga harus berupa angka dan tidak boleh negatif.';
    } elseif (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== '0') {
        $error = 'Stok harus berupa angka bulat.';
    } elseif ((int)$stock < 0) {
        $error = 'Stok tidak boleh negatif.';
    } else {

        try {

            $stmt = $pdo->prepare("
                UPDATE products
                SET
                    name = :name,
                    category_id = :category_id,
                    supplier_id = :supplier_id,
                    price = :price,
                    stock = :stock
                WHERE id = :id
            ");

            $stmt->execute([
                ':name' => $name,
                ':category_id' => $category_id,
                ':supplier_id' => $supplier_id,
                ':price' => $price,
                ':stock' => $stock,
                ':id' => $id
            ]);

            header('Location: index.php?success=Produk berhasil diperbarui.');
            exit;

        } catch (PDOException $e) {
            $error = 'Gagal memperbarui produk.';
        }
    }

    $product['name'] = $name;
    $product['category_id'] = $category_id;
    $product['supplier_id'] = $supplier_id;
    $product['price'] = $price;
    $product['stock'] = $stock;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk - CRUD Inventaris</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<div class="container">

    <h1>Edit Produk</h1>

    <?php if ($error !== ''): ?>

        <div class="alert error">
            <?= e($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label for="name">
                Nama Produk
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= e($product['name']) ?>"
                required
            >

        </div>

        <div class="form-group">

            <label for="category_id">
                Kategori
            </label>

            <select
                id="category_id"
                name="category_id"
                required
            >

                <option value="">
                    -- Pilih Kategori --
                </option>

                <?php foreach ($categories as $category): ?>

                    <option
                        value="<?= e($category['id']) ?>"
                        <?= ((int)$product['category_id'] === (int)$category['id']) ? 'selected' : '' ?>
                    >
                        <?= e($category['name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-group">

            <label for="supplier_id">
                Supplier
            </label>

            <select
                id="supplier_id"
                name="supplier_id"
                required
            >

                <option value="">
                    -- Pilih Supplier --
                </option>

                <?php foreach ($suppliers as $supplier): ?>

                    <option
                        value="<?= e($supplier['id']) ?>"
                        <?= ((int)$product['supplier_id'] === (int)$supplier['id']) ? 'selected' : '' ?>
                    >
                        <?= e($supplier['name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-group">

            <label for="price">
                Harga
            </label>

            <input
                type="number"
                id="price"
                name="price"
                min="0"
                step="0.01"
                value="<?= e($product['price']) ?>"
                required
            >

        </div>

        <div class="form-group">

            <label for="stock">
                Stok
            </label>

            <input
                type="number"
                id="stock"
                name="stock"
                min="0"
                value="<?= e($product['stock']) ?>"
                required
            >

        </div>

        <div class="form-actions">

            <button
                type="submit"
                class="btn"
            >
                Simpan Perubahan
            </button>

            <a
                href="index.php"
                class="btn-cancel"
            >
                Batal
            </a>

        </div>

    </form>

</div>

</body>
</html>