
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Edit Transaction</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Edit Transaction</div>
    <div class="panel-body add-client">
    <form id="edit-transaction">
 <input type="hidden" name="action" id="action" value="update"/>  
 <input type="hidden" name="trans_id" id="trans_id" value="<?php echo $transaction->trans_id ?>"/>       
 <div class="form-group"> 
    <label for="from-account">Account</label>
    <select name="account" class="form-control" id="account" disabled>
    <option value="<?php echo $transaction->accounts_name ?>"><?php echo $transaction->accounts_name ?></option>  
    </select>      
  </div> 
  
  
 <div class="form-group">
     <label for="account">Date</label>
    <div class='input-group date' id='date'>
        <input type='text' name="date" id="date" value="<?php echo $transaction->trans_date ?>" class="form-control"/>
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
 </div> 
                
                 
  <div class="form-group">
    <label for="amount">Amount</label>
      <div class='input-group date'>
        <div class="input-group-addon">$</div>  
        <input type='text' name="amount" id="amount" value="<?php echo $transaction->amount ?>" class="form-control" disabled/>
        <div class="input-group-addon">.00</div>
    </div>
  </div>


    <div class="form-group"> 
    <label for="p-method">Payment Method</label>
    <select name="p-method" class="form-control" id="p-method">  
    <?php foreach ($p_method as $method) {?>
      <option value="<?php echo $method->p_method_name ?>"><?php echo $method->p_method_name ?></option>
    <?php } ?>   
    </select>      
  </div> 

    <div class="form-group">
    <label for="reference">Reference No</label>
    <input type="text" class="form-control" value="<?php echo $transaction->ref ?>" name="reference" id="reference">
  </div>  
    
    <div class="form-group">
    <label for="note">Note</label>
    <input type="text" class="form-control" value="<?php echo $transaction->note ?>" name="note" id="note">
  </div>     
        
        
  <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Submit</button>
</form>
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

if($(".sidebar").width()=="0"){
$(".main-content").css("padding-left","0px");
}  



$("#account").select2();
$("#income-type").select2();
$("#payer").select2(); 
$("#p-method").select2();     
$("#date").datepicker();  

$("#p-method").select2("val","<?php echo $transaction->p_method ?>"); 

 $("#amount").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
        //display error message
        return false;
    }
   });

$('#edit-transaction').on('submit',function(event){ 
$.ajax({
method : "POST",
url : "<?php echo site_url('Admin/updateTransaction') ?>",
data : $(this).serialize(),
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){  
if(data=="true"){   
sucessAlert("Saved Sucessfully"); 
$(".block-ui").css('display','none');
$('#edit-transaction')[0].reset(); 
}else{
failedAlert2(data);
$(".block-ui").css('display','none');
}   
}
});    
return false;
});

  

});

</script>

