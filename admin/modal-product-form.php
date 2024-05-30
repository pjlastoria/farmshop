<?php 

// showing correct messages after form actions with classes

$show_product_form = ''; //defaults
$delete_msg = '';
$update_msg = '';
$modal = '';
$msg = '';

if(!empty($_GET)) {

  $modal = ' appear';

  if(isset($_GET['error']) && !empty($_GET['error'])) {//error=emptyproductInput, invalidName, invalidTotal, stmtfailed

    $update_msg = ' active-msg';

    if($_GET['error'] == 'stmtfailed')        { $msg = 'Sorry, something went wrong on the server.'; }
    if($_GET['error'] == 'emptyProductInput') { $msg = 'You must fill out all fields.'; }
    if($_GET['error'] == 'invalidName')       { $msg = 'Product name can only have letters, spaces, or dashes.'; }
    if($_GET['error'] == 'invalidPrice')      { $msg = 'Price must be in xx.xx format and less than $100.'; }
    if($_GET['error'] == 'invalidCode')       { $msg = 'The product code must be 6 digits.'; }
    if($_GET['error'] == 'codeTaken')         { $msg = 'Product code is taken, please enter a different one.'; }
    if($_GET['error'] == 'invalidImageId')    { $msg = 'Image id must only include letters, dashes, and underscores.'; }
    if($_GET['error'] == 'imageNotFound')     { $msg = 'Enter a valid photo id from unsplash.com.'; }

  } 

  if(isset($_GET['result']) && !empty($_GET['result'])) {//result=product_added, product_updated, product_deleted

    if($_GET['result'] == 'product_added') { 
      $msg = 'Product added.'; 
      $update_msg = ' active-msg success';
    }
    if($_GET['result'] == 'product_updated') { 
      $msg = 'Product updated.';
      $update_msg = ' active-msg success';
    }

    if($_GET['result'] == 'product_deleted') { 
      $delete_msg = ' active-msg';
      $show_product_form = ' hide';
      $msg = 'Product deleted.'; 
    }

  } 

}

?>

<div class="modal-bg<?php echo $modal; ?>">

  <div class="product-form-cont modal<?php echo $show_product_form; ?>">
    <form action="../includes/product.inc.php" method="post">
      <input type="hidden" name="id" value="">
      <div class="product-comp info">
        <div class="product-form-msg<?php echo $update_msg; ?>">
          <?php echo $msg; ?><!-- message also configured in crud.js success -->
        </div>
        <div class="billing-section">
          <div class="product-form">
            <div class="input-comp">
              <label for="product_name">Product Name</label><br>
              <input type="text" name="product_name" placeholder="Enter Product Name" maxlength="70" required>
            </div>
            <div class="input-comp">
              <label for="product_price">Price </label><br>
              <input type="text" name="product_price" placeholder="Enter Price" required>
              <span id="dollar-sign">$</span>
            </div>
          </div>
        </div>

        <div class="billing-section">
          <div class="product-form">
            <div class="input-comp">
              <label for="product_code">Product Code</label><br>
              <input type="text" name="product_code" placeholder="Enter Product Code" maxlength="7" required>
            </div>
            <div class="input-comp">
              <label for="product_image">Image </label><br>
              <input type="text" name="product_image" placeholder="Enter Unplash Image Id" required>
            </div>
          </div>
        </div>

        <div class="billing-section">
          <div class="product-form">

            <div class="input-comp category">
              <label for="product_category">Category</label><br>
                <select name="product_category" required>
                  <option value="">Select</option>
                  <option value="produce">Produce</option>
                  <option value="grocery">Grocery</option>
                </select>
            </div>
            <div class="input-comp modal-add-btn">
              <label for="product"></label><br>
              <input id="add-product" class="admin-btn" type="submit" name="product" value="Add product">
            </div>

          </div>
        </div>
      </div> 
    </form>
  </div>

  <div class="delete-product-form modal">
    Are you sure you want to delete product <span id="delete-product-id"></span> ?
    <form action="../includes/product.inc.php" method="post">
      <input id="delete-id" type="hidden" name="id" value="">
      <input type="hidden" name="delete" value="1">
      <div class="list-footer">
        <button class="product-btn" id="save-product">No</button>
        <input class="product-btn" type="submit" name="delete-product" value="Delete product">
      </div>
    </form>
  </div>

  <div id="delete-msg" class="delete-product-form msg-cont modal<?php echo $delete_msg; ?>">
    <p class="success"><?php echo $msg; ?></p>
  </div>

</div>