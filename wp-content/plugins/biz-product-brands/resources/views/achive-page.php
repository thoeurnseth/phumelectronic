<main class="product-brand">
    <div id="primary" class="content-area">
        <div class="container">

                <div class="row">                      
                    <?php
                        $brand = new Brand_Autoloader();
                        $brand->brand_query();
                    ?>
                </div> 

        </div>
    </div>
</main>



