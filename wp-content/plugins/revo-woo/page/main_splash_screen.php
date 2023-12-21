<!doctype html>
<?php
  require (plugin_dir_path( __FILE__ ).'../helper.php');
  
  $query_image = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'splashscreen'";

  $data_image = $wpdb->get_results($query_image, OBJECT);


  $uploads_url = WP_CONTENT_URL."/uploads/splashscreen/";
  $target_dir = WP_CONTENT_DIR."/uploads/splashscreen/";
 

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES["fileToUploadLogo"]["name"]) {
      
      $newname =  $_POST['splash_screen_name'];
      $target_file = $target_dir . basename($_FILES["fileToUploadLogo"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
     
      $is_upload_error = 0;
      if($_FILES["fileToUploadLogo"]["size"] > 0){

          if ($_FILES["fileToUploadLogo"]["size"] > 2000000) {
            $alert = array(
              'type' => 'error', 
              'title' => 'Uploads Error !',
              'message' => 'your file is too large. max 2Mb', 
            );
            $is_upload_error = 1;
          }

          if($imageFileType != "png") {
            $alert = array(
              'type' => 'error', 
              'title' => 'Uploads Error !',
              'message' => 'only JPG, JPEG & PNG files are allowed.', 
            );
            $is_upload_error = 1;
          }

          if ($is_upload_error == 0) {
            if ($_FILES["fileToUploadLogo"]["size"] > 500000) {
              compress($_FILES["fileToUploadLogo"]["tmp_name"],$target_dir.$newname,90);
            }else{
              move_uploaded_file($_FILES["fileToUploadLogo"]["tmp_name"], $target_dir.$newname);
            }
          }
      } 
    }
  }

?>
<html class="fixed sidebar-light">
<?php include (plugin_dir_path( __FILE__ ).'partials/_css.php'); ?>
<body>
  <?php include (plugin_dir_path( __FILE__ ).'partials/_header.php'); ?>
  <div class="container-fluid">
    <?php include (plugin_dir_path( __FILE__ ).'partials/_alert.php'); ?>
    <section class="panel">
      <div class="inner-wrapper pt-0">

        <!-- start: sidebar -->
        <?php include (plugin_dir_path( __FILE__ ).'partials/_new_sidebar.php'); ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body p-0 pl-0">
            <section class="panel mb-3">
              <div class="panel-body">
                <div class="row mb-2">
                  <div class="col-6 text-left">
                    <h4 style="margin-bottom: 35px">
                     phone
                    </h4>
                  </div>
                </div>
                <div class="row border-bottom-primary">
                      <form class=" col-md-8 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                          <input name="splash_screen_name" value="phone.png" type="hidden">    
                          <div class="form-group">
                              <label class="col-md-4 control-label text-left" for="inputDefault">Image <?php echo $title ?></label>
                              <div class="col-md-8">
                                  <input type="file" class="form-control" name="fileToUploadLogo" required>
                                  <!-- <small class="text-danger">Best Size : 
                                    
                                  </small> -->
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 text-right">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="title" value="">
                                  <button type="submit" class="btn btn-primary" style="text-transform: capitalize;">Update <?php echo $title ?></button>
                              </div>
                          </div>
                      </form>
                      <div class="col-md-4 text-center">
                          <div class="thumbnail-gallery my-auto text-center">
                              <a class="img-thumbnail lightbox my-auto" style="border:unset;" href="<?= $uploads_url."phone.png"?>" data-plugin-options='{ "type":"image" }'>
                                <img class="img-responsive" src="<?= $uploads_url."phone.png?t=".time() ?>" style="width: 100px;">
                                <span class="zoom">
                                  <i class="fa fa-search"></i>
                                </span>
                              </a>
                          </div>
                      </div>
                </div>
                <div class="row mb-2">
                  <div class="col-6 text-left">
                    <h4 style="margin-bottom: 35px">
                    tablet
                    </h4>
                  </div>
                </div>
                <div class="row border-bottom-primary">
                      <form class=" col-md-8 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                          <input name="splash_screen_name" value="tablet.png" type="hidden">
                          <div class="form-group">
                              <label class="col-md-4 control-label text-left" for="inputDefault">Image<?php echo $title ?></label>
                              <div class="col-md-8">
                                  <input type="file" class="form-control" name="fileToUploadLogo" required>
                                  <!-- <small class="text-danger">Best Size : 
                                    
                                  </small> -->
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12 text-right">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="title" value="">
                                  <button type="submit" class="btn btn-primary" style="text-transform: capitalize;">Update <?php echo $title?></button>
                              </div>
                          </div>
                      </form>
                      <div class="col-md-4 text-center">
                          <div class="thumbnail-gallery my-auto text-center">
                              <a class="img-thumbnail lightbox my-auto" style="border:unset;" href="<?= $uploads_url."tablet.png"?>" data-plugin-options='{ "type":"image" }'>
                                <img class="img-responsive" src="<?= $uploads_url."tablet.png?t=".time() ?>" style="width: 100px;">
                                <span class="zoom">
                                  <i class="fa fa-search"></i>
                                </span>
                              </a>
                          </div>
                      </div>
                </div>
              </div>             
            </section>
        </section>
      </div>
    </section>
  </div>
</body>
<?php include (plugin_dir_path( __FILE__ ).'partials/_js.php'); ?>
</html>