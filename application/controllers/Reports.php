<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
        redirect('User');    
        }
		$this->load->database();
        $this->load->model('Reportmodel');
        $this->load->model('Adminmodel');

    }

	//View Account Statement Report// 
    public function accountStatement($action='')
    {
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        if($action=='asyn'){
        $this->load->view('reports/accountStatement',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
		$this->load->view('reports/accountStatement',$data);
		$this->load->view('theme/include/footer');
        }else if($action=='view'){
        $account=$this->input->post('account',true); 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        $trans_type=$this->input->post('trans_type',true);
        
        $reportData=$this->Reportmodel->getAccountStatement($account,$from_date,$to_date,$trans_type);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;
        $bal=0;		
        foreach ($reportData as $report) {
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        $bal=$report->bal;
        ?>
        <tr>
        <td><?php echo $report->trans_date ?></td><td><?php echo $report->note ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->bal ?></td>
        </tr>
       

        <?php 
          }  
         //Summery value
         echo "<tr><td colspan='2'><b>Total</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$bal."</b></td></tr>"; 
        }
      }

    }

       //Date Wise Income Report
        public function datewiseIncomeReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/datewise_income_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/datewise_income_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getIncomeReport($from_date,$to_date);
        if(empty($reportData)){
        echo "false";
        }else{
        $sum=0;    
        foreach ($reportData as $report) { 
        
        $sum=$sum+$report->amount;
        ?>
        <tr>
        <td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->ref ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->payer ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>
        <td><?php echo $report->note ?></td></tr>
       

        <?php 
          } 
        echo "<tr><td colspan='5'><b>Total Income</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td>";
        echo "<td>Sub Total</td></tr>";   
        }
      }

    }


       //Day Wise Income Report
        public function daywiseIncomeReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/daywise_income_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/daywise_income_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getIncomeReport($from_date,$to_date,"group");
        if(empty($reportData)){
        echo "false";
        }else{

        $sum=0;    
        foreach ($reportData as $report) {     
        $sum=$sum+$report->amount;
        ?>
        <tr><td><?php echo $report->trans_date ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>

        <?php 
          }  
        echo "<tr><td><b>Total Income</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td></tr>";    
        }
      }

    }

        //Date Wise Expense Report
        public function datewiseExpenseReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/datewise_expense_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/datewise_expense_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getExpenseReport($from_date,$to_date);
        if(empty($reportData)){
        echo "false";
        }else{
        $sum=0;    
        foreach ($reportData as $report) {     
        $sum=$sum+$report->amount;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td><td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->ref ?></td><td><?php echo $report->category ?></td>
        <td><?php echo $report->payee ?></td><td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>
        <td><?php echo $report->note ?></td></tr>
       
        <?php 
          }
        echo "<tr><td colspan='5'><b>Total Expense</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td>";
        echo "<td>Sub Total</td></tr>";     
        }
      }

    }

       //Day Wise Expense Report
       public function daywiseExpenseReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/daywise_expense_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/daywise_expense_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getExpenseReport($from_date,$to_date,"group");
        if(empty($reportData)){
        echo "false";
        }else{
        $sum=0;    
        foreach ($reportData as $report) {     
        $sum=$sum+$report->amount;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>

        <?php 
          }
         echo "<tr><td><b>Total Expense</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td></tr>";        
        }
      }

    }

        //Transfer Report
        public function transferReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/transfer_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/transfer_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getTransferReport($from_date,$to_date);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td><td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->ref ?></td><td><?php echo $report->payer ?></td>
        <td><?php echo $report->note ?></td><td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='5'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }

    }

	//Income Vs Expense Report
    public function incomeVsExpense($action=''){
      if($action=='asyn'){
        $this->load->view('reports/income_vs_expense_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/income_vs_expense_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $income=$this->Reportmodel->getIncomeReport($from_date,$to_date);
        $expense=$this->Reportmodel->getExpenseReport($from_date,$to_date);

        $income_count=count($income);
        $expense_count=count($expense);

        ?>
        <div class="col-md-6 col-lg-6 col-sm-6 join-table-1">
        <table class="table table-bordered">
        <thead>
        <th>Income Date</th><th>Note</th><th class="text-right">Amount</th>
        <tbody>

        <?php 
        $income_total=0;
        foreach ($income as $report) { 
        $income_total=$income_total+$report->amount;
        ?>

        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->note ?></td>    
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>

        <?php 
        } 
        if($expense_count>$income_count){
        $dif=$expense_count-$income_count;
        for($i=0;$i<$dif; $i++){
        echo "<tr><td colspan='3'>&nbsp;</td></tr>";    
        }
        } 
        echo "<tr><td colspan='2'><b>Total Income</b></td><td class='text-right'><b>".get_current_setting('currency_code')." ".$income_total."</b></td></tr>";  
        
        ?>
        </tbody>
        </table>
        </div>

        <div class="col-md-6 col-lg-6 col-sm-6 join-table-2">
        <table class="table table-bordered">
        <thead>
        <th>Expense Date</th><th>Note</th><th class="text-right">Amount</th>
        <tbody>
        <?php    
        $expense_total=0;
        foreach ($expense as $report) { 
        $expense_total=$expense_total+$report->amount;
        ?>
		
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->note ?></td>     
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>
        <?php 
        } 
   
        if($income_count>$expense_count){
        $dif=$income_count-$expense_count;
        for($i=0;$i<$dif; $i++){
        echo "<tr><td colspan='3'>&nbsp;</td></tr>";    
        }
        }
        echo "<tr><td colspan='2'><b>Total Expense</b></td><td class='text-right'><b>".get_current_setting('currency_code')." ".$expense_total."</b></td></tr>"; 
        ?>
        </tbody>
        </table>
        </div> 
        <?php
      }
    }

    //Report By Chart Of Accounts
    public function incomeCategoryReport($action=''){
        $data=array();  
        $data['accountList']=$this->Adminmodel->getAllChartOfAccounts();
        if($action=='asyn'){
        $this->load->view('reports/income_cat_report',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/income_cat_report',$data);
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true); 
        $category=$this->input->post('category',true);  
        
        $reportData=$this->Reportmodel->getCategoryReport($from_date,$to_date,$category);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->type ?></td>
        <td><?php echo $report->note ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='5'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }
    }


    //Report By Payer
    public function reportByPayer($action=''){
        $data=array();  
        $data['payerList']=$this->Adminmodel->getPayeryAndPayeeByType('Payer');
        if($action=='asyn'){
        $this->load->view('reports/report_by_payer',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/report_by_payer',$data);
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true); 
        $payer=$this->input->post('payer',true);  
        
        $reportData=$this->Reportmodel->getPayerReport($from_date,$to_date,$payer);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->type ?></td>
        <td><?php echo $report->note ?></td>
        <td><?php echo $report->payer ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='6'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }
    }

    //Report By Payee
    public function reportByPayee($action=''){
        $data=array();  
        $data['payeeList']=$this->Adminmodel->getPayeryAndPayeeByType('Payee');
        if($action=='asyn'){
        $this->load->view('reports/report_by_payee',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/report_by_payee',$data);
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true); 
        $payee=$this->input->post('payee',true);  
        
        $reportData=$this->Reportmodel->getPayeeReport($from_date,$to_date,$payee);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->type ?></td>
        <td><?php echo $report->note ?></td>
        <td><?php echo $report->payee ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='6'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }
    }



}