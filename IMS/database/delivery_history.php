<?php

include('connection.php');

$stmt = $conn->prepare("SELECT qty_received, date_received FROM order_product_history ORDER BY date_received ASC");
$stmt->execute();
$rows = $stmt->fetchAll();

$line_data = [];
foreach($rows as $row){
    $key = date('Y-m-d', strtotime($row['date_received']));

    $line_data[$key] = isset($line_data[$key]) ? $line_data[$key] + (int) $row['qty_received'] : (int) $row['qty_received'];
}

$line_categories = array_keys($line_data);
$line_data = array_values($line_data);