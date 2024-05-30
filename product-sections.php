<section id="main-carousel-section">
  <h2 class="product-grid-title">Deals of the Week</h2>
  <div class="carousel">
    <button id="move-left"><</button>
    <div id="slide-show">
      <div class="slide-cont">
        <?php $grid_products = get_carousel_products();//the first 25 products fit in the carousel ?>
      </div>
    </div>
    <button id="move-right">></button>
  </div>
</section>

<section id="product-grid-section">
  <h2 class="product-grid-title">New Arrivals</h2>
  <div id="product-grid">
    <?php get_grid_products($grid_products); //the rest go in the grid ?>
  </div>
</section>