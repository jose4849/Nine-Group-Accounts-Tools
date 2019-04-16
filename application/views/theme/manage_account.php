
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Accounts</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Accounts <div class="add-button">
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('Admin/addAccount') ?>">Add Account</a>
    </div></div>
    <div class="panel-body manage-client">
        <table class="table table-striped table-bordered table-condensed">
        <th>Account</th><th>Opening Balance</th><th>Note</th><th class="action">Action</th>
        
        <?php foreach ($accounts as $account) { ?>
        <tr>
        <td><?php echo $account->accounts_name ?></td>
        <td><?php echo $account->opening_balance ?></td>
        <td><?php echo $account->note ?></td>
        <td><a class="mybtn btn-info btn-xs account-edit-btn" href="<?php echo site_url('Admin/editAccount/'.$account->accounts_id) ?>"><i class="fa fa-search"></i> View</a>
        <a class="mybtn btn-danger btn-xs account-remove-btn" href="<?php echo site_url('Admin/addAccount/remove/'.$account->accounts_id) ?>"><i class="fa fa-trash-o"></i> Delete</a></td>    
        </tr>
        <?php } ?>    

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
$(document).ready(function(){
$('.account-remove-btn').on('click',function(){ 
var main=$(this);
swal({title: "Are you sure Want To Delete?",
text: "You will not be able to recover this Account Data!",
type: "warning",   showCancelButton: true,confirmButtonColor: "#DD6B55",   
confirmButtonText: "Yes, delete it!",closeOnConfirm: false,
showLoaderOnConfirm: true }, function(){ 
///////////////    
var link=$(main).attr("href");    
$.ajax({
url : link,
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){
$(main).closest("tr").remove();    
//sucessAlert("Remove Sucessfully");
$(".system-alert-box").empty();
swal("Deleted!", "Remove Sucessfully", "success");  
$(".block-ui").css('display','none');
}    
});
}); 
return false;       
});

$('.account-edit-btn').on('click',function(){
var link=$(this).attr("href"); 
$.ajax({
method : "POST",
url : link,
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){ 
//var link = location.pathname.replace(/^.*[\\\/]/, ''); //get filename only  
history.pushState(null, null,link);  
$('.asyn-div').load(link+'/asyn',function() {
$(".block-ui").css('display','none');     
});     
   
}
});

return false;
}); 
});

</script>