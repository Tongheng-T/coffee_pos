<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}




if (isset($_POST['btnsaveorder'])) {

  $orderdate     = date('Y-m-d');
  $subtotal      = $_POST['txtsubtotal'];
  $discount      = $_POST['txtdiscount'];
  $discountp      = $_POST['txtdiscountp'];

  $total         = $_POST['txttotal'];
  $payment_type  = $_POST['rb'];
  $due           = $_POST['txtdue'];
  $paid          = $_POST['txtpaid'];

  /////

  $arr_pid     = $_POST['pid_arr'];

  $arr_name    = $_POST['product_arr'];

  $arr_qty     = $_POST['quantity_arr'];
  $arr_price   = $_POST['price_c_arr'];
  $arr_total   = $_POST['saleprice_arr'];

  $saler_name = $_SESSION['userid'];
  $insert = query("INSERT into tbl_invoice (order_date,subtotal,discount,discountp,total,payment_type,due,paid,saler_name) values('{$orderdate}','{$subtotal}','{$discount}','{$discountp}','{$total}','{$payment_type}','{$due}','{$paid}','{$saler_name}') ");
  confirm($insert);
  $invoice_id = last_id();

  if ($invoice_id != null) {
    for ($i = 0; $i < count($arr_pid); $i++) {

      $insert = query("INSERT into tbl_invoice_details (invoice_id,product_id,product_name,qty,rate,saleprice,order_date,saler_id) values ('{$invoice_id}','{$arr_pid[$i]}','{$arr_name[$i]}','{$arr_qty[$i]}','{$arr_price[$i]}','{$arr_total[$i]}','{$orderdate}','{$saler_name}')");
      confirm($insert);
    }

    redirect('showReceipt.php?id='.$invoice_id.'');
  } //1st if end



  //var_dump($arr_total);

}


ob_end_flush();



$select = query("SELECT * from tbl_taxdis where taxdis_id =1");
confirm($select);
$row = $select->fetch_object();



?>


<style type="text/css">
  .tableFixHead {
    overflow: scroll;
    height: 520px;
  }

  .tableFixHead thead th {
    position: sticky;
    top: 0;
    z-index: 1;
  }

  table {
    border-collapse: collapse;
    width: 100px;
  }

  th,
  td {
    padding: 8px 16px;
  }

  th {
    background: #eee;
  }

  a {
    color: #343a40;
  }

  a:hover {
    text-decoration: none;
    color: #343a40;
  }

  .tableFixHead {
    height: 410px;
    overflow-x: hidden;

  }
</style>





<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">


        <div class="card card-primary card-outline">
          <div class="card-header">
            <h4 class="m-0">Cashier</h4>
            <!-- <img width="50px" src="../ui/logo/cashier.svg" /> -->
          </div>



          <div class="card-body">


            <div class="row">

              <div class="col-md-5">

                <!-- <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                  </div>
                  <input type="text" class="form-control" placeholder="Scan Barcode" autocomplete="off" name="txtbarcode" id="txtbarcode_id">
                </div> -->


                <form action="" method="post" name="">

                  </br>
                  <div class="tableFixHead">


                    <table id="producttable" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>price </th>
                          <th>QTY </th>
                          <th>Total </th>
                          <th>Del </th>
                        </tr>

                      </thead>


                      <tbody class="details" id="itemtable">
                        <tr data-widget="expandable-table" aria-expanded="false">

                        </tr>
                      </tbody>
                    </table>

                  </div>
                  <hr>

                  <h3>Total Amount:</strong> <span id="txttotal">0</span> ៛</h3>
                  <div><button type="button" class="btn btn-success btn-block btn-payment" data-toggle="modal" data-target="#exampleModal">Payment</button></div>



              </div>


              <div class="col-md-7">
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <?php
                    $select = query("SELECT * from tbl_category");
                    confirm($select);
                    foreach ($select as $roww) {
                      echo ' <a class="nav-item nav-link" data-id="' . $roww["catid"] . '" data-toggle="tab"> ' . $roww["category"] . '</a>';
                    }
                    ?>

                  </div>
                </nav>
                <div id="list-menu" class="row mt-2"></div>

              </div>


              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h3 class="totalAmount"></h3>
                      <h3 class="changeAmount"></h3>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">SUBTOTAL(KHR) </span>
                          </div>
                          <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal_id" readonly>
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa" style="font-size: 24px;">៛</i></span>
                          </div>
                        </div>


                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">DISCOUNT(%)</span>
                          </div>
                          <input type="text" class="form-control" name="txtdiscountp" id="txtdiscount_p" value=" <?php echo $row->discount ?> "readonly>
                          <div class="input-group-append">
                            <span class="input-group-text">%</span>
                          </div>
                        </div>


                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">DISCOUNT(KHR)</span>
                          </div>
                          <input type="text" class="form-control" name="txtdiscount" id="txtdiscount_n" readonly>
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa" style="font-size: 24px;">៛</i></span>
                          </div>
                        </div>

                        <hr style="height:2px; border-width:0; color:black; background-color:black;">

                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">TOTAL(KHR)</span>
                          </div>
                          <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall" readonly>
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa" style="font-size: 24px;">៛</i></span>
                          </div>
                        </div>

                        <hr style="height:2px; border-width:0; color:black; background-color:black;">

                        <div class="icheck-success d-inline">
                          <input type="radio" name="rb" value="Cash" checked id="radioSuccess1">
                          <label for="radioSuccess1">
                            CASH
                          </label>
                        </div>
                        <div class="icheck-primary d-inline">
                          <input type="radio" name="rb" value="Card" id="radioSuccess2">
                          <label for="radioSuccess2">
                            QR <i class="fas fa-qrcode"></i>
                          </label>
                        </div>
                        <!-- <div class="icheck-danger d-inline">
                          <input type="radio" name="rb" value="Check" id="radioSuccess3">
                          <label for="radioSuccess3">
                            CHECK
                          </label>
                        </div> -->
                        <hr style="height:2px; border-width:0; color:black; background-color:black;">


                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">Change(KHR)</span>
                          </div>
                          <input type="text" class="form-control" name="txtdue" id="txtdue" readonly>
                          <div class="input-group-append">
                            <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                          </div>
                        </div>

                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">Paid Amount(KHR)</span>
                          </div>
                          <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                          <div class="input-group-append">
                            <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                          </div>
                        </div>
                     
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btn-save-payment" name="btnsaveorder" disabled>Save Payment</button>
                    </div>
                  </div>
                </div>




              </div>
            </div>



          </div>


        </div>

        </form>

      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div><!-- /.content-fluid -->
</div>