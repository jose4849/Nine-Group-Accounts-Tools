</div>
<!--Start Footer-->
<footer id="footer">
<p><b>Copyright</b> &copy; NINESOFT</p>
</footer>

<!--End Footer-->
</div>
</section>
<!--End Content Area-->
</div>
</div><!-- End Wrapper -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url() ?>/theme/js/metisMenu.js"></script>
<script src="<?php echo base_url() ?>/theme/js/bootstrap.js"></script>
<script src="<?php echo base_url() ?>/theme/js/nicescroll.js"></script>
<script src="<?php echo base_url() ?>/theme/js/c3.min.js"></script>
<script src="<?php echo base_url() ?>/theme/js/d3.min.js"></script>
<script src="<?php echo base_url() ?>/theme/js/select2.min.js"></script>   
<script src="<?php echo base_url() ?>/theme/js/bootstrap-datepicker.js"></script>    
<script src="<?php echo base_url() ?>/theme/js/moment.min.js"></script> 
<script src="<?php echo base_url() ?>/theme/js/fullcalendar.min.js"></script> 
<script src="<?php echo base_url() ?>/theme/js/dataTables.min.js"></script>  
<script src="<?php echo base_url() ?>/theme/js/dataTables.responsive.min.js"></script> 
<script src="<?php echo base_url() ?>/theme/js/jquery.slicknav.min.js"></script> 
<script src="<?php echo base_url() ?>/theme/js/sweetalert.min.js"></script>  
<script src="<?php echo base_url() ?>/theme/js/pdf/jspdf.debug.js"></script> 
<script src="<?php echo base_url() ?>/theme/js/widgets/calculator.js"></script> 
<script src="<?php echo base_url() ?>/theme/js/custom.js"></script>
<script type="text/javascript">
$(document).on('ready',function() {

$(".financial-bal").niceScroll({
cursorwidth: "8px",cursorcolor:"#7f8c8d"
});

$(document).on('submit','#edit-profile',function(){
var link=$(this).attr("action");
$.ajax({method : "POST",url : link, data: $(this).serialize(),
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){
if(data=="true"){
swal("Alert","Saved Sucessfully", "success");
}else{
swal("Alert",data, "info");
}
$(".block-ui").css("display","none");
}
});

return false;
});

$(document).on('submit','#change-password',function(){
var link=$(this).attr("action");
$.ajax({method : "POST",url : link, data: $(this).serialize(),
beforeSend : function(){
$(".block-ui").css('display','block'); 
},success : function(data){
if(data=="true"){
swal("Alert","Saved Sucessfully", "success");
$("#change-password")[0].reset();
}else{
swal("Alert",data, "info");
}
$(".block-ui").css("display","none");
}
});

return false;
});


loadpage(); 
loadpage2();

$('#current-calendar').fullCalendar();

});



</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132313349-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-132313349-1');
</script>


</body>
</html>