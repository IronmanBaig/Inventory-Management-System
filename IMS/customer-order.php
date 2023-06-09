<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: admin.php');

    $show_table = 'suppliers';
    $suppliers = include('database/show.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>View Purchase Orders - IMS</title>
    <?php include('partials/app-header-scripts.php'); ?>
  </head>
  <body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sidebar-admin.php')?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topnav.php')?>
            <div class="dasboard_content">
              <div class="dasboard_content_main">
                <div class="row">
                    <div class="column column-12">
                      <h1 class="section_header"><i class="fa fa-list"></i> Customer Orders</h1>
                      <div class="section_content">
                        <div class="poListContainers">
                            <?php
                                $stmt = $conn->prepare("
                                SELECT order_productc.id, order_productc.product, products.product_name, products.price, order_productc.quantity_ordered, order_productc.batch, suppliers.supplier_name, order_productc.created_at, order_productc.created_by
                                    FROM order_productc, suppliers, products, customer
                                    WHERE
                                        order_productc.supplier = suppliers.id
                                            AND
                                        order_productc.product = products.id
                                            AND
                                        order_productc.created_by = customer.id
                                    ORDER BY
                                        order_productc.created_at DESC
                                    ");
                                $stmt->execute();
                                $purchase_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $user = $_SESSION['user'];
                                $data = [];
                                foreach($purchase_orders as $purchase_order){
                                        $data[$purchase_order['batch']][] = $purchase_order;
                                }
                            ?>

                            <?php
                                $up = true;
                                foreach($data as $batch_id => $batch_pos){
                            ?>
                            <div class="poList" id="container-<?= $batch_id ?>">
                                <p>Batch No #: <?= $batch_id ?> </p>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity Ordered</th>
                                            <th>Supplier</th>
                                            <!-- <th>Ordered By</th> -->
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Grandtotal =0;
                                            foreach($batch_pos as $index => $batch_po){
                                                // $pr = (int) $batch_po['price'];
                                                // $qt = (int) $batch_po['quantity_ordered'];
                                                $total = 0;
                                                $Grandtotal += $batch_po['quantity_ordered'] * $batch_po['price'];
                                                $total = $batch_po['quantity_ordered'] * $batch_po['price'];
                                        ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="po_product"><?= $batch_po['product_name'] ?></td>
                                            <td class="po_price"><?= $batch_po['price'] ?></td>
                                            <td class="po_qty_ordered"><?= $batch_po['quantity_ordered'] ?></td>
                                            <td class="po_qty_supplier"><?= $batch_po['supplier_name'] ?></td>
                                            <!-- <td><?= $batch_po['first_name'] . ' ' . $batch_po['last_name'] ?></td> -->
                                            <td class="po_total"><?= $total ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="poOrderUpdateBtnContainer alignRight">
                                    <p style="color: #000;"><?= 'Grand Total : $ ' . '<span style="color: #000;">'. $Grandtotal .'</span>' ?></p>
                                    <!-- <?php 
                                        if($up){
                                            
                                    ?>
                                    <a href="confirm.php" class="updatePoBtn" >Confirm Order</a>
                                    <button type ="submit" class="updatePoBtn">Confirm Order</button>
                                    <button class="updatePoBtn" data-pid="<?= $batch_id ?>">Confirm Order</button>
                                    <?php } ?> -->
                                </div>
                            </div>

                            <?php $up = false; } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
    </div>

<?php include('partials/app-scripts.php'); ?>
<script>

    function script(){

        var vm = this;
        this.registerEvents = function(){

          document.addEventListener('click', function(e){
            targetElement = e.target;
            classList = targetElement.classList;

            if(classList.contains('updatePoBtn')){
                  e.preventDefault();
                  
                  batchNumber = targetElement.dataset.id;
                  batchNumberContainer = 'container-' + batchNumber;

                //   get all purchase order product records

                //   productList = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_product');
                //   qtyOrderedList = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_qty_ordered');
                //   qtyReceivedList = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_qty_received');
                //   supplierList = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_qty_supplier');
                //   statusList = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_qty_status');
                //   rowIds = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_qty_row_id');
                //   pIds = document.querySelectorAll('#' + batchNumberContainer  +  ' .po_qty_productid');
                  
                //   poListsArr = [];

                //   for(i=0;i<productList.length;i++){
                //     poListsArr.push({
                //         name: productList[i].innerText,
                //         qtyOrdered: qtyOrderedList[i].innerText,
                //         qtyReceived: qtyReceivedList[i].innerText,
                //         supplier: supplierList[i].innerText,
                //         status: statusList[i].innerText,
                //         id: rowIds[i].value,
                //         pid: pIds[i].value
                //     });
                //   }

                // //   Store in Html
                  var poListHtml = 'Order Placed Successfully';

                //   poListsArr.forEach((poList) => {
                //         poListHtml += '\
                //             <tr>\
                //                 <td class="po_product alignLeft">'+ poList.name +'</td>\
                //                 <td class="po_qty_ordered">'+ poList.qtyOrdered +'</td>\
                //                 <td class="po_qty_received">'+ poList.qtyReceived +'</td>\
                //                 <td class="po_qty_delivered"><input class ="qtyin" type="number" value="0" /></td>\
                //                 <td class="po_qty_supplier alignLeft">'+ poList.supplier +'</td>\
                //                 <td>\
                //                     <select class="po_qty_status">\
                //                         <option value="pending" '+ (poList.status == 'pending' ? 'selected' : '') +'>pending</option>\
                //                         <option value="incomplete" '+ (poList.status == 'incomplete' ? 'selected' : '') +'>incomplete</option>\
                //                         <option value="complete" '+ (poList.status == 'complete' ? 'selected' : '') +'>complete</option>\
                //                     </select>\
                //                     <input type="hidden" class="po_qty_row_id" value="'+ poList.id +'">\
                //                     <input type="hidden" class="po_qty_pid" value="'+ poList.pid +'">\
                //                 </td>\
                //             </tr>\
                //         ';
                //   });
                //   poListHtml += '</tbody></table>';

                  pName = targetElement.dataset.name;

                  BootstrapDialog.alert({
                    type: BootstrapDialog.TYPE_PRIMARY,
                    title: 'Order',
                    message: poListHtml,
                    
                  });
            }

          });
        },


        this.initialize = function(){
            this.registerEvents();
        }
    }
    var script = new script;
    script.initialize();
</script>
</body>
</html>
