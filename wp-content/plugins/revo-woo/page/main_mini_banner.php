<?php 

  require (plugin_dir_path( __FILE__ ).'../helper.php');
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (@$_POST["typeQuery"] == 'insert') {

      if(@$_POST["jenis"] == 'file' || @$_POST["jenis"] == 'link') {

        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Add Data Banner', 
        );

        $images_url = '';

        if ($_POST["jenis"] == 'file') {
            $uploads_url = WP_CONTENT_URL."/uploads/revo/";
            $target_dir = WP_CONTENT_DIR."/uploads/revo/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $newname =  md5(date("Y-m-d H:i:s")) . "." . $imageFileType;
            $is_upload_error = 0;
            if($_FILES["fileToUpload"]["size"] > 0){

                if ($_FILES["fileToUpload"]["size"] > 2000000) {
                  $alert = array(
                    'type' => 'error', 
                    'title' => 'Uploads Error !',
                    'message' => 'your file is too large. max 2Mb', 
                  );
                  $is_upload_error = 1;
                }

                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                  $alert = array(
                    'type' => 'error', 
                    'title' => 'Uploads Error !',
                    'message' => 'only JPG, JPEG & PNG files are allowed.', 
                  );
                  $is_upload_error = 1;
                }

                if ($is_upload_error == 0) {
                  if ($_FILES["fileToUpload"]["size"] > 500000) {
                    compress($_FILES["fileToUpload"]["tmp_name"],$target_dir.$newname,90);
                    $images_url = $uploads_url.$newname;
                  }else{
                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir.$newname);
                    $images_url = $uploads_url.$newname;
                  }
                }
            }
        }else{
          $images_url = $_POST['url_link'];
        }

        if ($images_url != '') {

          $id = 0;
          $is_type = '';
          $product_title = '';
          if(!empty($_POST['idproduct'])) {
            $is_type  = 'product';
            $id       = $_POST['idproduct'];
            $product  = get_product_varian_detail($_POST['idproduct']);
            $product_title = $product[0]->get_title();
          }
          elseif(!empty($_POST['idcategory'])) {
            $is_type  = 'category';
            $id       = $_POST['idcategory'];

            $category_list = json_decode(get_categorys());
            foreach($category_list as $cat) {
              if($id == $cat->id) {
                $product_title = $cat->text;
              }
            }
          }
          else {
            $is_type  = 'coupon';
            $id       = 0; //$_POST['idcoupon'];
            
            // $coupon_list   = json_decode(get_coupons());
            // foreach($coupon_list as $coupon) {
            //   $product_title = $coupon->text;
            // }
            $product_title = '';
          }
          $wpdb->insert('revo_list_mini_banner',                  
          [
            'order_by'      => $_POST['order_by'],
            'product_id'    => $id,
            'product_name'  => $product_title,
            'type'          => $_POST['type'],
            'is_type'       => $is_type,
            'image'         => $images_url,
            'start_date'    => $_POST['start_date'],
            'end_date'      => $_POST['end_date']
          ]);

          if (@$wpdb->insert_id > 0) {
            $alert = array(
              'type' => 'success', 
              'title' => 'Success !',
              'message' => 'Banner Success Saved', 
            );
          }
        }

        $_SESSION["alert"] = $alert;
        
      }

    }elseif (@$_POST["typeQuery"] == 'update') {

      if(@$_POST["jenis"] == 'file' || @$_POST["jenis"] == 'link') {

        $id = 0;
        $is_type = '';
        $product_title = '';
        $post_type_update = $_POST['post_type_update'];

        if($post_type_update == 'product') {
          $is_type  = 'product';
          $id       = $_POST['idproduct'];
          $product  = get_product_varian_detail($_POST['idproduct']);
          $product_title = $product[0]->get_title();
        }
        elseif($post_type_update == 'category') {
          $is_type  = 'category';
          $id       = $_POST['idcategory'];

          $category_list = json_decode(get_categorys());
          foreach($category_list as $cat) {
            if($id == $cat->id) {
              $product_title = $cat->text;
            }
          }
        }
        else {
          $is_type  = 'coupon';
          $id       = 0; //$_POST['idcoupon'];
          $product_title = '';
          
          // $coupon_list   = json_decode(get_coupons());
          // foreach($coupon_list as $coupon) {
          //   $product_title = $coupon->text;
          // }
        }
        
        $dataUpdate = array(
                        'order_by'    => $_POST['order_by'],
                        'product_id'  => $id,
                        'product_name'=> $product_title,
                        'type'        => $_POST['type'],
                        'start_date'  => $_POST['start_date'],
                        'end_date'    => $_POST['end_date'],
                        'is_type'     => $is_type,
                    );

        $where = array('id' => $_POST['id']);

        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Update Banner '.$_POST['title'], 
        );

        $images_url = '';

        if ($_POST["jenis"] == 'file') {
            $uploads_url = WP_CONTENT_URL."/uploads/revo/";
            $target_dir = WP_CONTENT_DIR."/uploads/revo/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $newname =  md5(date("Y-m-d H:i:s")) . "." . $imageFileType;
            $is_upload_error = 0;
            if($_FILES["fileToUpload"]["size"] > 0){

                if ($_FILES["fileToUpload"]["size"] > 2000000) {
                  $alert = array(
                    'type' => 'error', 
                    'title' => 'Uploads Error !',
                    'message' => 'your file is too large. max 2Mb', 
                  );
                  $is_upload_error = 1;
                }

                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                  $alert = array(
                    'type' => 'error', 
                    'title' => 'Uploads Error !',
                    'message' => 'only JPG, JPEG & PNG files are allowed.', 
                  );
                  $is_upload_error = 1;
                }

                if ($is_upload_error == 0) {
                  if ($_FILES["fileToUpload"]["size"] > 500000) {
                    
                    compress($_FILES["fileToUpload"]["tmp_name"],$target_dir.$newname,90);
                    $dataUpdate['image'] = $uploads_url.$newname; 

                  }else{

                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir.$newname);
                    $dataUpdate['image'] = $uploads_url.$newname; 

                  }
                }
            }

        }else{

          $dataUpdate['image'] = $_POST['url_link']; 

        }

        $wpdb->update('revo_list_mini_banner',$dataUpdate,$where);
        
        if (@$wpdb->show_errors == false) {
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Banner '.$_POST['title'].' Success Updated', 
          );
        }

        $_SESSION["alert"] = $alert;
        
      }

    }elseif (@$_POST["typeQuery"] == 'hapus') {
        header('Content-type: application/json');

        $query = $wpdb->update( 
              'revo_list_mini_banner', ['is_deleted' =>  '1'], 
              array( 'id' => $_POST['id'])
            );

        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Delete  Banner', 
        );

        if ($query) {
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Banner Success Deleted', 
          );
        }

        $_SESSION["alert"] = $alert;

        http_response_code(200);
        return json_encode(['kode' => 'S']);
        die();
    }

  }

  $data_banner = $wpdb->get_results("SELECT * FROM revo_list_mini_banner WHERE is_deleted = 0", OBJECT);

  $product_list  = json_decode(get_product_varian());
  $category_list = json_decode(get_categorys());
  $coupon_list   = json_decode(get_coupons());

  $img_example = revo_url().'/assets/extend/images/';
  $type_banner = array(['text' => 'Special Promo',
                        'image' => $img_example.'example_specialpromo.jpg'
                      ],['text' => 'Love These Items',
                        'image' => $img_example.'example_loveitems.jpg'
                      ],['text' => 'Blog Banner',
                        'image' => $img_example.'example_blog.jpg'
                      ]);
