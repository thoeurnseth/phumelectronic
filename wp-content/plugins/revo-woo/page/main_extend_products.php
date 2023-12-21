<?php

use AC\Helper\Strings;
  require (plugin_dir_path( __FILE__ ).'../helper.php');
  
  // global $wpdb;
  // $product_id = $wpdb->get_results("SELECT post_parent FROM `ph0m31e_posts` WHERE post_type = 'product_variation' and ID in(SELECT post_id FROM `ph0m31e_postmeta` WHERE meta_key = '_sale_price' AND meta_value != '')");
  // $id = [];
  // foreach($product_id as $value){
  //   $id[] = $value->post_parent;
  // }
    $time = time();
		$obj_sale_product = array();
		$query_args = array(
		  'posts_per_page'    => -1,
		  'no_found_rows'     => 1,
		  'post_status'       => 'publish',
		  'post_type'         => 'product',
		  'meta_query'        => WC()->query->get_meta_query(),
		  'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
		);
		
		$products = new WP_Query( $query_args );
		if( $products->have_posts() )
		{ 
		  while( $products->have_posts() ) {
        $products->the_post();
        $id = get_the_id();
        $current_date       = date('Y-m-d : H:i:s');
        $post_id = $wpdb->get_results("SELECT post_id FROM `ph0m31e_postmeta` WHERE post_id = $id AND meta_key = 'cambo_exspire_date' AND (meta_value > '$current_date' || meta_value = '')");
        foreach($post_id as $value){
          $postId = $value->post_id;
          $data_product_id = $wpdb->get_results("SELECT post_parent FROM `ph0m31e_posts` WHERE post_parent = '$postId' AND post_type = 'product_variation' and ID in(SELECT post_id FROM `ph0m31e_postmeta` WHERE (meta_key = '_sale_price_dates_from' AND meta_value <= '$time') OR (meta_key = 'cambo_exspire_date' AND (meta_value > '$current_date' || meta_value = '')))");
          foreach($data_product_id as $value_id){
            $post_ = $value_id->post_parent;
            array_push($obj_sale_product ,"".$post_."");
          }
        }
		  }
		}
		$result_id = json_encode($obj_sale_product);
		$wpdb->update('revo_extend_products',array(
		  'products' => $result_id,
		  ), array('id'=>1)
		);

  //     // get parent product 
  //     $query = WC()->query->get_meta_query();
  //     $query = array(
  //         'relation' => 'OR',
  //         array(
  //           'key' => 'cambo_exspire_date',
  //           'value' => '',
  //           'compare' => '=',
  //         ),
  //         array(
  //           'key' => 'cambo_exspire_date',
  //           'value' => date("Y-m-d H:i:s"),
  //           'compare' => '>',
  //         )
  //     );
  //     $query_args = array(
  //       'posts_per_page'    => -1,
  //       'no_found_rows'     => 1,
  //       'post_status'       => 'publish',
  //       'post_type'         => 'product',
  //       'meta_query'        => $query,
  //       'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
    
  //     );
  //     $products = new WP_Query( $query_args );
  //     $productIds = [];
  //     if( $products->have_posts() )
  //     { 
  //       while( $products->have_posts() ) {
  //       $products->the_post();
  //       $id = get_the_ID();
  //       array_push($productIds ,"".$id."");
  //       }
  //     }

  //     // Product Variant
  //     $query = WC()->query->get_meta_query();
  //     $query = array(
  //       'relation' => 'OR',
  //       array(
  //         'key' => '_sale_price_dates_from',
  //         'value' => time(),
  //         'compare' => '<=',
  //       ),
  //       array(
  //         'key' => '_sale_price',
  //         'value' => '',
  //         'compare' => '!=',
  //       )
  //     );
      
  //     $query_args = array(
  //       'posts_per_page'    => -1,
  //       'no_found_rows'     => 1,
  //       'post_status'       => 'publish',
  //       'post_type'         => 'product_variation',
  //       'meta_query'        => $query,
  //       'post_parent__in'       => $productIds,
  //     );

  // $obj_sale_product = array();
  // $productVariants = new WP_Query( $query_args );
  // // echo "Last SQL-Query: {$productVariants->request}";exit;

  // if( $productVariants->have_posts() )
  // { 
  //   while( $productVariants->have_posts() ) {
  //   $productVariants->the_post();
  //   $id = get_post_field("post_parent");
  //       array_push($obj_sale_product ,"".$id."");
  //   }
  // }

  // $result_id = json_encode($obj_sale_product);
  // $wpdb->update('revo_extend_products',array(
  //   'products' => $result_id,
  //   ), array('id'=>1)
  // );

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $products = array();
    $category=array();

    if ($_POST['products']) {
      $products = $_POST['products'];
    }
    $products = json_encode($products);

    if($_POST['idcategory']){
      $category=$_POST['idcategory'];
    }

    $category = json_encode($category); 
   



    if (@$_POST["typeQuery"] == 'update') {

        $dataUpdate = array(
                      'title' => $_POST['title'], 
                      'description' => $_POST['description'], 
                      'products' => $products,
                      'category_id'=>$category, 
                    );

        $where = array('id' => $_POST['id']);

        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Update Additional Products '.$_POST['title'], 
        );

        $wpdb->update('revo_extend_products',$dataUpdate,$where);
        
        if (@$wpdb->show_errors == false) {
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Additional Products '.$_POST['title'].' Success Updated', 
          );
        }

        $_SESSION["alert"] = $alert;
    }

  }

  $data_extend_products = $wpdb->get_results("SELECT * FROM revo_extend_products WHERE is_deleted = 0", OBJECT);

  $product_list = json_decode(get_product_varian());
  $category_list = json_decode(get_categorys());

