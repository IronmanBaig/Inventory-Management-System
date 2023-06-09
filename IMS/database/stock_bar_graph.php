<?php

include('connection.php');

$stmt = $conn->prepare("SELECT id, product_name, stock FROM products");
$stmt->execute();
$rows = $stmt->fetchAll();

$categories1 =[];
$bar_chart_data1 = [];

$colors =['#FF0000', '#00FF00'];

$counter1=0;
// Query suppliers
foreach($rows as $key => $row){
    $id = $row['id'];

    $categories1[] = $row['product_name'];
    // Query count
    // $stmt = $conn->prepare("SELECT COUNT(*) as p_count FROM productsuppliers WHERE productsuppliers.supplier = '" . $id . "'");
    // $stmt->execute();
    // $row = $stmt->fetch();

    $count1 = (int) $row['stock'];

    if($count1 >= 100) $counter1 = 1;
    else{
        $counter1 = 0;
    }
    
    $bar_chart_data1[] = [
        'y' => (int) $count1,
        'color' => $colors[$counter1]
    ];
    // $counter++;
}
