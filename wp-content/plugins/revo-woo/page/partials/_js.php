 <!-- Vendor -->
 <script src="<?php echo revo_url() ?>assets/vendor/jquery/jquery.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>

 <!-- Specific Page Vendor -->
 <script src="<?php echo revo_url() ?>assets/vendor/select2/js/select2.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
 <script src="<?php echo revo_url() ?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

 <!-- Theme Base, Components and Settings -->
 <script src="<?php echo revo_url() ?>assets/javascripts/theme.js"></script>

 <!-- Theme Custom -->
 <script src="<?php echo revo_url() ?>assets/javascripts/theme.custom.js"></script>

 <!-- Theme Initialization Files -->
 <script src="<?php echo revo_url() ?>assets/javascripts/theme.init.js"></script>

 <!-- Examples -->
 <script src="<?php echo revo_url() ?>assets/javascripts/tables/examples.datatables.default.js"></script>
 <script src="<?php echo revo_url() ?>assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
 <script src="<?php echo revo_url() ?>assets/javascripts/tables/examples.datatables.tabletools.js"></script>

 <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

 <script type="text/javascript">

	$(document).ready(function() {
      $('.typeInsert, input[type=radio][name=jenis]').change(function(e) {
        e.preventDefault();
        modalEl = $(e.target).parents('.modal');
        const fdId = modalEl.attr('fd-id') ?? '';
          if (this.value == 'file') {
            $('#linkInput'+fdId).css("display", "none");
            $('#linkInput'+fdId).removeAttr("required");
            $('#fileinput'+fdId).css("display", "block");
            $('#fileinput'+fdId).attr("required","");
          }
          else if (this.value == 'link') {
            $('#linkInput'+fdId).css("display", "block");
            $('#linkInput'+fdId).attr("required", "");
            $('#fileinput'+fdId).css("display", "none");
            $('#fileinput'+fdId).removeAttr("required");
          }
      });

       $('.updateFile, input[type=radio][name=jenis]').change(function() {
          var id = $(this).attr("categoriesid");
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

    $('#datatable-default tbody').on('click', '.updateFile', function () {
        var post_id;
        var id = $(this).attr("categoriesid");
        var banner_id = $(this).attr("bannerid");
        if(id == 'undefined') {
            post_id = id;
        }
        else {
            post_id = banner_id;
        }
        if (this.value == 'file') {
            $('#linkInput' + post_id).css("display", "none");
            $('#linkInput' + post_id).removeAttr("required");
            $('#fileinput' + post_id).css("display", "block");
            $('#fileinput' + post_id).attr("required","");
        }
        else if (this.value == 'link') {
            $('#linkInput' + post_id).css("display", "block");
            $('#linkInput' + post_id).attr("required", "");
            $('#fileinput' + post_id).css("display", "none");
            $('#fileinput' + post_id).removeAttr("required");
        }
    });

 </script>