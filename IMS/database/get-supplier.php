<?php
include('connection.php');

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = $id");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// fetch products.
$stmt = $conn->prepare("
    SELECT product_name, products.id
        FROM products, productsuppliers 
        WHERE 
          productsuppliers.supplier = $id
            AND
            productsuppliers.product = products.id
    ");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row['products'] = array_column($products, 'id');
echo json_encode($row);