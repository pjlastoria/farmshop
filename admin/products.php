<?php 
include("header.php"); 
include("../includes/functions.inc.php");
include("product-data.php"); 
include("modal-product-form.php"); 

$sorted_products = get_sorted_products()[0];
$sales_graph_data = get_sorted_products()[1];
$_SESSION['sales-report'] = $sales_graph_data;

$default_3 = array("No Product Sales" => 0.00);//DEFAULT
$top_3 = ($sorted_products) ? array_splice($sorted_products, 0,3) : $default_3;
$top_rankings = array( 'first', 'second', 'third' );
$rank_ind = 0; 

function top_selling_products($products) {

    $rank = 4;
    foreach($products as $product => $amount){
        
        $amount = number_format($amount, 2);
        $product = <<<EOT
            <div class="product" data-name="{$product}">
                <p class="product-rank">{$rank}</p>
                <p class="product-title">{$product}</p>
                <p class="product-revenue">&#36;{$amount}</p>
            </div>
        EOT;
        $rank++;
        echo $product;
        
    }

}

?>

<div id="admin-main">
    <h1 id="admin-title">Best Selling Products</h1>
    <div id="main-flex">
        <div id="main-left-wrapper"> 
            <div class="row-3">

            <?php foreach($top_3 as $product_name => $total_revenue) { ?>

                <div class="top-left-main component" data-name="<?= $product_name ?>">
                    <div class="graph-tab">

                        <img src="../images/<?= $top_rankings[$rank_ind]; ?>-place.svg" alt="<?= $top_rankings[$rank_ind]; ?>-place" class="graph-tab-arrow">
                        <ul class="graph-tab-text">
                            <li id="revenue" class="graph-tab-name"><?= $product_name ?></li>
                            <li id="revenue-amount" class="graph-tab-amount">$<?= number_format($total_revenue, 2); ?></li>
                        </ul>
                        <p id="revenue-difference" class="graph-tab-difference">+$<?= number_format($total_revenue, 2); ?> From Last Week</p>

                    </div>
                </div>

            <?php $rank_ind++; } ?>

            </div>
            <h3 id="graph-title"><?= key($top_3); ?></h3>
            <div class="graph-cont component">
                <?php include("bar-graph.php"); ?>
            </div>

        </div>
        <div id="main-right-wrapper">
            <h3 id="main-right-title">Less than the Best</h3>
            <div class="top-products-list component">
                <div class="product-listing">
                    <div class="products-header product">
                        <p class="product-rank">Rank</p>
                        <p class="product-title">Name</p>
                        <p class="product-revenue">Revenue</p>
                    </div>
                    <div class="products-cont">
                        <?php top_selling_products($sorted_products); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="title-cont">
        <h3 id="products-title" class="order-title">All Products</h3>
        <button class="add-product-btn product-btn small-screen-btn">Add New Product</button>
    </div>
    <div class="products-wrapper component">
        <div id="products-cont">
            <div class="product-header table-product">
                <p class="product-image">Image</p>
                <p class="product-name">Product</p>
                <p class="product-price">Price</p>
                <p class="product-date">Date</p>
                <p class="product-category">Category</p>
                <p class="product-id">ID</p>
                <p class="product-action">Edit/Delete</p>
            </div>
            <div id="products-list">
                <?php $product_count = get_products(); ?>
            </div>
        </div>
        <div class="list-footer">
            <button class="add-product-btn product-btn wide-screen-btn">Add New Product</button>
            <div class="pagination-cont">
                
                <div id="showing">
                    <span>Showing <span id="first-ele">1</span> to <span id="last-ele"><?php echo ($product_count > 8) ? 8 : $product_count; ?></span> of <span id="products-count"><?php echo $product_count ?></span> Results</span>
                </div>
                <div class="page-btns">
                    <a href="#">&lsaquo;</a>
                    <a class="active-page" href="#">1</a>

                    <?php  $page_count = ($product_count/8 > 5) ? 5 : ceil($product_count/8);// to show the correct amount of page tabs based on product count
                        for($page = 2; $page <= $page_count; $page++) { ?>

                        <a href="#"><?php echo $page ?></a>

                    <?php } //&laquo;, &raquo;?>
                        
                    <a id="test" href="#">&rsaquo;</a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="js/product-graph.js"></script>
<script src="js/product-crud.js"></script>

<?php include("footer.php"); ?>