
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Transfer</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Transfer</div>
    <div class="panel-body add-client">
    <form id="add-transfer">
 <input type="hidden" name="action" id="action" value="insert"/>  
 <input type="hidden" name="trans_id" id="trans_id" value=""/>       
 <div class="form-group"> 
    <label for="from-account">Account From</label>
    <select name="from-account" class="form-control" id="from-account">
      <?php foreach ($accounts as $account) {?>
      <option value="<?php echo $account->accounts_name ?>"><?php echo $account->accounts_name ?></option>
      <?php } ?>  
    </select>      
  </div> 
  
  <div class="form-group"> 
    <label for="to-account">Account To</label>
    <select name="to-account" class="form-control" id="to-account"> 
      <?php foreach ($accounts as $account) {?>
      <option value="<?php echo $account->accounts_name ?>"><?php echo $account->accounts_name ?></option>
      <?php } ?>  
    </select>      
  </div> 
  
 <div class="form-group">
     <label for="account">Date</label>
    <div class='input-group date' id='date'>
        <input type='text' name="transfer-date" id="transfer-date" class="form-control" />
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
 </div> 
                
                 
  <div class="form-group">
    <label for="amount">Amount</label>
      <div class='input-group date'>
        <div class="input-group-addon">$</div>  
        <input type='text' name="amount" id="amount" class="form-control" />
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
    <input type="text" class="form-control" name="reference" id="reference">
  </div>  
    
    <div class="form-group">
    <label for="note">Note</label>
    <input type="text" class="form-control" name="note" id="note">
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
 

$("#account").select2();
$("#income-type").select2();
$("#payer").select2(); 
$("#p-method").select2();     
$("#date").datepicker();  

$('#amount').keypress(function(event) { 
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && 
    (event.which < 48 || event.which > 57)) { event.preventDefault(); 
  } 
});

$('#add-transfer').on('submit',function(event){ 
$.ajax({
method : "POST",
url : "<?php echo site_url('Admin/transfer/insert') ?>",
data : $(this).serialize(),
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){  
if(data=="true"){   
sucessAlert("Saved Sucessfully"); 
$(".block-ui").css('display','none');
$('#add-transfer')[0].reset(); 
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

