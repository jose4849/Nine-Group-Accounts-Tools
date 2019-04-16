
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Repeating Income</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<div class="col-md-10 col-lg-10 col-sm-10">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Repeating Income</div>
    <div class="panel-body add-client">
    <form id="add-repeat-income" method="post" action="<?php echo site_url('Admin/repeatIncome/insert') ?>">
  <input type="hidden" name="action" id="action" value="insert"/>  
 <input type="hidden" name="trans_id" id="trans_id" value=""/>    
 <div class="col-md-6 col-lg-6 col-sm-6">    
 <div class="form-group"> 
    <label for="account">Account Name</label>
    <select name="account" class="form-control" id="account"> 
      <?php foreach ($accounts as $account) {?>
      <option value="<?php echo $account->accounts_name ?>"><?php echo $account->accounts_name ?></option>
      <?php } ?> 
    </select>      
  </div> 
  </div>
   <div class="col-md-6 col-lg-6 col-sm-6"> 
 <div class="form-group">
     <label for="account">Date</label>
    <div class='input-group date' id='date'>
        <input type='text' name="income-date" id="income-date" class="form-control" />
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
 </div>
</div> 
 
  <div class="col-md-6 col-lg-6 col-sm-6"> 
    <div class="form-group">
    <label for="reference">Rotation</label>
	<select name="rotation" class="form-control" id="rotation">  
	<option value="30">Monthly</option>
	<option value="7">Weekly</option>
	<option value="14">Bi Weekly</option>
	<option value="1">Everyday</option>
	<option value="60">Every 2 Month</option>
	<option value="120">Quarterly</option>
	<option value="180">Every 6 Month</option>
	<option value="365">Yearly</option>
    </select> 
  </div>  
</div>
 
<div class="col-md-6 col-lg-6 col-sm-6"> 
  <div class="form-group">
    <label for="rotation-income">Number Of Rotation (Income)</label>
    <input type="text" class="form-control" name="rotation-income" id="rotation-income">
  </div>  
</div>
  
   <div class="col-md-6 col-lg-6 col-sm-6">                   
  <div class="form-group"> 
    <label for="income-type">Income Type</label>
    <select name="income-type" class="form-control" id="income-type">  
       <?php foreach ($category as $cat) {?>
      <option value="<?php echo $cat->accounts_name ?>"><?php echo $cat->accounts_name ?></option>
      <?php } ?>   
    </select>      
  </div>         
  </div>
  
 <div class="col-md-6 col-lg-6 col-sm-6">                                     
  <div class="form-group">
    <label for="amount">Amount</label>
      <div class='input-group date'>
        <div class="input-group-addon">$</div>  
        <input type='text' name="amount" id="amount" class="form-control" />
    </div>
  </div>
</div>    

    <div class="col-md-6 col-lg-6 col-sm-6"> 
    <div class="form-group"> 
    <label for="payer">Payer</label>
    <select name="payer" class="form-control" id="payer">  
    <?php foreach ($payers as $p) {?>
    <option value="<?php echo $p->payee_payers ?>"><?php echo $p->payee_payers ?></option>
    <?php } ?>   
    </select>      
    </div>  
   </div>
    
     <div class="col-md-6 col-lg-6 col-sm-6"> 
    <div class="form-group"> 
    <label for="p-method">Payment Method</label>
    <select name="p-method" class="form-control" id="p-method">
     <?php foreach ($p_method as $method) {?>
      <option value="<?php echo $method->p_method_name ?>"><?php echo $method->p_method_name ?></option>
    <?php } ?>   
    </select>      
     </div>
    </div> 
       
 <div class="col-md-6 col-lg-6 col-sm-6"> 
    <div class="form-group">
    <label for="reference">Reference No</label>
    <input type="text" class="form-control" name="reference" id="reference">
  </div>  
</div>
   
  <div class="col-md-6 col-lg-6 col-sm-6"> 
    <div class="form-group">
    <label for="note">Note</label>
    <input type="text" class="form-control" name="note" id="note">
  </div>     
</div>
    
<div class="col-md-6 col-lg-6 col-sm-6">      
<button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Submit</button>
</div>

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
$(".asyn-repeat-income").addClass("active-menu"); 

$("#rotation-income").keypress(function (e) {
if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
return false;
}
}); 

$('#amount').keypress(function(event) { 
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && 
    (event.which < 48 || event.which > 57)) { event.preventDefault(); 
  } 
}); 
 
$("#account").select2();
$("#income-type").select2();
$("#payer").select2(); 
$("#p-method").select2();     
$("#date").datepicker();  

$('#add-repeat-income').on('submit',function(event){ 
$.ajax({
method : "POST",
url : "<?php echo site_url('Admin/repeatIncome/insert') ?>",
data : $(this).serialize(),
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){  
if(data=="true"){   
sucessAlert("Saved Sucessfully"); 
$(".block-ui").css('display','none');
$('#add-repeat-income')[0].reset(); 
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