?>

<!doctype html>
<html class="fixed">
<?php include (plugin_dir_path( __FILE__ ).'partials/_css.php'); ?>
<link href="<?php echo revo_url(); ?>assets/datepicker/daterangepicker.css" rel="stylesheet"/>
<style type="text/css">
  .wp-core-ui select {
    font-size: 12px;
    height: 25px;
    border-radius: 3px;
    padding: 0 24px 0 8px;
    min-height: 7px;
    max-width: 25rem;
    background-size: 10px 10px;
  }
  .daterangepicker .calendar-time {
    text-align: left;
    padding-left: 35px;
  }
  .applyBtn, .cancelBtn{
    padding: 10px;
  }
  dd, li {
    margin-bottom: 0px; 
  }
</style>
<body>
  <?php include (plugin_dir_path( __FILE__ ).'partials/_header.php'); ?>
  <div class="container-fluid">
    <section class="panel">
      <?php include (plugin_dir_path( __FILE__ ).'partials/_alert.php'); ?>
      <section class="panel">
          <div class="inner-wrapper pt-0">

            <!-- start: sidebar -->
            <?php include (plugin_dir_path( __FILE__ ).'partials/_new_sidebar.php'); ?>
            <!-- end: sidebar -->

            <section role="main" class="content-body p-0 pl-0">
                <div class="panel-body">
                    <div class="row mb-2">
                      <div class="col-6 text-left">
                        <h4>
                          Home Additional Products <?php echo buttonQuestion() ?>
                        </h4>
                      </div>
                      <div class="col-6 text-right">
                      </div>
                    </div>
                    <table class="table table-bordered table-striped mb-none" id="datatable-default">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Details</th>
                          <th style="width: 35%">List Product</th>
                          <th style="width: 15%">Category</th>
                          <th class="hidden-xs">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        foreach ($data_extend_products as $key => $value): ?>
                          <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td>
                              Title  : <span class="font-weight-600 mb-0 text-capitalize"><?php echo $value->title ?></span><br>
                              Description  : <span class="font-weight-600 mb-0 text-capitalize"><?php echo $value->description ?></span><br>
                              Show In  : <span class="font-weight-600 mb-0 text-capitalize">
                                          <?php 
                                              $type =  cek_type($value->type)['text'];
                                              $type = str_replace("Pannel","panel",$type);
                                              echo $type;
                                          ?>
                                          </span><br>
                            </td>
                            <td>
                                <?php 
                                    $list_products = json_decode($value->products);
                                    $show = 0;
                                    if (!empty($list_products) && $list_products != NULL) {
                                      if (is_array($list_products)) {
                                        for ($i=0; $i < count($list_products); $i++) { 
                                          if (!empty(get_product_varian_detail($list_products[$i]))) {
                                            echo '<span class="badge badge-primary p-2">'.get_product_varian_detail($list_products[$i])[0]->get_title().'</span> ';
                                            $show += 1;
                                          }
                                        }
                                      }else{
                                        if (!empty(get_product_varian_detail($list_products[0]))) {
                                         echo '<span class="badge badge-primary p-2">'.get_product_varian_detail($list_products)[0]->get_title().'</span> ';  
                                         $show += 1;
                                      }
                                      }
                                    }else{
                                      // echo '<span class="badge badge-danger p-2">empty !</span>';
                                    }

                                    if ($show == 0) {
                                      echo '<span class="badge badge-danger p-2">empty !</span>';
                                    }
                                  ?>
                            </td>

                              <td>
                                  <?php 
                                      $categorySelecteds = json_decode($value->category_id);
                                      // array(1222,5544,4555);
                                      $show = 0;
                                      if (!empty($categorySelecteds) && $categorySelecteds != NULL) {
                                        if (is_array($categorySelecteds)) {


                                          // loop categorySelecteds
                                            foreach($categorySelecteds as $key => $cateId){
                                                // loop category_list
                                                foreach( $category_list as $key1 => $cate){
                                                     // condition if category id equal to selected category
                                                    if($cateId == $cate->id){
                                                      // echo category name and break
                                                      echo '<span class="badge badge-primary p-2">'.$cate->text.'</span> ';
                                                      $show += 1;
                                                      break;
                                                    }else{
                                                      
                                                    }
                                                }
                                            }
                                            
                                        }
                                      }                                    
                              
                                      if ($show == 0) {
                                        echo '<span class="badge badge-danger p-2">empty !</span>';
                                      }
                                    ?>
                              </td>





                            <td>
                              <button class="btn btn-primary mb-2"  data-toggle="modal" data-target="#updateFlashSale<?php echo $value->id ?>">
                                <i class="fa fa-edit"></i> Update
                              </button><br>
                              <div class="modal fade" id="updateFlashSale<?php echo $value->id ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-600" id="exampleModalLabel">Update <?php echo $value->title ?></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <form method="post" action="#" enctype="multipart/form-data">
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <label class="col-sm-4 pl-0 control-label">Title <span class="required" aria-required="true">*</span></label>
                                              <div class="col-sm-8">
                                                <input type="text" name="title" value="<?php echo $value->title ?>" class="form-control"  placeholder="eg.: New Additional Products" required="" aria-required="true">
                                                <input type="hidden" value="<?php echo $value->id ?>" name="id" required>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-sm-4 pl-0 control-label">Description <span class="required" aria-required="true">*</span></label>
                                              <div class="col-sm-8">
                                                <input type="text" name="description" value="<?php echo $value->description ?>" class="form-control"  placeholder="eg.: New Additional Products" aria-required="true">
                                                <input type="hidden" value="<?php echo $value->id ?>" name="id" required>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-sm-4 pl-0 control-label">Product To Show <span class="required" aria-required="true">*</span></label>
                                              <div class="col-sm-8">
                                                <input type="hidden" name="typeQuery" value="update">
                                                <select name="products[]" multiple data-plugin-selectTwo class="form-control populate" title="Please select Product" required>
                                                  <?php foreach ($product_list as $product): ?>
                                                    <option 
                                                        value="<?php echo $product->id ?>" 
                                                        <?php 
                                                          if (is_array($list_products)) {
                                                            for ($i=0; $i < count($list_products); $i++) { 
                                                              echo $product->id == $list_products[$i] ? 'selected' : '';
                                                            }
                                                          }else{
                                                            echo $product->id == $list_products ? 'selected' : '';
                                                          }
                                                        ?>
                                                    ><?php echo $product->text .' ('. $product->sku .')' ?></option>
                                                  <?php endforeach ?>
                                                </select>
                                              </div>
                                            </div>
                                                          
                                                          
                                            <?php 
                                            if($value->type=='special'){

                                            ?>
                                                <div class="form-group">
                                                  <div class="form-group category_tab_update" style="display: <?= $style_cat ?>">
                                                      <label class="col-sm-4 pl-0 control-label">Category <span class="required" aria-required="true">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select id="states" name="idcategory[]" multiple data-plugin-selectTwo class="form-control populate" title="Please select Category">
                                                              <option value="">Choose a Category</option>
                                                              <?php foreach ($category_list as $category): ?>
                                                                <option value="<?php echo $category->id ?>" <?php
                                                                    if (is_array($categorySelecteds)) {
                                                                      for ($i=0; $i < count($categorySelecteds); $i++) { 
                                                                        echo $category->id== $categorySelecteds[$i] ? 'selected' : '';
                                                                      }
                                                                    }else{
                                                                      echo $category->id== $categorySelecteds? 'selected' : '';
                                                                    }
                                                              
                                                                  ?>                                                      
                                                                                                                        
                                                                ><?php echo $category->text?></option>
                                                              <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                  </div>
                                                </div>
                                            <?php }?>
                                                         

                                            <div class="form-group text-right mt-5">
                                              <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary">
                                                  <i class="fa fa-send"></i> Submit
                                                </button>
                                              </div>
                                            </div>
                                          </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>    
            </section>
        </div>
    </section>
    </section>
  </div>

  <div class="modal fade" id="question" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-600" id="exampleModalLabel">Show In Mobile Pannel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mx-0">
            <div class="col-6 px-1">
              <div class="card p-3 mt-1">
                <label class="control-label pb-2"><?php echo cek_type('special')['text'] ?></label>
                <img src="<?php echo cek_type('special')['image'] ?>" style="height: 150px;width: auto;">
              </div>
            </div>
            <div class="col-6 px-1">
              <div class="card p-3 mt-1">
                <label class="control-label pb-2"><?php echo cek_type('our_best_seller')['text'] ?></label>
                <img src="<?php echo cek_type('our_best_seller')['image'] ?>" style="height: 150px;width: auto;">
              </div>
            </div>
            <div class="col-6 px-1">
              <div class="card p-3 mt-1">
                <label class="control-label pb-2"><?php echo cek_type('recomendation')['text'] ?></label>
                <img src="<?php echo cek_type('recomendation')['image'] ?>" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php include (plugin_dir_path( __FILE__ ).'partials/_js.php'); ?>
  
  <script type="text/javascript">

    $(document).ready(function() {

      $('.updateFile, input[type=radio][name=jenis]').change(function() {
         var id = $(this).attr("FlashSaleID");
          if (this.value == 'file') {
              $('#linkInput' + id).css("display", "none");
              $('#linkInput' + id).removeAttr("required");
              $('#fileinput' + id).css("display", "block");
              $('#fileinput' + id).attr("required","");
          }
          else if (this.value == 'link') {
              $('#linkInput' + id).css("display", "block");
              $('#linkInput' + id).attr("required", "");
              $('#fileinput' + id).css("display", "none");
              $('#fileinput' + id).removeAttr("required");
          }
      });
    });

    function hapus(id){
        Swal.fire({
          title: 'Are you sure you want to delete this ?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: `delete`,
          denyButtonText: `cancel`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
              $.ajax({
                  url: "#",
                  method: "POST",
                  data: {
                      id: id,
                      typeQuery: 'hapus',
                  },
                  datatype: "json",
                  async: true,
                  success: function (data) {
                    location.reload();
                  },
                  error: function (data) {
                    location.reload();
                  }
              });
          }
        })

      }
  </script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script src="<?php echo revo_url(); ?>assets/datepicker/daterangepicker.js"></script>

  <script type="text/javascript">

      $(document).ready(function(){

          $('.inputTanggalflashSale').daterangepicker({
              "timePicker": true,
              "timePicker24Hour": true,
              "locale": {
                  "format": 'DD/MM/YYYY H:mm',
                  "separator": " - ",
                  "applyLabel": "Apply",
                  "cancelLabel": "Cancel",
                  "fromLabel": "From",
                  "toLabel": "To",
                  "customRangeLabel": "Custom",
                  "weekLabel": "W",
                  "daysOfWeek": [
                      "Sen",
                      "Sel",
                      "Rab",
                      "Kam",
                      "Jum",
                      "Sab",
                      "Min"
                  ],
                  "monthNames": [
                      "Januari",
                      "Februari",
                      "Maret",
                      "April",
                      "Mei",
                      "Juni",
                      "Juli",
                      "Augustus",
                      "September",
                      "October",
                      "November",
                      "December"
                  ],
                  "firstDay": 1
              }
          }, function(start, end, label) {
            
          });



      });
  </script>
</body>
</html>