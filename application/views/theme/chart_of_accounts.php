<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Chart Of Accounts</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Income/Expense Category</div>
    <div class="panel-body add-client">
    <form id="add-Chart-account">
 <input type="hidden" name="action" id="action" value="insert"/>  
 <input type="hidden" name="chart_id" id="chart_id" value=""/>    
 <div class="form-group"> 
    <label for="account">Category Name</label>
    <input type="text" maxlength="30" class="form-control" name="account" id="account"/>   
  </div> 

        
  <div class="form-group"> 
    <label for="account-type">Account Type</label>
    <select name="account-type" class="form-control" id="account-type"> 
    <option value="Income">Income</option>
    <option value="Expense">Expense</option> 
    </select>      
  </div>                       
        
  <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
</form>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->       
</div>
    
  
    
<div class="col-md-7 col-lg-7 col-sm-7">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Chart Of Accounts</div>
    <div class="panel-body">
       <table class="scroll-table-head table table-striped table-bordered">
        <th class="sc-col-4">Account Name</th><th class="sc-col-3">Acc-Type</th><th class="sc-col-3">Action</th>
        </table> 
         
        <div class="scroll-div"> 
        <table id="accounts-table" class="table table-striped table-bordered">
        <?php  if(empty($accountList)){
        echo "<tr><td class='sc-col-4 t_name'></td><td class='sc-col-3 t_type'></td>";
        echo "<td class='sc-col-3'></td></tr>";
        }?>    
       <?php foreach($accountList as $list){ ?>
       
        <tr>
        <td class="a_name sc-col-4"><?php echo $list->accounts_name ?></td>
        <td class="a_type sc-col-3"><?php echo $list->accounts_type ?></td> 
        <td class="sc-col-3"><a class="mybtn btn-info btn-xs chart-edit-btn"  href="<?php echo $list->chart_id ?>">Edit</a>
        <a class="mybtn btn-danger btn-xs chart-remove-btn"  href="<?php echo site_url('Admin/chartOfAccounts/remove/'.$list->chart_id) ?>">Remove</a></td>     
        </tr>
        
        <?php } ?>
  
        </table>
    </div>
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
var myRow;    
$("#account-type").select2();
$("#account-type").select2("val",""); 

$(".scroll-div").niceScroll({
cursorwidth: "8px",cursorcolor:"#7f8c8d",cursorborderradius:"0px"
});

$('#add-Chart-account').on('submit',function(){    
$.ajax({
method : "POST",
url : "<?php echo site_url('Admin/chartOfAccounts/insert') ?>",
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
$('#add-Chart-account')[0].reset(); 
$("#account-type").select2("val","");     
}else{
failedAlert(json['message']);
$(".block-ui").css('display','none');
}   
}
});    
return false;
});

$(document).on('click','.chart-remove-btn',function(){  
var main=$(this);
swal({title: "Are you sure Want To Delete?",
text: "You will not be able to recover this Data!",
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
    
$(document).on('click','.chart-edit-btn',function(){ 
var main=$(this);
$("#action").val("update");
$("#chart_id").val($(main).attr("href"));
$("#account").val($(main).closest("tr").find(".a_name").html());  
$("#account-type").select2("val",$(main).closest("tr").find(".a_type").html()); 
//get table index
var tr = $(main).closest('tr');
myRow = tr.index();

return false;    
}); 


function appendRow(id){
var base='<?php echo base_url() ?>'+'Admin/chartOfAccounts/remove/'+id;    
var edit_link="<a class='mybtn btn-info btn-xs chart-edit-btn' href='"+id+"'>Edit</a>";
var remove_link="<a class='mybtn btn-danger btn-xs chart-remove-btn' href='"+base+"'>Remove</a>";
/*
$("#accounts-table tr:first").after("<tr><td class='a_name sc-col-4'>"+$("#account").val()+"</td>"+
"<td class='a_type sc-col-3'>"+$("#account-type").val()+"</td><td class='sc-col-3'>"+edit_link+" "+remove_link+"</td></tr>"); 
*/
$("<tr><td class='a_name sc-col-4'>"+$("#account").val()+"</td>"+
"<td class='a_type sc-col-3'>"+$("#account-type").val()+"</td><td class='sc-col-3'>"+edit_link+" "+remove_link+"</td></tr>").insertBefore("#accounts-table tr:first");
}

function updateRow(){
var an=$("#account").val();
var at=$("#account-type").val();

var x = document.getElementById("accounts-table").rows[myRow].cells;
x[0].innerHTML = an;
x[1].innerHTML = at;
$("#action").val("insert");
}
  
    
});

</script>

