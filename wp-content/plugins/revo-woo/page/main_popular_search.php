<?php 

 require (plugin_dir_path( __FILE__ ).'../helper.php');

global $wpdb;

// @Insert search text
if(isset($_POST['submit'])){
  $search_key  = $_POST['search_key'];
  $search_text = strtolower($_POST['search_text']);
  $data_insert = $wpdb->insert("revo_count_search", array(
    "search_count" => $search_key,
    "search_text"  => $search_text,
  )); 
  if (@$wpdb->insert_id > 0) {
    $alert = array(
      'type' => 'success', 
      'title' => 'Success !',
      'message' => 'Slider Success Saved', 
    );
  }
  $_SESSION["alert"] = $alert;
} 

    
// @update search text
if(isset($_POST['submit_update'])){
  $search_id    = $_POST['search_id'];
  $search_count = $_POST['search_key'];
  $search_text  =$_POST['search_text'];

  $wpdb->update('revo_count_search',
    array(
      'search_text'  => $search_text ,
      'search_count' => $search_count
    ),
    array('id' => $search_id) ,
    array('%s' ,'%d') ,
    array('%d')
  );
  if (@$wpdb->show_errors == false) {
    $alert = array(
      'type' => 'success', 
      'title' => 'Success !',
      'message' => 'Slider Success Updated', 
    );
  }

  $_SESSION["alert"] = $alert;
}

// @delete search text
if(isset($_POST["typeQuery"])) {
  if($_POST["typeQuery"] == 'hapus') {

    header('Content-type: application/json');

    $search_delete = $_POST['id'];
    $query         = $wpdb->delete('revo_count_search',array('id'=>$search_delete));

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

// @select search text
$result_select = $wpdb->get_results(" SELECT * FROM revo_count_search ORDER BY id  ASC LIMIT 15");

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
                      <h4>Polular Search <?php echo buttonQuestion() ?></h4>
                    </div>
                    <div class="col-6 text-right">
                      <button class="btn btn-primary"  data-toggle="modal" data-target="#Banner">
                        <i class="fa fa-plus"></i> Add Popular Search
                      </button>
                    </div>
                  </div>
                  <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                      <tr>
                        <th>id</th>
                        <th>Search Text</th>
                        <th>Count</th>
                        <th class="hidden-xs">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      foreach ($result_select as $key=>$value): ?>
                        <tr>
                          <td><?php echo $value->id?></td>
                          <td><?php echo $value->search_text?></td>
                          <td><?php echo $value->search_count?></td>
    
                          <td>
                            <button class="btn btn-primary"  data-toggle="modal" data-target="#Banner<?php echo $value->id?>">
                              <i class="fa fa-edit"></i> Update
                            </button>
                                <div class="modal fade" id="Banner<?php echo $value->id ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title font-weight-600" id="exampleModalLabel">Update <?php echo $value->search_count.' - Urutan ke '.$value->search_text ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <!-- @update search text -->
                                        <form method="post" >
                                            <div class="panel-body">
                                                <input type="text" name="search_id" value="<?php echo $value->id ?>" hidden>
                                                <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Search Text<span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                    <input type="input" value="<?php echo $value->search_text; ?>" name="search_text" class="form-control"  placeholder="search_text" required="" aria-required="true">
                                                  </div>
                                                </div>     
                                                <div class="form-group">
                                                  <label class="col-sm-4 pl-0 control-label">Search Count<span class="required" aria-required="true">*</span></label>
                                                  <div class="col-sm-8">
                                                    <input type="input" value="<?php echo $value->search_count; ?>" name="search_key" class="form-control"  placeholder="search_count" required="" aria-required="true">
                                                  </div>
                                                </div>                                
                                                <div class="form-group text-right mt-5">
                                                  <div class="col-sm-12">
                                                    <button type="submit" name="submit_update" class="btn btn-primary">
                                                      <i class="fa fa-send"></i> Submit
                                                    </button>
                                                  </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                                  <button class="btn btn-danger btn-remove"  onclick="hapus('<?php echo $value->id ?>')">
                                    <i class="fa fa-trash"></i> Delete
                                  </button> 
                          
                          </td>
                        </tr>
                      <?php 
                        endforeach;
                        if (!empty($result_select)) {
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
   <!-- insert table -->
  <div class="modal fade" id="Banner" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-600" id="exampleModalLabel">Add Popular Search</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="post">
              <div class="form-group">
                <label class="col-sm-4 pl-0 control-label">Search Text<span class="required" aria-required="true">*</span></label>
                <div class="col-sm-8">
                  <input type="input" value="" name="search_text" class="form-control"  placeholder="search_text" required="" aria-required="true">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 pl-0 control-label">Search Count<span class="required" aria-required="true">*</span></label>
                <div class="col-sm-8">
                  <input type="input" value="" name="search_key" class="form-control"  placeholder="search_count" required="" aria-required="true">
                </div>
              </div>
              <div class="form-group text-right mt-5">
                <div class="col-sm-12">
                  <button type="submit" name="submit"class="btn btn-primary">
                    <i class="fa fa-send"></i> Submit
                  </button>
                </div>
              </div>
          </form>
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
              jQuery.ajax({
                  url: "#",
                  method: "POST",
                  data: {
                    id:id ,
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