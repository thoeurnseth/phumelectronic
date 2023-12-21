<!doctype html>
<?php
  require (plugin_dir_path( __FILE__ ).'../helper.php');
  
  $query_image = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'empty_image'";

  $data_image = $wpdb->get_results($query_image, OBJECT);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES["fileToUploadLogo"]["name"]) {
      $query_data = array(
                      'slug' => 'empty_image', 
                      'title' => $_POST['title'], 
                      'image' => '', 
                      'description' => '', 
                    );

      $alert = array(
              'type' => 'error', 
              'title' => 'Failed to Change !',
              'message' => 'Required Image', 
          );

      $uploads_url = WP_CONTENT_URL."/uploads/revo/";
      $target_dir = WP_CONTENT_DIR."/uploads/revo/";
      $target_file = $target_dir . basename($_FILES["fileToUploadLogo"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $newname =  md5(date("Y-m-d H:i:s")) . "." . $imageFileType;
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

          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
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
              $query_data['image'] = $uploads_url.$newname;
            }else{
              move_uploaded_file($_FILES["fileToUploadLogo"]["tmp_name"], $target_dir.$newname);
              $query_data['image'] = $uploads_url.$newname;
            }
          }
      }

      if ($query_data['image'] != '') {

        $where = ['id' => $_POST['id']];
        $wpdb->update('revo_mobile_variable',$query_data,$where);
        
        if (@$wpdb->show_errors == false) {
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Data Updated Successfully', 
          );
        }
      }

      $_SESSION["alert"] = $alert;

      $data_image = $wpdb->get_results($query_image, OBJECT);
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
                    Setting Result Image
                  </h4>
                </div>
              </div>

              <?php foreach ($data_image as $data_image): 
                $title = str_replace("_", " ", $data_image->title);
                ?>
              <div class="row border-bottom-primary">
                <form class=" col-md-8 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">Image <?php echo $title ?></label>
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="fileToUploadLogo" required>
                            <small class="text-danger">Best Size : 
                              <?php 
                                  // echo $data_image->description 
                                  echo "800 x 800 px";
                              ?>    
                            </small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                          <input type="hidden" name="id" value="<?php echo $data_image->id ?>">
                          <input type="hidden" name="title" value="<?php echo $data_image->title ?>">
                            <button type="submit" class="btn btn-primary" style="text-transform: capitalize;">Update <?php echo $title ?></button>
                        </div>
                    </div>
                </form>
                <div class="col-md-4 text-center">
                    <div class="thumbnail-gallery my-auto text-center">
                        <a class="img-thumbnail lightbox my-auto" style="border:unset;" href="<?=isset($data_image->image)? $data_image->image : get_image() ?>" data-plugin-options='{ "type":"image" }'>
                          <img class="img-responsive" src="<?=isset($data_image->image) ? $data_image->image : get_image() ?>" style="width: 100px">
                          <span class="zoom">
                            <i class="fa fa-search"></i>
                          </span>
                        </a>
                    </div>
                </div>
              </div>
              <?php endforeach ?>
            </div>
          </section>
      </section>
    </div>
    </section>
  </div>
</body>
<?php include (plugin_dir_path( __FILE__ ).'partials/_js.php'); ?>
</html>