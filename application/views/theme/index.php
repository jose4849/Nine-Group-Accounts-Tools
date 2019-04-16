<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Dashboard</h4></div>

<div class="clo-md-3 col-lg-3 col-sm-6">
<div class="card-box">
    <div class="box-callout-green">
        <div class="leftside-cart">
            <i class="ion-plus-circled cart-icon"></i>
        </div>
        <div class="rightside-cart">
            <p class="card-head">Current Day Income<br>
                <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_day_income']; ?></span></p>
        </div>
    </div>
</div>
</div>

<div class="clo-md-3 col-lg-3 col-sm-6">
<div class="card-box">
    <div class="box-callout-orange">
        <div class="leftside-cart">
            <i class="ion-minus-circled cart-icon"></i>
        </div>
        <div class="rightside-cart">
            <p class="card-head">Current Day Expense<br>
                <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_day_expense']; ?></span></p>
        </div>
    </div>
</div>
</div>

<div class="clo-md-3 col-lg-3 col-sm-6">
<div class="card-box">
    <div class="box-callout-green">
        <div class="leftside-cart">
            <i class="ion-plus-circled cart-icon"></i>
        </div>
        <div class="rightside-cart">
            <p class="card-head">Current Month Income<br>
                <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_month_income']; ?></span></p>
        </div>
    </div>
</div>
</div>



<div class="clo-md-3 col-lg-3 col-sm-6">
<div class="card-box">
    <div class="box-callout-orange">
        <div class="leftside-cart">
            <i class="ion-minus-circled cart-icon"></i>
        </div>
        <div class="rightside-cart">
            <p class="card-head">Current Month Expense<br>
                <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_month_expense']; ?></span></p>
        </div>
    </div>
</div>
</div>

</div>
<!--End Card box-->




<!--Start Income-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default custom-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Latest 5 Income</div>
    <div class="panel-body">
        <!--Income Table-->
        <table class="table table-bordered">
            <th>Date</th>
            <th>Description</th>
            <th class="text-right">Amount</th>
         <?php foreach($latest_income as $income){ ?>   
            <tr>
                <td><?php echo $income->trans_date ?></td>
                <td><?php echo $income->note ?></td>
                <td class="text-right"><?php echo get_current_setting('currency_code')." ".$income->amount ?></td>
            </tr>

          <?php } ?>  

        </table>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End Income Col-->

<!--Start Expense-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default custom-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Latest 5 Expense</div>
    <div class="panel-body">
        <table class="table table-bordered">
            <th>Date</th>
            <th>Description</th>
            <th class="text-right">Amount</th>
             <?php foreach($latest_expense as $expense){ ?>   
            <tr>
                <td><?php echo $expense->trans_date ?></td>
                <td><?php echo $expense->note ?></td>
                <td class="text-right"><?php echo get_current_setting('currency_code')." ".$expense->amount ?></td>
            </tr>

          <?php } ?>  
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Expense Col-->
<?php /*
<!--Start Income Vs Expense Chart-->
<div class="col-md-6 col-sm-6 col-lg-6">
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Income Vs Expense - July 2015</div>
    <div class="panel-body">
        <div id="inc_vs_exp"></div>

    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Income Vs Expense Chart-->
*/
?>
<!--Start Account Status-->
<div class="col-md-12 col-sm-12 col-lg-12">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Financial Balance Status</div>
    <div class="panel-body financial-bal">
        <table class="table table-bordered ">
            <th>Account</th>
            <th class="text-right">Balance</th>
            <?php foreach($financialBalance as $balance) {?>
            <tr>
                <td><?php echo $balance->account ?></td>
                <td class="text-right"><?php echo get_current_setting('currency_code')." ".$balance->balance ?></td>
            </tr>

            <?php } ?>
   
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Account Status Col-->

</div>
<!--End Row-->
</div>
</section>
<!--End Main-content-->
<script type="text/javascript">
$(document).ready(function() {
    
    
    /*
    var chart;
    chart = c3.generate({
    bindto: '#inc_vs_exp2',
    data: {
        x: 'x',
//        xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
        columns: [
            ['x'

            <?php for($i=1;$i<=count($line_chart[0]);$i++){ 
             echo ",";    
             echo "'".$line_chart[0][$i]['date']."'"; 
            } ?>

            ],

           ['Income', 
            <?php for($i=1;$i<=count($line_chart[0]);$i++){ 
            echo  $line_chart[0][$i]['amount'].",";
            } ?>
            ],


            ['Expense', 
            <?php for($i=1;$i<=count($line_chart[1]);$i++){ 
            echo  $line_chart[1][$i]['amount'].",";
            } ?>
            ]
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%Y-%m-%d'
            }
        }
    }
});



chart = c3.generate({
bindto: '#inc_vs_exp',
data: {
columns: [
['Income', <?php echo $pie_data['income'] ?>],
['Expense', <?php echo $pie_data['expense'] ?>],
],
type: 'donut',
onclick: function(d, i) {
console.log("onclick", d, i);
},
onmouseover: function(d, i) {
console.log("onmouseover", d, i);
},
onmouseout: function(d, i) {
console.log("onmouseout", d, i);
}
},
color: {
pattern: ['#23c6c8', '#f39c12']
},
donut: {
title: "Income VS Expense"
}
});
   
});

*/


</script>