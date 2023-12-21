<?php 

  require (plugin_dir_path( __FILE__ ).'../helper.php');
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (@$_POST["typeQuery"] == 'insert') {

      if(@$_POST["jenis"] == 'file' || @$_POST["jenis"] == 'link') {
        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Add Data Categories', 
        );

        $image = '';

        if ($_POST["jenis"] == 'file') {
            $uploads_url = WP_CONTENT_URL."/uploads/revo/";
            $target_dir = WP_CONTENT_DIR."/uploads/revo/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $newname =  md5(date("Y-m-d H:i:s")) . "." . $imageFileType;
            $is_upload_error = 0;
            if($_FILES["fileToUpload"]["size"] > 0){

                if ($_FILES["fileToUpload"]["size"] > 500000) {
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
                    $image = $uploads_url.$newname;
                  }else{
                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir.$newname);
                    $image = $uploads_url.$newname;
                  }
                }
            }
        }else{
          $image = $_POST['url_link'];
        }

        if ($image != '') {
          $categories = get_categorys_detail($_POST['category_id']);
          $wpdb->insert('revo_list_categories',                  
          [
            'order_by' => $_POST['order_by'],
            'category_id' => $_POST['category_id'],
            'category_name' => $categories[0]->name,
            'image' => $image 
          ]);

          if (@$wpdb->insert_id > 0) {
            $alert = array(
              'type' => 'success', 
              'title' => 'Success !',
              'message' => 'Categories Success Saved', 
            );
          }
        }

        $_SESSION["alert"] = $alert;
        
      }

    }elseif (@$_POST["typeQuery"] == 'update') {

      if(@$_POST["jenis"] == 'file' || @$_POST["jenis"] == 'link') {

        $categories = get_categorys_detail($_POST['category_id']);
        
        $dataUpdate = array(
                        'order_by' => $_POST['order_by'],
                        'category_id' => $_POST['category_id'],
                        'category_name' => $categories[0]->name,
                    );

        $where = array('id' => $_POST['id']);

        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Update Categories '.$_POST['title'], 
        );

        $image = '';

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

        $wpdb->update('revo_list_categories',$dataUpdate,$where);
        
        if (@$wpdb->show_errors == false) {
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Categories '.$_POST['title'].' Success Updated', 
          );
        }

        $_SESSION["alert"] = $alert;
        
      }

    }elseif (@$_POST["typeQuery"] == 'hapus') {
        header('Content-type: application/json');

        $query = $wpdb->update( 
              'revo_list_categories', ['is_deleted' =>  '1'], 
              array( 'id' => $_POST['id']), 
              array( '%s'), 
              array( '%d' ) 
            );

        $alert = array(
            'type' => 'error', 
            'title' => 'Query Error !',
            'message' => 'Failed to Delete  Categories', 
        );

        if ($query) {
          $alert = array(
            'type' => 'success', 
            'title' => 'Success !',
            'message' => 'Categories Success Deleted', 
          );
        }

        $_SESSION["alert"] = $alert;

        http_response_code(200);
        return json_encode(['kode' => 'S']);
        die();
    }

  }

  $data_banner = $wpdb->get_results("SELECT * FROM revo_list_categories WHERE is_deleted = 0", OBJECT);

  $categories_list = json_decode(get_categorys());
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
                        <h4>
                          Custom Categories <?php echo buttonQuestion() ?>
                        </h4>
                      </div>
                      <div class="col-6 text-right">
                        <button class="btn btn-primary"  data-toggle="modal" data-target="#tambahCategories">
                          <i class="fa fa-plus"></i> Add Categories
                        </button>
                      </div>
                    </div>
                    <table class="table table-bordered table-striped mb-none" id="datatable-default">
                      <thead>
                        <tr>
                          <th>Sort</th>
                          <th>Title Categories</th>
                          <th>Icon</th>
                          <th class="hidden-xs">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        foreach ($data_banner as $key => $value): ?>
                          <tr>
                            <td><?php echo $value->order_by ?></td>
                            <td><?php echo $value->category_name ?></td>
                            <td>
                              <img src="<?php echo $value->image ?>" class="img-fluid" style="width: 100px">
                            </td>
                            <td>
                              <button class="btn btn-primary"  data-toggle="modal" data-target="#updateCategories<?php echo $value->id ?>">
                                <i class="fa fa-edit"></i> Update
                              </button>
                                  <div class="modal fade" id="updateCategories<?php echo $value->id ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title font-weight-600" id="exampleModalLabel">Update  Categories</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <form method="post" action="#" enctype="multipart/form-data">
                                              <div class="panel-body">
                                                <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Select categories Product <span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                    <select id="states" name="category_id" data-plugin-selectTwo class="form-control populate" title="Please select Categories" required>
                                                      <option value="">Choose a Categories</option>
                                                      <?php foreach ($categories_list as $categories): ?>
                                                        <option value="<?php echo $categories->id ?>" <?php echo $value->category_id == $categories->id ? 'selected' : '' ?>><?php echo $categories->text ?></option>
                                                      <?php endforeach ?>
                                                    </select>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Sort to <span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                    <input type="number" value="<?php echo $value->order_by ?>" name="order_by" class="form-control"  placeholder="Number Only" required="" aria-required="true">
                                                    <input type="hidden" value="<?php echo $value->id ?>" name="id" required>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Type Upload Icon <span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                    <div class="d-flex">
                                                      <div class="radio-custom radio-primary mr-4">
                                                        <input id="link<?php echo $value->id ?>" CategoriesID="<?php echo $value->id ?>" class="updateFile" name="jenis" type="radio" value="link" checked>
                                                        <label class="font-size-14" for="link<?php echo $value->id ?>">Link / URL</label>
                                                      </div>
                                                      <div class="radio-custom radio-primary mb-2">
                                                        <input id="uploadsImage<?php echo $value->id ?>" CategoriesID="<?php echo $value->id ?>" class="updateFile" name="jenis" type="radio" value="file">
                                                        <label class="font-size-14" for="uploadsImage<?php echo $value->id ?>">Upload Image</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Select Image <span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                    <input type="hidden" name="typeQuery" value="update">
                                                    <input type="text" name="url_link" class="form-control" id="linkInput<?php echo $value->id ?>" placeholder="eg.: google.co.id/Categories.jpeg" value="<?php echo $value->image ?>" required>
                                                    <input type="file" name="fileToUpload" class="form-control" id="fileinput<?php echo $value->id ?>" style="display: none;">
                                                    <img src="<?php echo $value->image ?>" class="img-fluid my-2" style="width: 100px">
                                                    <p class="mb-0 mt-2" style="line-height: 15px">
                                                      <small class="text-danger">Best Size : 72px X 72px</small> <br>
                                                      <small class="text-danger">Max File Size : 500kb</small>
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
  <div class="modal fade" id="tambahCategories" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-600" id="exampleModalLabel">Add Custom Categories</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="#" enctype="multipart/form-data">
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 pl-0 control-label">Select categories Product <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="hidden" name="typeQuery" value="insert">
                    <select id="states" name="category_id" data-plugin-selectTwo class="form-control populate" title="Please select Categories" required>
                      <option value="">Choose a Categories</option>
                      <?php foreach ($categories_list as $categories): ?>
                        <option value="<?php echo $categories->id ?>"><?php echo $categories->text ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
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
                    <input type="text" name="url_link" class="form-control" id="linkInput" placeholder="eg.: google.co.id/Categories.jpeg" required>
                    <input type="file" name="fileToUpload" class="form-control" id="fileinput" style="display: none;">
                    <p class="mb-0 mt-2" style="line-height: 15px">
                      <small class="text-danger">Best Size : 72px X 72px</small> <br>
                      <small class="text-danger">Max File Size : 500kb</small>
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

  
  <?php 
    $img_example = revo_url().'/assets/extend/images/example_categories.jpg';
    include (plugin_dir_path( __FILE__ ).'partials/_modal_example.php'); 
    include (plugin_dir_path( __FILE__ ).'partials/_js.php'); 
  ?>
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
  </script>
</body>
</html>