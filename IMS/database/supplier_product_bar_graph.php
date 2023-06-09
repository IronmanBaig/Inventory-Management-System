<?php

include('connection.php');

$stmt = $conn->prepare("SELECT id, supplier_name FROM suppliers");
$stmt->execute();
$rows = $stmt->fetchAll();

$categories =[];
$bar_chart_data = [];

$colors =['#FF0000', '#0000FF', 'ADD8E6', '#00FF00', '#FFA500', '#800000'];

$counter=0;
// Query suppliers
foreach($rows as $key => $row){
    $id = $row['id'];

    $categories[] = $row['supplier_name'];
    // Query count
    $stmt = $conn->prepare("SELECT COUNT(*) as p_count FROM productsuppliers WHERE productsuppliers.supplier = '" . $id . "'");
    $stmt->execute();
    $row = $stmt->fetch();

    $count = $row['p_count'];

    if(!isset($colors[$key])) $counter = 0;
    $bar_chart_data[] = [
        'y' => (int) $count,
        'color' => $colors[$counter]
    ];
    $counter++;
}