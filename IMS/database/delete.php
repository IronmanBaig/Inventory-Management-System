<?php
    $data = $_POST;
    $id = (int) $data['id'];
    $table = $data['table'];

    // $first_name = $data['f_name'];
    // $last_name = $data['l_name'];

    // Deleting the User
    try {
        include('connection.php');

        // delete junction table
        if($table === 'suppliers'){
            $supplier_id = $id;
            $command = "DELETE FROM productsuppliers WHERE supplier={$id}";
            $conn->exec($command);
        }

        if($table === 'products'){
            $supplier_id = $id;
            $command = "DELETE FROM productsuppliers WHERE product={$id}";
            $conn->exec($command);
        }

        // delete main table
        $command = "DELETE FROM $table WHERE id={$id}";
        $conn->exec($command);

        echo json_encode([
            'success' => true,
        ]);
    } catch (PDOException $e){
        echo json_encode ([
            'success' => false,
        ]);
    }