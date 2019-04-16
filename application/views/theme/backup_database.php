<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Backup Database</h4></div>

<!--Alert-->
<div class="alert alert-success ajax-notify"></div>
<!--End Alert-->


<!--<div class="col-md-5 col-lg-5 col-sm-5">

<div class="panel panel-default">

    <div class="panel-heading">Restore Database</div>
    <div class="panel-body add-client">
<?php 
$attributes = array('id' => 'restore-database');
echo form_open_multipart('Admin/backupDatabase/restore',$attributes);
?>
 <div class="form-group"> 
    <label for="account">Restore Database</label>
    <input type="file" class="form-control" name="restore-file"/>   
  </div> 
              
  <button type="submit"  class="mybtn btn-submit"><i class="fa fa-history"></i> Restore</button>
  </form>
  </div>

</div>
    
</div>-->
    
  
    
<div class="col-md-7 col-lg-7 col-sm-7">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Backup Database</div>
    <div class="panel-body">
       <table class="table table-striped table-bordered table-condensed">
        <th>Table Name</th><th width="60">Backup</th>
       
        <tr>
        <td>accounts</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/accounts" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>chart_of_accounts</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/chart_of_accounts" ?>"><i class="fa fa-download"></i></a></td> 
        </tr>

        <tr>
        <td>language</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/language" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>payee_payers</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/payee_payers" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>payment_method</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/payment_method" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>repeat_transaction</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/repeat_transaction" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>settings</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/settings" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

         <tr>
        <td>transaction</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/transaction" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>user</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/user" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>

        <tr>
        <td>All</td>
        <td><a class="btn btn-default download-backup" data-toggle="tooltip" 
        title="Download Backup" href="<?php echo site_url('Admin/backupDatabase')."/backup/all" ?>"><i class="fa fa-download"></i></a></td>  
        </tr>


        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->       
</div>    
    

</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->


<script type="text/javascript">
$(document).ready(function() {
$('[data-toggle="tooltip"]').tooltip(); 
  

});

</script>

