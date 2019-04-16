
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Income</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Income <div class="add-button">
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('Admin/addIncome') ?>">Add Income</a>
    </div></div>
    <div class="panel-body manage-income">
        <table id="manage-income" class="display responsive nowrap" cellspacing="0" width="100%">
        <thead>
        <th>Date</th><th>Account</th><th>Category</th>
        <th>Payer</th><th>Method</th><th>Reference</th> 
        <th>Note</th><th>Amount</th>    
        <th class="single-action">Manage</th>
        </thead>

        <tbody>  
        <?php foreach($income_list as $income){ ?>
        <tr>
        <td><?php echo $income->trans_date ?></td> 
        <td><?php echo $income->accounts_name ?></td>
        <td><?php echo $income->category ?></td>
        <td><?php echo $income->payer ?></td>
        <td><?php echo $income->p_method ?></td>
        <td><?php echo $income->ref ?></td>
        <td><?php echo $income->note ?></td>
        <td><?php echo $income->amount ?></td>    
        <td><a class="mybtn btn-info btn-xs income-manage-btn" href="<?php echo site_url('Admin/editTransaction/'.$income->trans_id) ?>" data-toggle="tooltip" 
        title="Click For Edit">Manage</a></td> 
        </tr>
        <?php } ?> 
              
        </tbody>
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
$("body").tooltip({ selector: '[data-toggle=tooltip]' });    
$(".manage-income").niceScroll({
cursorwidth: "8px",cursorcolor:"#7f8c8d"
});

//data table
$("#manage-income").DataTable({aaSorting : [[0, 'desc']]});
$("#repeat-income-table").DataTable();
$(".dataTables_length select").addClass("show_entries");

$(document).on('click','.income-manage-btn',function(){

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

