<?php
session_start();

$bo = false;
$post_data = $_POST;
$products = $post_data['products'];
$abc = $post_data['quantity'];
// var_dump($abc);
// die;
$qty = array_values($post_data['quantity']);

$post_data_arr = [];

// include('connection.php');

// $st = 0;
// $nm = '';
foreach($products as $key => $pid) {
    if(isset($qty[$key])) $post_data_arr[$pid] = $qty[$key];
    
    
}
// die;
// include connection

include('connection.php');

    
// store data
$batch = time();

try{
    $success = false;
    foreach($post_data_arr as $pid => $supplier_qty){
        foreach($supplier_qty as $sid => $qty){
            $st =0;
            $qt =0;
            $fst =0;
            $stmt = $conn->prepare("
            SELECT products.product_name, products.stock
                FROM products
                WHERE
                    products.id = $pid
                ");
            $stmt->execute();
            $purchase_orders = $stmt->fetch(PDO::FETCH_ASSOC);
            $st = (int) $purchase_orders['stock'];
            $nm =  $purchase_orders['product_name'];
            
            $qt = (int) $qty;
            $fst = $st - $qt;

            // checking stock
            if($st >= $qt){
                $bo = true;
                // updating stocks

                $sq = "UPDATE products 
                SET 
                    products.stock = $fst
                WHERE products.id = $pid";

                include('connection.php');

                $stmt = $conn->prepare($sq);
                $stmt->execute();
                
                // Insert to database
                $values =[
                    'supplier' => $sid,
                    'product' => $pid,
                    'quantity_ordered' => $qty,
                    'batch' => $batch,
                    'created_by' => $_SESSION['user']['id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $sql = "INSERT INTO order_productc
                                        (supplier, product, quantity_ordered, batch, created_by, updated_at, created_at) 
                                VALUES 
                                    (:supplier, :product, :quantity_ordered, :batch, :created_by, :updated_at, :created_at)";

                $stmt = $conn->prepare($sql);
                $stmt->execute($values);
            }
            else{
                $bo = false;
            }
        }
    }
    // die;
    if($bo){
        $success = true;
        $message = 'Order successfully created!';
    }
    else{
        $success = false;
        $message = 'Stock not available!';
    }
    
} catch (\Exception $e){
    
    $message = $e->getMessage();
}

$_SESSION['response'] =[
    'message' => $message,
    'success' => $success
];
header('location: ../product-orderc.php');