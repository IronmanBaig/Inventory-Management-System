<?php
    $purchase_orders = $_POST['payload'];
    include('connection.php');

    try{
        foreach($purchase_orders as $po){
            $delivery = (int) $po['qtyDelivered'];

            // we don't save the data if zero received
            if($delivery > 0){
                $cur_qty_received = (int) $po['qtyReceive'];
                $status = $po['status'];
                $row_id = $po['id'];
                $qty_ordered = (int) $po['qtyOrdered'];
                $product_id = (int) $po['pid'];

                // Update quantity received

                $updated_qty_received = $cur_qty_received + $delivery;
                $qty_remaining = $qty_ordered - $updated_qty_received;
        
                $sql = "UPDATE order_product 
                            SET 
                            quantity_received=?, status=?, quantity_remaining=?
                            WHERE id=?";
        
                $stmt = $conn->prepare($sql);
                $stmt->execute([$updated_qty_received, $status, $qty_remaining, $row_id]);


                // Insert script adding to the order_product_history
                $delivery_history = [
                    'order_product_id' => $row_id,
                    'qty_received' => $delivery,
                    'date_received' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s')
                ];

                $sql = "INSERT INTO order_product_history
                                        (order_product_id, qty_received, date_received, date_updated) 
                                VALUES 
                                    (:order_product_id, :qty_received, :date_received, :date_updated)";

                $stmt = $conn->prepare($sql);
                $stmt->execute($delivery_history);

                // script for updating main product quantity
                // select statement to pull the current quantity of product.
                
                $stmt = $conn->prepare("
                SELECT products.stock FROM products
                    WHERE
                        id = $product_id
                    ");
                $stmt->execute();
                $product = $stmt->fetch();

                $cur_stock = (int) $product['stock'];
                $updated_stock = $cur_stock + $delivery;
                // update statement to add the delivered product to the cur quantity

                $sql = "UPDATE products 
                            SET 
                                stock=?
                            WHERE id=?";

                $stmt = $conn->prepare($sql);
                $stmt->execute([$updated_stock, $product_id]);
            }
        }

        $response = [
            'success' => true,
            'message' => "Purchase order successfully updated!"
        ];


} catch (\Exception $e){
        $response = [
            'success' => false,
            'message' => "Error processing your request"
        ];
}

echo json_encode($response);