?>

<!doctype html>
<html class="fixed">
<?php include (plugin_dir_path( __FILE__ ).'partials/_css.php'); ?>
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
                      <h4>Additional Banner <?php echo buttonQuestion() ?></h4>
                    </div>
                    <div class="col-6 text-right">
                      <button class="btn btn-primary"  data-toggle="modal" data-target="#Banner">
                        <i class="fa fa-plus"></i> Add Banner
                      </button>
                    </div>
                  </div>
                  <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                      <tr>
                        <th>Sort</th>
                        <th>Type Banner</th>
                        <th>Image</th>
                        <th>Link To Product</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End date</th>
                        <th class="hidden-xs">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      foreach ($data_banner as $key => $value):
                        $start_date = $value->start_date;
                        $end_date   = $value->end_date;
                        $image_item = $value->image;
                      ?>

                        <tr>
                          <td><?php echo $value->order_by ?></td>
                          <td><?php echo $value->type ?></td>
                          <td>
                            <img src="<?php echo $image_item ?>" class="img-fluid" style="width: 100px">
                          </td>
                          <td><?php echo $value->product_name ?></td>
                          <td><?php echo $value->is_type ?></td>
                          <td><?php echo $start_date ?></td>
                          <td><?php echo $end_date ?></td>

                          <td>
                            <button class="btn btn-primary"  data-toggle="modal" data-target="#Banner<?php echo $value->id ?>">
                              <i class="fa fa-edit"></i> Update
                            </button>
                                <div class="modal fade" id="Banner<?php echo $value->id ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title font-weight-600" id="exampleModalLabel">Update <?php echo $value->type.' - Urutan ke '.$value->order_by ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <form method="post" action="#" enctype="multipart/form-data">
                                            <div class="panel-body">
                                              <div class="form-group">
                                                <label class="col-sm-4 pl-0 control-label">Type Banner <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                   <select name="type" class="form-control" style="max-width: unset;font-size: 12px;color: #777777;margin-right: 0px;">
                                                      <?php for ($i=0; $i < count($type_banner); $i++) { ?>
                                                        <option value="<?php echo $type_banner[$i]['text'] ?>" <?php echo $type_banner[$i]['text'] == $value->type ? 'selected' : '' ?>><?php echo $type_banner[$i]['text'] ?></option>
                                                      <?php } ?>
                                                    </select>
                                                </div>
                                              </div>

                                              <?php
                                                $check_pro = '';
                                                $check_cat = '';
                                                $check_cou = '';
                                                $style_pro = '';
                                                $style_cat = '';
                                                $style_cou = '';
                                                  if($value->is_type == 'product') {
                                                    $check_pro = 'checked';
                                                    $style_pro = 'block';
                                                    $style_cat = 'none';
                                                    $style_cou = 'none';
                                                  } 
                                                  elseif($value->is_type == 'category') {
                                                    $check_cat = 'checked';
                                                    $style_pro = 'none';
                                                    $style_cat = 'block';
                                                    $style_cou = 'none';
                                                  }
                                                  else {
                                                    $check_cou = 'checked';
                                                    $style_pro = 'none';
                                                    $style_cat = 'none';
                                                    $style_cou = 'block';
                                                  }
                                              ?>

                                              <!-- @type of post update-->
                                              <div class="form-group">
                                                <label class="col-sm-4 pl-0 control-label">Link Type <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <div class="d-flex">
                                                    <div class="radio-custom radio-primary mr-4">
                                                      <input class="typeInsert action_product_tab_update" type="radio" value="product" name="post_type_update" <?= $check_pro ?>>
                                                      <label class="font-size-14" for="link">Product</label>
                                                    </div>
                                                    <div class="radio-custom radio-primary mr-4">
                                                      <input class="typeInsert action_category_tab_update" type="radio" value="category" name="post_type_update" <?= $check_cat ?>>
                                                      <label class="font-size-14" for="link">Category</label>
                                                    </div>
                                                    <div class="radio-custom radio-primary mr-4">
                                                      <input class="typeInsert action_coupon_tab_update" type="radio" value="coupon" name="post_type_update" <?= $check_cou ?>>
                                                      <label class="font-size-14" for="link">Coupon</label>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>

                                              <!-- @product update -->
                                              <div class="form-group product_tab_update" style="display: <?= $style_pro ?>">
                                                <label class="col-sm-4 pl-0 control-label">Link To Product <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <select id="states" name="idproduct" data-plugin-selectTwo class="form-control populate" title="Please select Product">
                                                    <option value="">Choose a Product</option>
                                                    <?php foreach ($product_list as $product): ?>
                                                      <option value="<?php echo $product->id ?>" <?php echo $value->product_id == $product->id ? 'selected' : '' ?>><?php echo $product->text .' ( '. $product->sku .' ) ' ?></option>
                                                    <?php endforeach ?>
                                                  </select>
                                                </div>
                                              </div>

                                              <!-- @category update -->
                                              <div class="form-group category_tab_update" style="display: <?= $style_cat ?>">
                                                <label class="col-sm-4 pl-0 control-label">Link To Category <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <select id="states" name="idcategory" data-plugin-selectTwo class="form-control populate" title="Please select Category">
                                                    <option value="">Choose a Category</option>
                                                    <?php foreach ($category_list as $category): ?>
                                                      <option value="<?php echo $category->id ?>" <?php echo $value->product_id == $category->id ? 'selected' : '' ?>><?php echo $category->text ?></option>
                                                    <?php endforeach ?>
                                                  </select>
                                                </div>
                                              </div>

                                              <!-- @coupon update -->
                                              <!-- <div class="form-group coupon_tab_update" style="display: <?php // echo $style_cou ?>">
                                                <label class="col-sm-4 pl-0 control-label">Link To Coupon<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <select id="states" name="idcoupon" data-plugin-selectTwo class="form-control populate" title="Please select Coupon">
                                                    <option value="">Choose a Coupon</option>
                                                    <?php // foreach ($coupon_list as $coupon): ?>
                                                      <option value="<?php // echo $coupon->id ?>" <?php // echo $value->product_id == $coupon->id ? 'selected' : '' ?>><?php // echo $coupon->text ?></option>
                                                    <?php // endforeach ?>
                                                  </select>
                                                </div>
                                              </div> -->

                                              <div class="form-group">
                                                <label class="col-sm-4 pl-0 control-label">Sort to <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <input type="number" value="<?php echo $value->order_by ?>" name="order_by" class="form-control"  placeholder="Number Only" required="" aria-required="true">
                                                  <input type="hidden" value="<?php echo $value->id ?>" name="id" required>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <label class="col-sm-4 pl-0 control-label">Type Image Banner <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <div class="d-flex">
                                                    <div class="radio-custom radio-primary mr-4">
                                                      <input id="link<?php echo $value->id ?>" BannerID="<?php echo $value->id ?>" class="updateFile" name="jenis" type="radio" value="link" checked>
                                                      <label class="font-size-14" for="link<?php echo $value->id ?>">Link / URL</label>
                                                    </div>
                                                    <div class="radio-custom radio-primary mb-2">
                                                      <input id="uploadsImage<?php echo $value->id ?>" BannerID="<?php echo $value->id ?>" class="updateFile" name="jenis" type="radio" value="file">
                                                      <label class="font-size-14" for="uploadsImage<?php echo $value->id ?>">Upload Image</label>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <label class="col-sm-4 pl-0 control-label">Select Image <span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                  <input type="hidden" name="typeQuery" value="update">
                                                  <input type="text" name="url_link" class="form-control" id="linkInput<?php echo $value->id ?>" placeholder="eg.: google.co.id/Banner.jpeg" value="<?php echo $value->image ?>" required>
                                                  <input type="file" name="fileToUpload" class="form-control" id="fileinput<?php echo $value->id ?>" style="display: none;">
                                                  <img src="<?php echo $value->image ?>" class="img-fluid my-2" style="width: 100px">
                                                  <p class="mb-0 mt-2" style="line-height: 15px">
                                                    <small class="text-danger">Best Size : 480 x 240 px</small> <br>
                                                    <small class="text-danger">Max File Size : 2MB</small>
                                                  </p>
                                                </div>
                                              </div>
                                              <!-- update start_date end_date -->                                                             
                                              <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Start_date <span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                      <input class="form-control" value="<?php echo $start_date ?>" type="date" id="date" name="start_date" placeholder="Year/Month/Day">   
                                                      <!-- <small class="text-danger">Banner website will show when start date</small> <br>                                            -->
                                                  </div>                                                    
                                              </div>
                                              <div class="form-group">
                                                <label class="col-sm-4 pl-0 control-label">End_date <span class="required" aria-required="true">*</span></label>
                                                    <div class="col-sm-8">
                                                      <div class="form-group">
                                                        <div class='input-group date' id='datetimepicker6'>
                                                            <input class="form-control" value="<?php echo $end_date ?>" type="date" id="date" name="end_date" placeholder="Year/Month/Day">    
                                                            <!-- <small class="text-danger">Banner website will hide when end date finish</small> <br>                                           -->
                                                        </div>
                                                      </div>
                                                    </div> 
                                              </div>
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
                            <button class="btn btn-danger" onclick="hapus('<?php echo $value->id ?>')">
                              <i class="fa fa-trash"></i> Delete
                            </button>
                          </td>
                        </tr>
                      <?php 
                        endforeach;
                        if (!empty($data_banner)) {
                          $key = $key + 2;
                        }else{
                          $key = 1;
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
            </section>
        </div>
      </section>
    </section>
  </div>
  <!-- @Add Additional Banner -->
  <div class="modal fade" id="Banner" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-600" id="exampleModalLabel">Add Additional Banner</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="#" enctype="multipart/form-data">
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 pl-0 control-label">Type Banner <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <select name="type" class="form-control" style="max-width: unset;font-size: 12px;color: #777777;margin-right: 0px;">
                      <?php for ($i=0; $i < count($type_banner); $i++) { ?>
                        <option value="<?php echo $type_banner[$i]['text'] ?>"><?php echo $type_banner[$i]['text'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                  <!-- @type of post -->
                  <div class="form-group">
                  <label class="col-sm-4 pl-0 control-label">Link Type <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <div class="d-flex">
                      <div class="radio-custom radio-primary mr-4">
                        <input class="typeInsert" type="radio" name="post_type" id="action_product_tab" checked>
                        <label class="font-size-14" for="link">Product</label>
                      </div>
                      <div class="radio-custom radio-primary mr-4">
                        <input class="typeInsert" type="radio" name="post_type" id="action_category_tab">
                        <label class="font-size-14" for="link">Category</label>
                      </div>
                      <div class="radio-custom radio-primary mr-4">
                        <input class="typeInsert" type="radio" name="post_type" id="action_coupon_tab">
                        <label class="font-size-14" for="link">Coupon</label>
                      </div>
                    </div>
                </div>

                <!-- @link to product -->
                <div class="form-group" id="product_tab">
                  <label class="col-sm-4 pl-0 control-label">Link To Product <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="hidden" name="typeQuery" value="insert">
                    <select id="states" name="idproduct" data-plugin-selectTwo class="form-control populate" title="Please select Product">
                      <option value="">Choose a Product</option>
                      <?php foreach ($product_list as $product): ?>
                        <option value="<?php echo $product->id ?>"><?php echo $product->text .' ( '. $product->sku .' ) ' ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <!-- create start_date end_date -->                                                             
                <div class="form-group">
                    <label class="col-sm-4 pl-0 control-label">Start_date <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-8">
                      <input class="form-control" value="" type="date" id="date" name="start_date" placeholder="Year/Month/Day">   
                      <!-- <small class="text-danger">Banner website will show when start date</small> <br>                                            -->
                    </div>                                                    
                </div>
                <div class="form-group">
                    <label class="col-sm-4 pl-0 control-label">End_date <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-8">
                      <div class="form-group">
                        <div class='input-group date' id='datetimepicker6'>
                            <input class="form-control" value="" type="date" id="date" name="end_date" placeholder="Year/Month/Day">    
                            <!-- <small class="text-danger">Banner website will hide when end date finish</small> <br>                                           -->
                        </div>
                      </div>
                    </div> 
                </div>
                <!-- @link to category -->
                <div class="form-group" id="category_tab" style="display: none;">
                  <label class="col-sm-4 pl-0 control-label">Link To Category <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <select id="states" name="idcategory" data-plugin-selectTwo class="form-control populate" title="Please select Category">
                      <option value="">Choose a Category</option>
                      <?php foreach ($category_list as $category): ?>
                        <option value="<?php echo $category->id ?>"><?php echo $category->text ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>

                <!-- @link to coupon -->
                <!-- <div class="form-group" id="coupon_tab" style="display: none;">
                  <label class="col-sm-4 pl-0 control-label">Link To Coupon<span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <select id="states" name="idcoupon" data-plugin-selectTwo class="form-control populate" title="Please select Coupon">
                      <option value="">Choose a Coupon</option>
                      <?php // foreach ($coupon_list as $coupon): ?>
                        <option value="<?php // echo $coupon->id ?>"><?php // echo $coupon->text ?></option>
                      <?php // endforeach ?>
                    </select>
                  </div>
                </div> -->


                <div class="form-group">
                  <label class="col-sm-4 pl-0 control-label">Sort to <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="number" value="<?php echo $key ?>" name="order_by" class="form-control"  placeholder="Number Only" required="" aria-required="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 pl-0 control-label">Type Image Banner <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <div class="d-flex">
                      <div class="radio-custom radio-primary mr-4">
                        <input class="typeInsert" id="link" name="jenis" type="radio" value="link" checked>
                        <label class="font-size-14" for="link">Link / URL</label>
                      </div>
                      <div class="radio-custom radio-primary mb-2">
                        <input class="typeInsert" id="uploadsImage" name="jenis" type="radio" value="file">
                        <label class="font-size-14" for="uploadsImage">Upload Image</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 pl-0 control-label">Select Image <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="text" name="url_link" class="form-control" id="linkInput" placeholder="eg.: google.co.id/Banner.jpeg" required>
                    <input type="file" name="fileToUpload" class="form-control" id="fileinput" style="display: none;">
                    <p class="mb-0 mt-2" style="line-height: 15px">
                      <small class="text-danger">Best Size : 480 x 240 px</small> <br>
                      <small class="text-danger">Max File Size : 2MB</small>
                    </p>
                  </div>
                </div>
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
            <?php for ($i=0; $i < count($type_banner); $i++) { ?>
              <div class="col-6 px-1">
                <div class="card p-3 mt-1">
                  <label class="control-label pb-2"><?php echo $type_banner[$i]['text']; ?></label>
                  <img src="<?php echo $type_banner[$i]['image']; ?>" style="height: 150px;width: auto;">
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include (plugin_dir_path( __FILE__ ).'partials/_js.php'); ?>
  <script type="text/javascript">

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

      jQuery('#action_product_tab').click(function(){
        jQuery('#product_tab').show();
        jQuery('#category_tab').hide();
        jQuery('#coupon_tab').hide();
      });
      jQuery('#action_category_tab').click(function(){
        jQuery('#product_tab').hide();
        jQuery('#category_tab').show();
        jQuery('#coupon_tab').hide();
      });
      jQuery('#action_coupon_tab').click(function(){
        jQuery('#product_tab').hide();
        jQuery('#category_tab').hide();
        jQuery('#coupon_tab').show();
      });


      jQuery('.action_product_tab_update').each(function(i){
        jQuery(this).click(function(){
          jQuery('.product_tab_update').show();
          jQuery('.category_tab_update').hide();
          jQuery('.coupon_tab_update').hide();
        })
      });
      jQuery('.action_category_tab_update').each(function(i){
        jQuery(this).click(function(){
          jQuery('.product_tab_update').hide();
          jQuery('.category_tab_update').show();
          jQuery('.coupon_tab_update').hide();
        })
      });
      jQuery('.action_coupon_tab_update').each(function(i){
        jQuery(this).click(function(){
          jQuery('.product_tab_update').hide();
          jQuery('.category_tab_update').hide();
          jQuery('.coupon_tab_update').show();
        })
      });

  </script>
</body>
</html>