<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Calendar</h4></div>
<div class="col-md-10 col-lg-10 col-sm-10">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Expense Calendar <div class="add-button">
    <a class="mybtn btn-default" href="">Add Expense</a>
    </div></div>
    <div class="panel-body">
    <!--Start Income Calendar-->
        
    <div id='expense-calendar'></div>  
    
    <!--End Income Calendar-->    
        
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
$(".manage-client").niceScroll({
cursorwidth: "8px",cursorcolor:"#7f8c8d"
});

 $('#expense-calendar').fullCalendar({
        events: [
         <?php foreach($repeat_expense as $expense){
         $url='';   
         if($expense->status=='paid'){
         $url=1;   
         }else{
         $url=site_url('Admin/processRepeatTransaction/'.$expense->trans_id.'/paid');  
         }

         ?>
        {
            title: "<?php echo $expense->description ?>",
            start: '<?php echo $expense->date ?>',
            url:"<?php echo $url ?>"
        },     
        <?php } ?>
       
    ],
    color: 'yellow', 
    textColor: 'black',
    dayClick: function(date, jsEvent, view) {

        alert('Clicked on: ' + date.format());

    },
        eventRender: function (event, element) {
        element.attr('href', 'javascript:void(0);');
        element.click(function() {
        //alert(event.title);
        //showInfo(event.title);
        showExpense(event.title,event.start.format(),event.url,event); 
        $('#expense-calendar').fullCalendar('updateEvent', event);    
        });
    } 
     
     
 });
    
});

</script>

