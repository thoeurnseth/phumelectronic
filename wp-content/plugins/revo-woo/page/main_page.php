<!doctype html>
<?php
  require (plugin_dir_path( __FILE__ ).'../helper.php');
  
  $query_logo = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'logo' LIMIT 1";
  $query_splash = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'splashscreen' LIMIT 1";
  $query_kontak = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'kontak' LIMIT 3 ";
  $query_cs = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'cs'";
  $query_pp = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'privacy_policy'";
  $query_about = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'about'";

  $data_logo = $wpdb->get_row($query_logo, OBJECT);
  $data_splash = $wpdb->get_row($query_splash, OBJECT);
  $data_cs = $wpdb->get_row($query_cs, OBJECT);
  $data_pp = $wpdb->get_row($query_pp, OBJECT);
  $data_about = $wpdb->get_row($query_about, OBJECT);
  $data_kontak = $wpdb->get_results($query_kontak, OBJECT);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES["fileToUploadSplash"]["name"]) {
      $query_data = array(
                      'slug' => 'splashscreen', 
                      'image' => '', 
                      'description' => $_POST['description'], 
                    );

      $alert = array(
              'type' => 'error', 
              'title' => 'Failed to Change SplashScreen !',
              'message' => 'Required Image', 
          );

      $uploads_url = WP_CONTENT_URL."/uploads/revo/";
      $target_dir = WP_CONTENT_DIR."/uploads/revo/";
      $target_file = $target_dir . basename($_FILES["fileToUploadSplash"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $newname =  md5(date("Y-m-d H:i:s")) . "." . $imageFileType;
      $is_upload_error = 0;
      if($_FILES["fileToUploadSplash"]["size"] > 0){

          if ($_FILES["fileToUploadSplash"]["size"] > 2000000) {
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
            if ($_FILES["fileToUploadSplash"]["size"] > 500000) {
              compress($_FILES["fileToUploadSplash"]["tmp_name"],$target_dir.$newname,90);
              $query_data['image'] = $uploads_url.$newname;
            }else{
              move_uploaded_file($_FILES["fileToUploadSplash"]["tmp_name"], $target_dir.$newname);
              $query_data['image'] = $uploads_url.$newname;
            }
          }
      }

      if ($query_data['image'] != '') {
        if ($data_splash == NULL || empty($data_splash)) {

              $wpdb->insert('revo_mobile_variable',$query_data);

              if (@$wpdb->insert_id > 0) {
                $alert = array(
                  'type' => 'success', 
                  'title' => 'Success !',
                  'message' => 'Splashscreen Updated Successfully', 
                );
              }
            
        }else{

            $where = ['id' => $data_splash->id];
            $wpdb->update('revo_mobile_variable',$query_data,$where);
            
            if (@$wpdb->show_errors == false) {
              $alert = array(
                'type' => 'success', 
                'title' => 'Success !',
                'message' => 'Splashscreen Updated Successfully', 
              );
            }

        }
      }

      $_SESSION["alert"] = $alert;

      $data_splash = $wpdb->get_row($query_splash, OBJECT);
    }
    if ($_FILES["fileToUploadLogo"]["name"]) {
      $query_data = array(
                      'slug' => 'logo', 
                      'title' => $_POST['title'], 
                      'image' => '', 
                      'description' => 'logo', 
                    );

      $alert = array(
              'type' => 'error', 
              'title' => 'Failed to Change Logo !',
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
              'title' => 'Uploads Error Logo !',
              'message' => 'your file is too large. max 2Mb', 
            );
            $is_upload_error = 1;
          }

          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
            $alert = array(
              'type' => 'error', 
              'title' => 'Uploads Error Logo !',
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
        if ($data_logo == NULL || empty($data_logo)) {

              $wpdb->insert('revo_mobile_variable',$query_data);

              if (@$wpdb->insert_id > 0) {
                $alert = array(
                  'type' => 'success', 
                  'title' => 'Success !',
                  'message' => 'Logo Updated Successfully', 
                );
              }
            
        }else{

            $where = ['id' => $data_logo->id];
            $wpdb->update('revo_mobile_variable',$query_data,$where);
            
            if (@$wpdb->show_errors == false) {
              $alert = array(
                'type' => 'success', 
                'title' => 'Success !',
                'message' => 'Logo Updated Successfully', 
              );
            }

        }
      }

      $_SESSION["alert"] = $alert;

      $data_logo = $wpdb->get_row($query_logo, OBJECT);
    }

    if (@$_POST['slug']) {
      if ($_POST['slug'] == 'kontak') {

        $success = 0;
        $where_wa = array(
          'slug' => 'kontak', 
          'title' => 'wa',
        );

        $success = insert_update_MV($where_wa,$_POST['id_wa'],$_POST['number_wa']);

        $where_phone = array(
          'slug' => 'kontak', 
          'title' => 'phone',
        );

        $success = insert_update_MV($where_phone,$_POST['id_tel'],$_POST['number_tel']);

        $where_sms = array(
          'slug' => 'kontak', 
          'title' => 'sms', 
        );

        $success = insert_update_MV($where_sms,$_POST['id_sms'],$_POST['number_sms']);

        if ($success > 0) {
          $data_kontak = $wpdb->get_results($query_kontak, OBJECT);
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Contact Updated Successfully', 
          );
        }else{
          $alert = array(
            'type' => 'error', 
            'title' => 'error !',
            'message' => 'Contact Failed to Update', 
          );
        }

        $_SESSION["alert"] = $alert;

      }

      if ($_POST['slug'] == 'url') {
        $success = 0;
        
        for ($i=1; $i < 4; $i++) { 
            $query_data = array(
              'slug' => $_POST['slug'.$i], 
              'title' => $_POST['title'.$i], 
              'description' => $_POST['description'.$i], 
            );

          if ($_POST['id'.$i] != 0) {
            $where = ['id' => $_POST['id'.$i]];
            $wpdb->update('revo_mobile_variable',$query_data,$where);
            
            if (@$wpdb->show_errors == false) {
              $success = 1;
            }
          }else{
            $wpdb->insert('revo_mobile_variable',$query_data);
            if (@$wpdb->insert_id > 0) {
              $success = 1;
            }
          }
        }

        if ($success) {
          $data_cs = $wpdb->get_row($query_cs, OBJECT);
          $data_about = $wpdb->get_row($query_about, OBJECT);
          $data_pp = $wpdb->get_row($query_pp, OBJECT);
          
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => $_POST['title'].' Success to Update', 
          );
        }else{
          $alert = array(
            'type' => 'error', 
            'title' => 'error !',
            'message' => $_POST['title'].' Failed to Update', 
          );
        }

        $_SESSION["alert"] = $alert;
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
              <div class="row border-bottom-primary">
                <div class="col-md-12">
                  <h4 style="margin-bottom: 35px">Setting Logo Apps</h4>
                </div>
                <form class=" col-md-8 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-left" for="inputDefault">Title Apps</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="title" value="<?php echo $data_logo->title ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-left" for="inputDefault">Image</label>
                        <div class="col-md-9">
                            <input type="file" class="form-control" name="fileToUploadLogo" required>
                            <small class="text-danger">Best Size : 100 x 100px</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Update Logo Apps</button>
                        </div>
                    </div>
                </form>
                <div class="col-md-4 text-center">
                    <h5 class="mb-2" style="margin-bottom: 15px">Preview</h5>
                    <div class="thumbnail-gallery my-auto text-center">
                      <a class="img-thumbnail lightbox my-auto" style="border:unset;" href="<?=isset($data_logo->image)? $data_logo->image : get_logo() ?>" data-plugin-options='{ "type":"image" }'>
                        <img class="img-responsive" src="<?=isset($data_logo->image) ? $data_logo->image : get_logo() ?>" style="width: 150px">
                        <span class="zoom">
                          <i class="fa fa-search"></i>
                        </span>
                      </a>
                    </div>
                </div>
              </div>
              <div class="row border-bottom-primary">
                <div class="col-md-12">
                  <h4 style="margin-bottom: 35px">General Settings</h4>
                </div>
                <form class=" col-md-5 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                    <h5 style="margin-bottom: 25px">Contact Setting</h5>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">WhatsApp</label>
                        <div class="col-md-8">
                            <?php 
                                $id = 0;
                                $value = '';
                                if ($data_kontak) {
                                  foreach ($data_kontak as $key){
                                    if ($key->title == 'wa') {
                                      $id = $key->id;
                                      $value = $key->description;
                                    }
                                  }
                                }
                              ?>
                                  <input type="number" class="form-control" name="number_wa" placeholder="ex: 628XXXXXXX" value="<?= $value ?>" required>
                                  <input type="hidden" name="id_wa" value="<?php echo $id  ?>">
                              <?php 
                                  
                              ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">No Telp</label>
                        <div class="col-md-8">
                            <?php 
                              $id = 0;
                              $value = '';
                              if ($data_kontak) {
                                foreach ($data_kontak as $key){
                                  if ($key->title == 'phone') {
                                    $id = $key->id;
                                    $value = $key->description;
                                  }
                                }
                              }
                            ?>
                            <input type="number" class="form-control" name="number_tel" placeholder="ex: 628XXXXXXX" value="<?= $value ?>" required>
                            <input type="hidden" name="id_tel" value="<?php echo $id ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">SMS</label>
                        <div class="col-md-8">
                            <?php 
                              $id = 0;
                              $value = '';
                              if ($data_kontak) {
                                foreach ($data_kontak as $key){
                                  if ($key->title == 'sms') {
                                    $id = $key->id;
                                    $value = $key->description;
                                  }
                                }
                              }
                            ?>
                            <input type="number" class="form-control" name="number_sms" placeholder="ex: 628XXXXXXX" value="<?= $value ?>" required>
                            <input type="hidden" name="id_sms" value="<?php echo $id  ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <input type="hidden" name="slug" value="kontak">
                            <button type="submit" class="btn btn-primary">Update Contact</button>
                        </div>
                    </div>
                </form>
                <form class=" col-md-7 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                    <h5 style="margin-bottom: 25px">URL Setting</h5>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">Link to About</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="description1" placeholder="ex: https://revoapps.id/about" value="<?= isset($data_about->description)? $data_about->description : '' ?>" required>
                            <input type="hidden" name="slug1" value="about">
                            <input type="hidden" name="title1" value="<?= isset($data_about->title)? $data_about->title : 'link about' ?>">
                            <input type="hidden" name="id1" value="<?= $data_about->id ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">Customer Support</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="description2" placeholder="ex: https://revoapps.id/customer-suport" value="<?= isset($data_cs->description)? $data_cs->description : '' ?>" required>
                            <input type="hidden" name="slug2" value="cs">
                            <input type="hidden" name="title2" value="<?= isset($data_cs->title)? $data_cs->title : 'customer service' ?>">
                            <input type="hidden" name="id2" value="<?= $data_cs->id ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left" for="inputDefault">Privacy Policy</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="description3" placeholder="ex: https://revoapps.id/privacy-policy" value="<?= isset($data_pp->description)? $data_pp->description : '' ?>" required>
                            <input type="hidden" name="slug3" value="privacy_policy">
                            <input type="hidden" name="title3" value="<?= isset($data_pp->title)? $data_pp->title : 'Privacy Policy' ?>">
                            <input type="hidden" name="id3" value="<?= $data_pp->id ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <input type="hidden" name="slug" value="url">
                            <button type="submit" class="btn btn-primary">Update URL</button>
                        </div>
                    </div>
                </form>
              </div>
              <div class="row border-bottom-primary">
                <div class="col-md-12">
                  <h4 style="margin-bottom: 35px">Setting Splash Screen</h4>
                </div>
                <form class=" col-md-8 form-horizontal form-bordered" method="POST" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-left" for="inputDefault">Description</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="description" name="description" value="<?=isset($data_splash->description)? $data_splash->description : 'Welcome'?>"  placeholder="title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-left" for="inputDefault">Image</label>
                        <div class="col-md-9">
                            <input type="file" class="form-control" name="fileToUploadSplash" required>
                            <small class="text-danger">Best Size : 500 x 1000 px</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Update Splash Screen</button>
                        </div>
                    </div>
                </form>
                <div class="col-md-4 text-center">
                    <div class="thumbnail-gallery my-auto text-center">
                        <a class="img-thumbnail lightbox my-auto w-75" style="border:unset;" href="<?=isset($data_splash->image)? $data_splash->image : get_logo() ?>" data-plugin-options='{ "type":"image" }'>
                          <img class="img-responsive w-75 mx-auto" src="<?=isset($data_splash->image) ? $data_splash->image : get_logo() ?>" style="width: 200px">
                          <p class="font-size-xl" style="color: black;margin-top: 15px" id="title"><?=isset($data_splash->description)? $data_splash->description : 'Welcome'?></p>
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