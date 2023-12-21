<!doctype html>
  <style type="text/css">
    .modal-dialog {
        margin-top: 18%!important;
    }
  </style>
  <?php

use AC\Asset\Style;
use AC\Helper\Strings;
    require (plugin_dir_path( __FILE__ ).'../helper.php');

    $send_notif = false;

    function notif(){
      require (plugin_dir_path( __FILE__ ).'../helper.php');
      $query_notif = "SELECT * FROM `revo_mobile_variable` WHERE slug = 'firebase_notification' ORDER BY id DESC limit 1";
      return $wpdb->get_row($query_notif, OBJECT);
    }

    $data = access_key();
    $data_notif = notif();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($_POST['type'] == 'firebase_key') {
          $query_data = array(
                            "firebase_servey_key" => $_POST["firebase_servey_key"],
                            "firebase_api_key" => $_POST["firebase_api_key"],
                            "firebase_auth_domain" => $_POST["firebase_auth_domain"],
                            "firebase_database_url" => $_POST["firebase_database_url"],
                            "firebase_project_id" => $_POST["firebase_project_id"],
                            "firebase_storage_bucket" => $_POST["firebase_storage_bucket"],
                            "firebase_messaging_sender_id" => $_POST["firebase_messaging_sender_id"],
                            "firebase_app_id" => $_POST["firebase_app_id"],
                            "firebase_measurement_id" => $_POST["firebase_measurement_id"],
                          );

          $alert = array(
                    'type' => 'error', 
                    'title' => 'Gagal KEY Firebase !',
                    'message' => 'Try Again Later', 
                );

          $success = false;
          if (@$_POST['id']) {
            $wpdb->update('revo_access_key',$query_data,['id' => $_POST['id']]);
            if (@$wpdb->show_errors == false) {
              $success = true;
            }
          }else{
            $wpdb->insert('revo_access_key',$query_data);
            if (@$wpdb->show_errors == false) {
              $success = true;
            }
          }

          if ($success) {
            $alert = array(
              'type' => 'success', 
              'title' => 'Success !',
              'message' => 'KEY Firebase Berhasil update', 
            );
          }
        }

        if ($_POST['type'] == 'firebase_notification') {

          $query_data = array(
                          'slug'        => $_POST['type'], 
                          'title'       => $_POST['title'], 
                          'image'       => ' ', 
                          'description' => $_POST['description'],
                          'link_to'     => $_POST['link_to'], 
                          // 'description' => json_encode(['description' => $_POST['description'], 'link_to' => $_POST['link_to'], ]), 
                        );
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
            $images_url = (!is_null($_POST['url_link'])) ? $_POST['url_link'] : '';
          }

          $query_data['image'] = $images_url;
          // if ($query_data['image'] != '') {
            // if ($data_notif == NULL || empty($data_notif)) {
          $wpdb->insert('revo_mobile_variable',array(
            'slug'        => $_POST['type'], 
            'title'       => $_POST['title'], 
            'image'       => $images_url, 
            'description' => $_POST['description'],
            'link_to'     => $_POST['link_to'], 
          ));
                  // if (@$wpdb->insert_id > 0) {
                    $send_notif = true;
                    $alert = array(
                      'type' => 'success', 
                      'title' => 'Success !',
                      'message' => 'Notification Successfully Sent', 
                    );
                  // }
            // }else{
            //     $where = ['id' => $data_notif->id];
            //     $wpdb->update('revo_mobile_variable',$query_data,$where);              
            //     if (@$wpdb->show_errors == false) {
            //       $send_notif = true;
            //       $alert = array(
            //         'type' => 'success', 
            //         'title' => 'Success !',
            //         'message' => 'Notification Successfully Sent', 
            //       );
            //     }
            // }
          // }
        }

      $_SESSION["alert"] = $alert;

      $data = access_key();
      $data_notif = notif();
    }

    $show_notification = false;
    if (!empty($data->firebase_servey_key)) {
      $show_notification = true;
    }

    if (isset($data_notif->description)) {
      $description = json_decode($data_notif->description);
    }

    if ($send_notif) {

        $notification = array(
                          'title' => strval($data_notif->title), 
                          'body'  => strval($data_notif->description), 
                          'icon'  => get_logo(), 
                          'image' => (isset($data_notif->image) ? $data_notif->image : get_logo())
                        );

        $extend['id'] = "";
        $extend['type'] = "all";
        $extend['click_action'] = (isset($description->link_to) ? $description->link_to : '');
    
        $users_tokens = get_user_token();
        $tokens = [];
        foreach ($users_tokens as $users_token) {
          foreach($users_token as $value) {
            $tokens[] = $value;
          }
        }

        $status_send = send_FCM($tokens,$notification,$extend);
        if ($status_send == 'error') {
          $alert = array(
                'type' => 'error', 
                'title' => 'Gagal Kirim Notifikasi !',
                'message' => "Try Again Later", 
            );
        }
        $_SESSION["alert"] = $alert;   

    }

  ?>
  <html class="fixed">
    <?php include (plugin_dir_path( __FILE__ ).'partials/_css.php'); ?>
    <body>
      <?php include (plugin_dir_path( __FILE__ ).'partials/_header.php'); ?>
      <div class="container-fluid">
        <?php include (plugin_dir_path( __FILE__ ).'partials/_alert.php'); ?>
        <?php if(!$show_notification){ ?>
          <div class="alert text-capitalize alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <strong>Cannot Send Notifications ! </strong> Please Input Firebase <strong>SERVER KEY</strong> First
          </div>
        <?php } ?>
        <section class="panel">
            <div class="inner-wrapper pt-0">

                <!-- start: sidebar -->
                <?php include (plugin_dir_path( __FILE__ ).'partials/_new_sidebar.php'); ?>
                <!-- end: sidebar -->

                <section role="main" class="content-body p-0 pl-0">
                    <section class="panel">
                      <div class="panel-body">
                        <div class="row mb-2">
                          <div class="col-6 text-left">
                            <h4>
                              Post Notification
                            </h4>
                          </div>
                          <div class="col-6 text-right">
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#tambahintropage">
                              <i class="fa fa-cogs"></i> Setting Firebase Key
                            </button>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-12">
                            <form method="post" action="#" enctype="multipart/form-data">
                              <div class="form-group">
                                  <span>Title <i style="color: red;"><small>(Required text less than 100 characters.)</small></i></span>
                                  <input type="text" id="input_title" class="form-control" name="title"  placeholder="*Input Title" required>
                                  <input type="hidden" name="type" value="firebase_notification" required>
                                </div>
                                <div class="form-group d-none">
                                  <span>Link To</span>
                                  <input type="text" class="form-control" name="link_to" value="PE App" placeholder="*Input Title" required>
                                </div>
                                <div class="form-group">
                                  <span>Description</span>
                                  <textarea placeholder="*Input Description" id="input_description" name="description" rows="6" class="form-control" <?php echo $show_notification == true ? '' : 'disabled' ?> required><?php echo @$description->description ?></textarea>
                                </div>
                                <div class="form-group">
                                  <span>Image</span>
                                  <div class="d-flex">
                                    <div class="radio-custom radio-primary mr-4">
                                      <input class="typeInsert" id="link" name="jenis" type="radio" value="link" checked>
                                      <label class="font-size-14" for="link">Link / URL</label>
                                    </div>
                                    <div class="radio-custom radio-primary mb-2">
                                      <input class="typeInsert" id="uploadsImage" name="jenis" type="radio" value="file">
                                      <label class="font-size-14" for="uploadsImage">Upload Image</label>
                                    </div>
                                    <div class="radio-custom radio-primary mb-2" style="margin-left:10px">
                                    <div class="radio-custom radio-primary mb-2" style="margin-left:10px; display: none;">
                                      <input class="typeInsert" id="delete-image" name="jenis" type="radio" value="no_image">
                                      <label class="font-size-14" for="uploadsImage">No Image</label>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label">Select Image</label>
                                      <input type="text" name="url_link" value="<?php echo $data_notif->image ?>" class="form-control" id="linkInput" placeholder="eg.: google.co.id/Banner.jpeg">
                                      <input type="file" name="fileToUpload"onchange="previewFile(this); class="form-control" <?php echo $show_notification == true ? '' : 'disabled' ?> id="fileinput" style="display: none;">
                                      <?php if ($data_notif->image): ?>
                                        <img src="<?php echo $data_notif->image ?>" id="intputblah" class="img-fluid mt-3" style="width: 100px">
                                      <?php endif ?>
                                      <p class="mb-0 mt-2" style="line-height: 15px">
                                        <small class="text-danger">Best Size : 100 X 100px</small> <br>
                                        <small class="text-danger">Max File Size : 2MB</small>
                                      </p>
                                  </div>
                                </div>
                                <div class="mt-2 text-right">
                                  <button type="submit" class="btn btn-primary" <?php echo $show_notification == true ? '' : 'disabled' ?>>Submit</button>
                                </div>
                            </form>
                          </div>
                        </div>

                        <!-- repush notification -->

                        <table class="table table-bordered mt-5 repush-notification">
                          <thead>
                            <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Title</th>
                              <th scope="col">Description</th>
                              <th scope="col">created_at</th>
                              <th scope="col">Image</th>
                              <th scope="col">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                                // select data re_send push nitification
                                $no_of_records_per_page = 7;
                                if(isset($_GET['page_number'])){
                                  $page = $_GET['page_number'];
                                }else{
                                  $page = 1;
                                }

                                $offset = ($page-1)*$no_of_records_per_page; 
      
                                
                                $re_send = $wpdb->get_results("SELECT * FROM `revo_mobile_variable` WHERE `slug` LIKE 'firebase_notification' ORDER BY id DESC LIMIT $offset,$no_of_records_per_page");
                                
                                $i = 1;
                                
                                foreach($re_send as $key=>$value){
                                    ?>
                                      <tr>
                                          <td scope="row"> <?php echo $i; ?></td>
                                          <td class ="title_resend">
                                            <input class ="resend_title" type="hidden" value="<?php echo $value->title ?>">
                                            <?php echo $value->title ?>
                                          </td>
                                          <td class ="descript_resend">
                                            <input class="description_resend" type="hidden" value="<?php echo $value->description ?>">  
                                            <?php echo $value->description ?>
                                          </td>
                                          <td><?php echo $value->created_at ?></td>
                                          <td hidden class="link_resend"><?php echo $value->image ?></td>
                                          <td class ="image_resend"><img src="<?php echo $value->image ?>" alt=""></td>
                                          <td>
                                              <button type="submit" class="btn btn-danger copy_resend">Copy</button>
                                          </td>
                                      </tr>
                                    <?php
                                  $i++;  
                                }  
                            ?>
                          </tbody>
                        </table>
                        <div class="pagination-ponotification">
                          <nav aria-label="Page navigation example">
                            <ul class="pagination">
                              <li class="page-item"><a class="page-link" href="<?php echo site_url()?>/wp-admin/admin.php?page=revo-post-notification&page_number=<?php echo $page-1 ?>"><i class="fa fa-chevron-left"></i></a></li>
                                  <?php
                                    $re_send = $wpdb->get_results("SELECT * FROM `revo_mobile_variable` WHERE `slug` LIKE 'firebase_notification' ORDER BY id DESC LIMIT 70");
                                    $total_page = ceil(count($re_send)/$no_of_records_per_page);

                                    for($i=1;$i<=$total_page;$i++){
                                      ?>
                                        <li class="page-item"><a class="page-link  <?php if($page==$i){echo 'active_background';} ?>" href="<?php echo site_url()?>/wp-admin/admin.php?page=revo-post-notification&page_number=<?php echo $i ?>"><?php echo $i ?></a></li> 
                                      <?php
                                    }
                                  ?>
                              <li class="page-item"><a class="page-link" <?php if($page>=$total_page ){echo 'style="display:none"';} ?>  href="<?php echo site_url()?>/wp-admin/admin.php?page=revo-post-notification&page_number=<?php echo $page+1 ?>"><i class="fa fa-chevron-right"></i></a></li>
                            </ul>
                          </nav>
                        </div>
                      </div>
                    </section>
                </section>
            </div>
        </section>
        <div class="modal fade" id="tambahintropage" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="exampleModalLabel">Form Input API KEY Firebase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" action="#">
                  <div class="form-group">
                    <span>Server KEY <span class="text-danger font-size-12">*Required Push Notification</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_servey_key ?>" name="firebase_servey_key"  placeholder="*ex : AAAAL4gX-Xo:APA91bEpfkLw0F8ju_11FVw8RYuleoIve9uUP7QvoYJbT-q4kT7wjBxqN_2gBHhTl*******">
                  </div>
                  <div class="form-group">
                    <span>Messaging Sender Id <span class="text-danger font-size-12">*Required Push Notification</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_messaging_sender_id ?>" name="firebase_messaging_sender_id"  placeholder="*ex : 123456789">
                  </div>
                  <div class="form-group">
                    <span>Api key <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="hidden" name="id" value="<?php echo $data->id ?>" required>
                    <input type="hidden" name="type" value="firebase_key" required>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_api_key ?>" name="firebase_api_key"  placeholder="*Input AIzaSyCYkikCSaf91MbO6f3xE********">
                  </div>
                  <div class="form-group">
                    <span>Auth Domain <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_auth_domain ?>" name="firebase_auth_domain"  placeholder="*ex : project-id.firebaseapp.com">
                  </div>
                  <div class="form-group">
                    <span>Database URL <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_database_url ?>" name="firebase_database_url"  placeholder="*ex : https://project-id.firebaseio.com">
                  </div>
                  <div class="form-group">
                    <span>Project Id <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_project_id ?>" name="firebase_project_id"  placeholder="*ex : projek-revo">
                  </div>
                  <div class="form-group">
                    <span>Storage Bucket <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_storage_bucket ?>" name="firebase_storage_bucket"  placeholder="*ex : project-id.appspot.com">
                  </div>
                  <div class="form-group">
                    <span>AppId <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_app_id ?>" name="firebase_app_id"  placeholder="*ex : 1:2019186***:web:dda924d*******">
                  </div>
                  <div class="form-group">
                    <span>MeasurementId <span class="text-danger font-size-12">*Required OTP Login & Register</span></span>
                    <input type="text" class="form-control" value="<?php echo $data->firebase_measurement_id ?>" name="firebase_measurement_id"  placeholder="*ex : G-HNR4*****">
                  </div>
                  <div class="mt-2 text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
    <script>
        function previewFile(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              
              reader.onload = function (e) {
                  jQuery('#intputblah').attr('src', e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
        }
        jQuery("#fileinput").change(function(){
          previewFile(this);
        });
        // hide image when push notofication
        jQuery(document).ready(function(){
          jQuery("#delete-image").click(function(){
              jQuery("#intputblah").hide();
          });

          jQuery("#link").click(function(){
              jQuery("#intputblah").show();
          });

          jQuery("#uploadsImage").click(function(){
              jQuery("#intputblah").show();
          });
        });

        // appent notification
        jQuery(document).ready(function(){
          jQuery(".copy_resend").click(function(){
            var tr                 = jQuery(this).parent().parent();
            var resend_title       = tr.find('.resend_title').val();
            var description_resend = tr.find('.description_resend').val();
            var link_resend        = tr.find('.link_resend').text();
            var image_resend       = tr.find('.image_resend > img').attr("src");

            jQuery('#input_title').val(resend_title); 
            jQuery('#input_description').val(description_resend);
            jQuery('#linkInput').val(link_resend);
            jQuery('#intputblah').attr("src",image_resend);
          })
        });


    </script>
    <?php include (plugin_dir_path( __FILE__ ).'partials/_js.php');  ?>
  </html>