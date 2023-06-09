<?php
    require('fpdf/fpdf.php');

class PDF extends FPDF{
    function __construct(){
        parent::__construct('L');
    }

    // Colored table
    function FancyTable($headers, $data, $row_height = 30)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');

        $width_sum = 0;
        foreach($headers as $header_key => $header_data){
            $this->Cell($header_data['width'], 7, $header_key, 1, 0, 'C', true);
            $width_sum += $header_data['width'];
        }
        $this->Ln();
        // Color and font restoration
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        $img_pos_y = 40;
        $header_keys = array_keys($headers);
        foreach($data as $row){
            foreach($header_keys as $header_key){
                $content = $row[$header_key]['content'];
                $width = $headers[$header_key]['width'];
                $align = $row[$header_key]['align'];

                if($header_key == 'image')
                    $content = is_null($content) || $content == "" ? 'No Image' : $this->Image('.././uploads/products/' . $content, 45, $img_pos_y, 35,25);

                $this->Cell($width, $row_height, $content,'LRBT',0, $align);
            }
            
            $this->Ln();
            $img_pos_y += 30;
        }

        // Closing line
        $this->Cell($width_sum,0,'','T');
    }
}

    $type = $_GET['report'];
    $report_headers = [
        'product' => 'Product Reports',
        'supplier' => 'Supplier Report',
        'delivery' => 'Delivery Report',
        'purchase_orders' => 'Purchase Order Report'
    ];

    $row_height = 30;

    // pull data from database
    include('connection.php');

    // Product Export
    if($type == 'product'){
        // Column headings - replace from mysql database 
        $header = [
            'id' => [
                'width' => 15
            ],
            'image' => [
                'width' => 70
            ],
            'product_name' => [
                'width' => 35
            ],
            'stock' => [
                'width' => 15
            ],
            'created_by' => [
                'width' => 45
            ],
            'created_at' => [
                'width' => 45
            ],
            'updated_at' => [
                'width' => 45
            ]
        ];


        // load product

        $stmt = $conn->prepare("SELECT *, products.id as pid  FROM products 
                INNER JOIN 
                    users ON 
                    products.created_by = users.id 
                    ORDER BY 
                    products.created_at DESC");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $products = $stmt->fetchAll();

        $data = [];
        foreach($products as $product){
            $product['created_by'] = $product['first_name'] . ' ' . $product['last_name'];
            unset($product['first_name'], $product['last_name'], $product['password'], $product['email']);

            // detect double quotes and escape any value that contains them
            array_walk($product, function(&$str){
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            });

            $data[] = [
                'id' => [
                    'content' => $product['pid'],
                    'align' => 'C'
                ],
                'image' => [
                    'content' => $product['img'],
                    'align' => 'C'
                ],
                'product_name' => [
                    'content' => $product['product_name'],
                    'align' => 'C'
                ],
                'stock' => [
                    'content' => $product['stock'],
                    'align' => 'C'
                ],
                'created_by' => [
                    'content' => $product['created_by'],
                    'align' => 'L'
                ],
                'created_at' => [
                    'content' => date('M d,Y h:i:s A', strtotime($product['created_at'])),
                    'align' => 'L'
                ],
                'updated_at' => [
                    'content' => date('M d,Y h:i:s A', strtotime($product['updated_at'])),
                    'align' => 'L'
                ]
            ];
        }
    }

    // Supplier Export
    if($type === 'supplier'){
        $stmt = $conn->prepare("SELECT suppliers.id as sid, suppliers.created_at as created_at, users.first_name, users.last_name, suppliers.supplier_location, suppliers.email, suppliers.created_by FROM suppliers INNER JOIN users ON suppliers.created_by = users.id ORDER BY suppliers.created_at DESC");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $suppliers = $stmt->fetchAll();

        // Column headings - replace from mysql database 
        $header = [
            'supplier_id' => [
                'width' => 30
            ],
            'created_at' => [
                'width' => 70
            ],
            'supplier_location' => [
                'width' => 60
            ],
            'email' => [
                'width' => 60
            ],
            'created_by' => [
                'width' => 60
            ]
        ];


        foreach($suppliers as $supplier){
            $supplier['created_by'] = $supplier['first_name'] . ' ' . $supplier['last_name'];

            $data[] = [
                'supplier_id' => [
                    'content' => $supplier['sid'],
                    'align' => 'C'
                ],
                'created_at' => [
                    'content' => $supplier['created_at'],
                    'align' => 'C'
                ],
                'supplier_location' => [
                    'content' => $supplier['supplier_location'],
                    'align' => 'C'
                ],
                'email' => [
                    'content' => $supplier['email'],
                    'align' => 'C'
                ],
                'created_by' => [
                    'content' => $supplier['created_by'],
                    'align' => 'L'
                ]
            ];
        }

        $row_height = 10;
    }

    // Delivery Export
    if($type === 'delivery'){
        $stmt = $conn->prepare("SELECT date_received, qty_received, first_name, last_name, products.product_name, supplier_name, batch
                FROM order_product_history, order_product, users, suppliers, products
                WHERE
                    order_product_history.order_product_id = order_product.id
                AND
                    order_product.created_by = users.id
                AND
                    order_product.supplier = suppliers.id
                AND
                    order_product.product = products.id
                ORDER BY order_product.batch DESC
            ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $deliveries = $stmt->fetchAll();

        $header = [
            'date_received' => [
                'width' => 40
            ],
            'qty_received' => [
                'width' => 30
            ],
            'product_name' => [
                'width' => 50
            ],
            'supplier_name' => [
                'width' => 60
            ],
            'batch' => [
                'width' => 35
            ],
            'created_by' => [
                'width' => 60
            ]
        ];
        

        foreach($deliveries as $delivery){
            $delivery['created_by'] = $delivery['first_name'] . ' ' . $delivery['last_name'];
            $data[] = [
                'date_received' => [
                    'content' => $delivery['date_received'],
                    'align' => 'C'
                ],
                'qty_received' => [
                    'content' => $delivery['qty_received'],
                    'align' => 'C'
                ],
                'product_name' => [
                    'content' => $delivery['product_name'],
                    'align' => 'C'
                ],
                'supplier_name' => [
                    'content' => $delivery['supplier_name'],
                    'align' => 'C'
                ],
                'batch' => [
                    'content' => $delivery['batch'],
                    'align' => 'C'
                ],
                'created_by' => [
                    'content' => $delivery['created_by'],
                    'align' => 'C'
                ]
            ];
        }

        $row_height =10;
    }

    // purchase orders export
    if($type === 'purchase_orders'){
        $stmt = $conn->prepare("SELECT products.product_name, order_product.id, order_product.quantity_ordered, order_product.quantity_received, order_product.quantity_remaining, order_product.status, order_product.batch, users.first_name, users.last_name, suppliers.supplier_name, order_product.created_at as 'created at' 
                FROM order_product
                INNER JOIN users ON order_product.created_by = users.id
                INNER JOIN suppliers ON order_product.supplier = suppliers.id
                INNER JOIN products ON order_product.product = products.id
                ORDER BY order_product.batch DESC
            ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $order_products = $stmt->fetchAll();

        $header = [
            'id' => [
                'width' => 15
            ],
            'qty_ordered' => [
                'width' => 25
            ],
            'qty_received' => [
                'width' => 25
            ],
            'qty_remaining' => [
                'width' => 25
            ],
            'status' => [
                'width' => 25
            ],
            'batch' => [
                'width' => 25
            ],
            'supplier_name' => [
                'width' => 30
            ],
            'product_name' => [
                'width' => 30
            ],
            'created at' => [
                'width' => 40
            ],
            'created_by' => [
                'width' => 40
            ]
        ];


        foreach($order_products as $order_product){
            $order_product['created_by'] = $order_product['first_name'] . ' ' . $order_product['last_name'];
            $data[] = [
                'id' => [
                    'content' => $order_product['id'],
                    'align' => 'C'
                ],
                'qty_ordered' => [
                    'content' => $order_product['quantity_ordered'],
                    'align' => 'C'
                ],
                'qty_received' => [
                    'content' => $order_product['quantity_received'],
                    'align' => 'C'
                ],
                'qty_remaining' => [
                    'content' => $order_product['quantity_remaining'],
                    'align' => 'C'
                ],
                'status' => [
                    'content' => $order_product['status'],
                    'align' => 'C'
                ],
                'batch' => [
                    'content' => $order_product['batch'],
                    'align' => 'C'
                ],
                'supplier_name' => [
                    'content' => $order_product['supplier_name'],
                    'align' => 'C'
                ],
                'product_name' => [
                    'content' => $order_product['product_name'],
                    'align' => 'C'
                ],
                'created at' => [
                    'content' => $order_product['created at'],
                    'align' => 'C'
                ],
                'created_by' => [
                    'content' => $order_product['created_by'],
                    'align' => 'C'
                ]
            ];
        }

        $row_height = 10;
    }

    // start PDF
    $pdf = new PDF();

    $pdf->SetFont('Arial','',20);
    $pdf->AddPage();

    $pdf->Cell(75);
    $pdf->Cell(100,10, $report_headers[$type],0,0,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->FancyTable($header,$data, $row_height);
    $pdf->Output();