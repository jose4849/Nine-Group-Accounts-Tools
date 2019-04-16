<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>User Management</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add User</div>
    <div class="panel-body add-client">
    <form id="add-user">
 <input type="hidden" name="action" id="action" value="insert"/>  
 <input type="hidden" name="user_id" id="user_id" value=""/>     
<div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" maxlength="15" name="username" id="username" />
</div>
        
 <div class="form-group">
    <label for="fullname">Fullname</label>
    <input type="text" class="form-control" maxlength="30" name="fullname" id="fullname" />
</div>  

 <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" maxlength="60" name="email" id="email" />
</div>                     

        
  <div class="form-group"> 
    <label for="user-type">User Type</label>
    <select name="user-type" class="form-control" id="user-type"> 
    <option value="Admin">Admin</option>
    <option value="Employee">Employee</option> 
    </select>
    <span class="help_info">Choose User Type Employee to disable access to 
    Administrative work</span>    
  </div> 
                
   <div class="form-group"> 
    <label for="user-password">User Password</label>
    <input type="password" maxlength="15" minlength="5" class="form-control" name="user-password" id="user-password"/>   
    <span class="help_info for-update">Keep Default Hash to do not change Password</span>
  </div>                                 
                 
        
  <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
</form>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->       
</div>
    
<!-- Start Table Section -->    
    
<div class="col-md-7 col-lg-7 col-sm-7">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">User List</div>
    <div class="panel-body">
       <table id="user-table" class="table table-striped table-bordered table-condensed">
        <th>Username</th><th>User Type</th><th>Action</th>
       <?php foreach($users as $user){ ?>
        <tr>
        <td><?php echo $user->user_name ?></td>
        <td><?php echo $user->user_type ?></td> 
        <td><a class="mybtn btn-info btn-xs edit-user-btn" href="<?php echo site_url('Admin/getUser/'.$user->user_id) ?>">Edit</a>
        <!--<a class="mybtn btn-danger btn-xs remove-btn" href="<?php echo site_url('Admin/userManagement/remove/'.$user->user_id) ?>">Remove</a>-->
        </td>     
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
$(document).ready(function() {

$("#user-type").select2();

$('#add-user').on('submit',function(){    
$.ajax({
method : "POST",
url : "<?php echo site_url('Admin/userManagement/insert') ?>",
data : $(this).serialize(),
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){ 
 var json = JSON.parse(data);   
if(json['result']=="true" ){  
if(json['action']=="insert"){
appendRow(json['last_id']);
}else if(json['action']=="update"){
updateRow();
}      
sucessAlert("Saved Sucessfully"); 
$(".block-ui").css('display','none');        
$('#add-user')[0].reset();
$(".for-update").css("display","none")     
}else{ 
if(json['result']=="false"){
failedAlert(json['message']);
}else{  
failedAlert2(json);
}
$(".block-ui").css('display','none');
}   
}
});    
return false;
}); 

$(document).on('click','.remove-btn',function(){  
var main=$(this);    
var link=$(main).attr("href");    
$.ajax({
url : link,
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){
$(main).closest("tr").remove();    
sucessAlert("Remove Sucessfully"); 
$(".block-ui").css('display','none');
}    
});
return false;       
}); 
    
$(document).on('click','.edit-user-btn',function(){
var main=$(this); 
var url=$(main).attr("href");
$.ajax({url:url,beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){  
var json = JSON.parse(data);
$("#username").val(json['user_name']);
$("#fullname").val(json['fullname']);
$("#email").val(json['email']);
$("#user-password").val(json['password']);
$("#user-type").select2("val",json['user_type']);  
$("#action").val("update");
$("#user_id").val(json['user_id']);
$(".for-update").css("display","inline");
$(".block-ui").css('display','none');
}
});
//get table index
var tr = $(main).closest('tr');
myRow = tr.index(); 
return false;    
}); 

function appendRow(id){
var base='<?php echo base_url() ?>'+'Admin/getUser/'+id;    
var edit_link="<a class='mybtn btn-info btn-xs edit-user-btn' href='"+base+"'>Edit</a>";

$("#user-table tr:first").after("<tr><td>"+$("#username").val()+"</td>"+
"<td>"+$("#user-type").val()+"</td><td>"+edit_link+"</td></tr>"); 

}

function updateRow(){
var an=$("#username").val();
var at=$("#user-type").val();

var x = document.getElementById("user-table").rows[myRow].cells;
x[0].innerHTML = an;
x[1].innerHTML = at;
$("#action").val("insert");
}    


 
});

</script>

