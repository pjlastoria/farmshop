<?php 

// showing correct messages after form actions with classes

$show_order_form = ''; //defaults
$delete_msg = '';
$update_msg = '';
$modal = '';
$msg = '';


if(!empty($_GET)) {

  $modal = ' appear';

  if(isset($_GET['error']) && !empty($_GET['error'])) {//error=emptyOrderInput, invalidName, invalidTotal, stmtfailed

    $update_msg = ' active-msg';

    if($_GET['error'] == 'stmtfailed')      { $msg = 'Sorry, something went wrong on the server.'; }
    if($_GET['error'] == 'emptyOrderInput') { $msg = 'You must fill out all fields.'; }
    if($_GET['error'] == 'invalidName')     { $msg = 'Name can only have letters, spaces, dashes, dots, or apostrophes.'; }
    if($_GET['error'] == 'invalidTotal')    { $msg = 'Total must be in xxx.xx format and less than $1,000.'; }

  } 

  if(isset($_GET['result']) && !empty($_GET['result'])) {//result=order_added, order_updated, order_deleted

    

    if($_GET['result'] == 'order_added') { 
      $msg = 'Order added.'; 
      $update_msg = ' active-msg success';
    }
    if($_GET['result'] == 'order_updated') { 
      $msg = 'Order updated.';
      $update_msg = ' active-msg success';
    }

    if($_GET['result'] == 'order_deleted') { 
      $delete_msg = ' active-msg';
      $show_order_form = ' hide';
      $msg = 'Order deleted.'; 
    }

  } 

}

?>

<div class="modal-bg<?php echo $modal; ?>">

  <div class="order-form-cont modal<?php echo $show_order_form; ?>">
    <form action="../includes/order.inc.php" method="post">
      <input type="hidden" name="id" value="">
      <div class="order-comp">
        <div class="order-form-msg<?php echo $update_msg; ?>">
          <?php echo $msg; ?><!-- message also configured in crud.js success -->
        </div>
        <div class="order-input-row">
          <div class="order-form">
            <div class="input-comp">
              <label for="customer_name">Customer Name</label><br>
              <input type="text" name="customer_name" placeholder="Enter Full Name" maxlength="70" required>
            </div>
            <div class="input-comp">
              <label for="order_total">Total </label><br>
              <input type="text" name="order_total" placeholder="Enter Amount" required>
              <span id="order-dollar-sign">$</span>
            </div>
          </div>
        </div>

        <div class="order-input-row">
            
          <div class="order-form">
            <div class="product-select-wrapper">
              <div class="input-comp">
                <label for="product-select">Product</label>
                <select name="product-select" id="product-select">
                  <option value="">Select</option>
                  <?php get_product_options(); ?>
                    
                </select>
              </div>
              <div class="input-comp qty-cont">
                <label for="product_quantity">Quantity </label><br>
                <input type="number" name="product_quantity" id="qty-input" placeholder="Qty" size="1">
              </div>
              
                <button class="add-product" id="add-item">+</button>
              
            </div>

            <div id="items-cont">
              <p id="items-label">Items</p>
              <div id="item-wrapper">
                <div id="items-list">
                  <input type="hidden" id="cart" name="item_list">
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="order-input-row"> 
          <div class="order-form">

            <div class="input-comp">
              <label for="payment_method">Payment Method</label>
              <div class="payment_method">
                <select name="payment_method" required>
                  <option value="">Select</option>
                  <option value="in_store">In Store</option>
                  <option value="paypal">Paypal</option>
                  <option value="stripe">Stripe</option>
                </select>
                <input id="add-order" class="admin-btn" type="submit" name="order" value="Add Order">
              </div>
            </div>
            

          </div>
        </div>
      </div> 
    </form>
  </div>

  <div class="delete-order-form modal">
    Are you sure you want to delete order <span id="delete-order-id"></span> ?
    <form action="../includes/order.inc.php" method="post">
      <input id="delete-id" type="hidden" name="id" value="">
      <input type="hidden" name="delete" value="1">
      <div class="list-footer">
        <button class="order-btn" id="save-order">No</button>
        <input class="order-btn" type="submit" name="delete-order" value="Delete Order">
      </div>
    </form>
  </div>

  <div id="delete-msg" class="delete-order-form msg-cont modal<?php echo $delete_msg; ?>">
    <p class="success"><?php echo $msg; ?></p>
  </div>

</div>