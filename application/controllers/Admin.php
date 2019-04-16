<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
        redirect('User');    
        }
		$this->load->database();
        $this->load->model('Adminmodel');
        $this->load->library('form_validation');
    }
    
	public function index()
	{   
        $data=array();
        $this->load->model('Reportmodel');
        $data['cart_summery']=$this->Reportmodel->getIncomeExpense();
        $data['line_chart']=$this->Reportmodel->dayByDayIncomeExpense();
        $data['latest_income']=$this->Adminmodel->getTransaction(5,'Income');
        $data['latest_expense']=$this->Adminmodel->getTransaction(5,'Expense');
        $data['pie_data']=$this->Reportmodel->sumOfIncomeExpense();
        $data['financialBalance']=$this->Reportmodel->financialBalance();
	    $this->load->view('theme/include/header');
		$this->load->view('theme/index',$data);
		$this->load->view('theme/include/footer');
	}
	
    /** Method For dashboard **/ 
    public function dashboard($action='')
	{
        $data=array();
        $this->load->model('Reportmodel');
        $data['cart_summery']=$this->Reportmodel->getIncomeExpense();
        $data['line_chart']=$this->Reportmodel->dayByDayIncomeExpense();
        $data['latest_income']=$this->Adminmodel->getTransaction(5,'Income');
        $data['latest_expense']=$this->Adminmodel->getTransaction(5,'Expense');
        $data['pie_data']=$this->Reportmodel->sumOfIncomeExpense();
        $data['financialBalance']=$this->Reportmodel->financialBalance();
        if($action=='asyn'){
		$this->load->view('theme/index',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
		$this->load->view('theme/index',$data);
		$this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function addAccount($action='',$param1='')
	{
        if($action=='asyn'){
        $this->load->view('theme/add_account');
        }else if($action==''){
        $this->load->view('theme/include/header');
		$this->load->view('theme/add_account');
		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['accounts_name']=$this->input->post('accounts_name',true); 
        $data['note']=$this->input->post('note',true);  
   
        //-----Validation-----//   
        $this->form_validation->set_rules('accounts_name', 'Account Name', 'trim|required|min_length[4]|max_length[30]');
        $this->form_validation->set_rules('note', 'Note', 'trim|required');


        if (!$this->form_validation->run() == FALSE)
        {
        if($do=='insert'){ 
        //Check Duplicate Entry  
        if(!value_exists("accounts","accounts_name",$data['accounts_name'])){  
        $data['opening_balance']=$this->input->post('opening_balance',true);      
        
        $this->db->trans_begin();
        $this->db->insert('accounts',$data);   
        //insert Transaction Data
        $this->insertTransaction($data['accounts_name'],$data['opening_balance']);
        $this->db->trans_complete();
         if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            echo "true";
            $this->db->trans_commit();
        }
        
        }else{
        echo "This Account Is Already Exists !"; 
        }
        }else if($do=='update'){
        $id=$this->input->post('accounts_id',true);
        if(!value_exists("accounts","accounts_name",$data['accounts_name'],"accounts_id",$id)){       
        $old_name=getOld('accounts_id',$id,'accounts');

        $this->db->where('accounts_id', $id);
        $this->db->update('accounts', $data);

        //transaction table
        $data1=array();
        $data1['accounts_name']=$data['accounts_name'];
        $this->db->where('accounts_name', $old_name->accounts_name);
        $this->db->update('transaction', $data1);

        //repeat transaction table
        $data2=array();
        $data2['account']=$data['accounts_name'];
        $this->db->where('account', $old_name->accounts_name);
        $this->db->update('repeat_transaction', $data2);
       

        echo "true";
        }else{
        echo "This Account Is Already Exists !";  
        }    
        }         
        }else{
        //echo "All Field Must Required With Valid Length !";
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

        }
        //----End validation----//         
        }
        else if($action=='remove'){    
        $this->db->delete('accounts', array('accounts_id' => $param1));        
        }
	}
        
		/** Method For insert Transaction when new account create **/ 
        public function insertTransaction($account,$amount)
    {
        $data=array();
        $data['accounts_name']=$account; 
        $data['trans_date']=date("Y-m-d");
        $data['type']='Transfer'; 
        $data['category']=''; 
        $data['amount']=$amount; 
        $data['payer']='System'; 
        $data['payee']=''; 
        $data['p_method']=''; 
        $data['ref']=''; 
        $data['note']='Opening Balance';  
        $data['dr']=0;  
        $data['cr']=$amount;
        $data['bal']=$amount;  
        $this->db->insert('transaction',$data);

    }

    /** Method For view Manage Account Page **/ 
     public function manageAccount($action='')
	{
        $data=array();  
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        if($action=='asyn'){ 
        $this->load->view('theme/manage_account',$data);   
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/manage_account',$data);
		$this->load->view('theme/include/footer');
        }
	}

	 /** Method For get account information for Account Edit **/ 
     public function editAccount($accounts_id,$action='')
    {
        $data=array();
        $data['edit_account']=$this->Adminmodel->getAccount($accounts_id); 
        if($action=='asyn'){
        $this->load->view('theme/add_account',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('theme/add_account',$data);
        $this->load->view('theme/include/footer');
        }     
    }

    /** Method For Income Page And Create New Income **/ 
     public function addIncome($action='',$param1='')
	{
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        $data['category']=$this->Adminmodel->getChartOfAccountByType('Income');
        $data['payers']=$this->Adminmodel->getPayeryAndPayeeByType('Payer');
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();
        $data['t_data']=$this->Adminmodel->getTransaction(20,'Income');
        if($action=='asyn'){  
        $this->load->view('theme/add_income',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
		$this->load->view('theme/add_income',$data);
		$this->load->view('theme/include/footer');
        }

        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['accounts_name']=$this->input->post('account',true); 
        $data['trans_date']=$this->input->post('income-date',true); 
        $data['type']='Income'; 
        $data['category']=$this->input->post('income-type',true); 
        $data['amount']=$this->input->post('amount',true); 
        $data['payer']=$this->input->post('payer',true); 
        $data['payee']=''; 
        $data['p_method']=$this->input->post('p-method',true); 
        $data['ref']=$this->input->post('reference',true); 
        $data['note']=$this->input->post('note',true);  
        $data['dr']=0;  
        $data['cr']=$this->input->post('amount',true);

        //-----Validation-----//   
        $this->form_validation->set_rules('account', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('income-date', 'Date', 'trim|required');
        $this->form_validation->set_rules('income-type', 'Income Type', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('payer', 'Payer', 'trim|required');
        $this->form_validation->set_rules('p-method', 'Payment Method', 'trim|required');
        $this->form_validation->set_rules('reference', 'Reference No', 'trim|required');
        $this->form_validation->set_rules('note', 'Note', 'trim|required|min_length[8]');

        if (!$this->form_validation->run() == FALSE)
        {
        $data['bal']=$this->Adminmodel->getBalance($data['accounts_name'],$data['amount'],"add");      
        
        if($do=='insert'){ 
      
        if($this->db->insert('transaction',$data)){
        echo "true";
        }
        
        }else if($do=='update'){
        $id=$this->input->post('trans_id',true);
        $this->db->where('trans_id', $id);
        $this->db->update('transaction', $data);
        echo "true";
    
        }         
        }else{
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        //----End validation----//         
        }
        else if($action=='remove'){    
        $this->db->delete('transaction', array('trans_id' => $param1));        
        }
	}


    /** Method For Expense Page And Create New Expense **/ 
     public function addExpense($action='',$param1='')
	{
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        $data['category']=$this->Adminmodel->getChartOfAccountByType('Expense');
        $data['payee']=$this->Adminmodel->getPayeryAndPayeeByType('Payee');
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();
        $data['t_data']=$this->Adminmodel->getTransaction(20,'Expense');
        if($action=='asyn'){  
        $this->load->view('theme/add_expense',$data);    
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/add_expense',$data);
		$this->load->view('theme/include/footer');
        }

        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['accounts_name']=$this->input->post('account',true); 
        $data['trans_date']=$this->input->post('expense-date',true); 
        $data['type']='Expense'; 
        $data['category']=$this->input->post('expense-type',true); 
        $data['amount']=$this->input->post('amount',true); 
        $data['payer']='';
        $data['payee']=$this->input->post('payee',true); 
        $data['p_method']=$this->input->post('p-method',true); 
        $data['ref']=$this->input->post('reference',true); 
        $data['note']=$this->input->post('note',true);  
        $data['dr']=$this->input->post('amount',true); 
        $data['cr']=0;

        //-----Validation-----//   
        $this->form_validation->set_rules('account', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('expense-date', 'Date', 'trim|required');
        $this->form_validation->set_rules('expense-type', 'Expense Type', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('payee', 'Payee', 'trim|required');
        $this->form_validation->set_rules('p-method', 'Payment Method', 'trim|required');
        $this->form_validation->set_rules('reference', 'Reference No', 'trim|required');
        $this->form_validation->set_rules('note', 'Note', 'trim|required|min_length[8]');

        if (!$this->form_validation->run() == FALSE)
        {
        $data['bal']=$this->Adminmodel->getBalance($data['accounts_name'],$data['amount'],"sub");  

        if($do=='insert'){ 
      
        if($this->db->insert('transaction',$data)){
        echo "true";
        }
        
        }else if($do=='update'){
        $id=$this->input->post('trans_id',true);
        $this->db->where('trans_id', $id);
        $this->db->update('transaction', $data);
        echo "true";
    
        }         
        }else{
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        //----End validation----//         
        }
        else if($action=='remove'){    
        $this->db->delete('transaction', array('trans_id' => $param1));        
        }
	}
    
	/** Method For Transfer Page and create new transfer **/ 
     public function transfer($action='',$param1='')
	{
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();
 
        if($action=='asyn'){  
        $this->load->view('theme/add_transfer',$data);    
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/add_transfer',$data);
		$this->load->view('theme/include/footer');
        }

        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $to_account=$this->input->post('to-account',true); 
        $data['accounts_name']=$this->input->post('from-account',true); 
        $data['trans_date']=$this->input->post('transfer-date',true); 
        $data['type']='Transfer'; 
        $data['category']=''; 
        $data['amount']=$this->input->post('amount',true); 
        $data['payer']=''; 
        $data['payee']=''; 
        $data['p_method']=$this->input->post('p-method',true); 
        $data['ref']=$this->input->post('reference',true); 
        $data['note']=$this->input->post('note',true);  
        $data['dr']=$this->input->post('amount',true); 
        $data['cr']=0;
        $data['bal']=$this->Adminmodel->getBalance($data['accounts_name'],$data['amount'],"sub");  

        //-----Validation-----//   
        $this->form_validation->set_rules('from-account', 'From Account', 'trim|required');
        $this->form_validation->set_rules('to-account', 'To Account', 'trim|required');
        $this->form_validation->set_rules('transfer-date', 'Date', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('p-method', 'Payment Method', 'trim|required');
        $this->form_validation->set_rules('reference', 'Reference No', 'trim|required');
        $this->form_validation->set_rules('note', 'Note', 'trim|required|min_length[8]');

        if (!$this->form_validation->run() == FALSE)
        {
        if($do=='insert'){ 
        if($data['accounts_name']!= $to_account){  
        $this->db->trans_begin();  
        $this->db->insert('transaction',$data);
        $data['accounts_name']=$to_account;
        $data['dr']=0;
        $data['cr']=$this->input->post('amount',true); 
        $data['bal']=$this->Adminmodel->getBalance($data['accounts_name'],$data['amount'],"add");
        $this->db->insert('transaction',$data);

        if ($this->db->trans_status() === FALSE)
        {
        $this->db->trans_rollback();
        }else{
        echo "true";    
        $this->db->trans_commit();    
        }
        }else{
        echo "Sorry, Cannot Transfer Between Same Account !";   
        }
        
        }else if($do=='update'){
        $id=$this->input->post('trans_id',true);
        $this->db->where('trans_id', $id);
        $this->db->update('transaction', $data);
        echo "true";
    
        }         
        }else{
        //echo "All Field Must Required With Valid Length !";
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        //----End validation----//         
        }
        else if($action=='remove'){    
        $this->db->delete('transaction', array('trans_id' => $param1));        
        }

	}
    
	  /** Method For view manage Income page **/ 
      public function manageIncome($action='')
	{

        $data=array();
        $data['income_list']=$this->Adminmodel->getTransaction('all','Income');
        if($action=='asyn'){
        $this->load->view('theme/manage_income',$data);
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/manage_income',$data);
		$this->load->view('theme/include/footer');
        }

	}
    
	  /** Method For view manage Expense page **/ 
      public function manageExpense($action='')
	{
        $data=array();
        $data['expense_list']=$this->Adminmodel->getTransaction('all','Expense');
        if($action=='asyn'){
        $this->load->view('theme/manage_expense',$data);    
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/manage_expense',$data);
		$this->load->view('theme/include/footer');
        }
	}

	   /** Method For get data for edit transaction **/ 
       public function editTransaction($trans_id,$action='')
    {
        $data=array();
        $data['transaction']=$this->Adminmodel->getSingleTransaction($trans_id);
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();
        if($action=='asyn'){
        $this->load->view('theme/edit_transaction',$data);    
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('theme/edit_transaction',$data);
        $this->load->view('theme/include/footer');
        }
    }

	   /** Method For Update Transaction **/ 
       public function updateTransaction()
    {
       $data=array();
       $id=$this->input->post('trans_id',true);
       $data['trans_date']=$this->input->post('date',true);
       $data['p_method']=$this->input->post('p-method',true);
       $data['ref']=$this->input->post('reference',true);
       $data['note']=$this->input->post('note',true);

       $this->db->where('trans_id', $id);
       $this->db->update('transaction', $data);
       echo "true";

    }

      /** Method For View Repeat Income page **/ 
      public function repeatIncome($action='')
	{
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        $data['category']=$this->Adminmodel->getChartOfAccountByType('Income');
        $data['payers']=$this->Adminmodel->getPayeryAndPayeeByType('Payer');
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();

        if($action=='asyn'){
        $this->load->view('theme/repeat_income',$data);    
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/repeat_income',$data);
		$this->load->view('theme/include/footer');
        }

        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['account']=$this->input->post('account',true); 
        $data['date']=$this->input->post('income-date',true); 
        $data['type']='Income'; 
        $data['category']=$this->input->post('income-type',true); 
        $data['amount']=$this->input->post('amount',true); 
        $data['payer']=$this->input->post('payer',true); 
        $data['payee']=''; 
        $data['p_method']=$this->input->post('p-method',true); 
        $data['ref']=$this->input->post('reference',true); 
        $data['status']='pending';
        $data['description']=$this->input->post('note',true);  
    

        //-----Validation-----//   
        $this->form_validation->set_rules('account', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('income-date', 'Date', 'trim|required');
        $this->form_validation->set_rules('rotation-income', 'Rotation', 'numeric|required');
        $this->form_validation->set_rules('income-type', 'Income Type', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('payer', 'Payer', 'trim|required');
        $this->form_validation->set_rules('p-method', 'Payment Method', 'trim|required');
        $this->form_validation->set_rules('reference', 'Reference No', 'trim|required');
        $this->form_validation->set_rules('note', 'Note', 'trim|required|min_length[8]');

        if (!$this->form_validation->run() == FALSE)
        {
        if($do=='insert'){ 
        $increment=$this->input->post('rotation',true);      
        $loop=$this->input->post('rotation-income',true);    
        $this->db->trans_begin();
        for($i=0;$i<$loop;$i++){   
        $this->db->insert('repeat_transaction',$data);
        $date=$data['date'];
        $data['date']=date('Y-m-d', strtotime($date.' + '.$increment.' days'));
        }

        if ($this->db->trans_status() === FALSE)
        {
        $this->db->trans_rollback();
        }else{
        echo "true";    
        $this->db->trans_commit();    
        }
       
        }         
        }else{
        //echo "All Field Must Required With Valid Length !";
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        //----End validation----//         
        }
       
	}
      
	  /** Method For View repeat Expense Page **/ 
      public function repeatExpense($action='')
	{
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        $data['category']=$this->Adminmodel->getChartOfAccountByType('Expense');
        $data['payee']=$this->Adminmodel->getPayeryAndPayeeByType('Payee');
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();
        if($action=='asyn'){
        $this->load->view('theme/repeat_expense',$data);    
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/repeat_expense',$data);
		$this->load->view('theme/include/footer');
        }

        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['account']=$this->input->post('account',true); 
        $data['date']=$this->input->post('expense-date',true); 
        $data['type']='Expense'; 
        $data['category']=$this->input->post('expense-type',true); 
        $data['amount']=$this->input->post('amount',true); 
        $data['payer']=''; 
        $data['payee']=$this->input->post('payee',true); 
        $data['p_method']=$this->input->post('p-method',true); 
        $data['ref']=$this->input->post('reference',true); 
        $data['status']='unpaid';
        $data['description']=$this->input->post('note',true);  
    

        //-----Validation-----//   
        $this->form_validation->set_rules('account', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('expense-date', 'Date', 'trim|required');
        $this->form_validation->set_rules('rotation-expense', 'Rotation', 'numeric|required');
        $this->form_validation->set_rules('expense-type', 'Expense Type', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('payee', 'Payee', 'trim|required');
        $this->form_validation->set_rules('p-method', 'Payment Method', 'trim|required');
        $this->form_validation->set_rules('reference', 'Reference No', 'trim|required');
        $this->form_validation->set_rules('note', 'Note', 'trim|required|min_length[8]');

        if (!$this->form_validation->run() == FALSE)
        {
        if($do=='insert'){ 
        $increment=$this->input->post('rotation',true);      
        $loop=$this->input->post('rotation-expense',true);    
        $this->db->trans_begin();
        for($i=0;$i<$loop;$i++){   
        $this->db->insert('repeat_transaction',$data);
        $date=$data['date'];
        $data['date']=date('Y-m-d', strtotime($date.' + '.$increment.' days'));
        }

        if ($this->db->trans_status() === FALSE)
        {
        $this->db->trans_rollback();
        }else{
        echo "true";    
        $this->db->trans_commit();    
        }
       
        }         
        }else{
        //echo "All Field Must Required With Valid Length !";
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        //----End validation----//         
        }
       

	}
    
	  /** Method For view Process Income page  **/ 
      public function processIncome($action='')
	{
        $data=array();
        $data['repeat_income']=$this->Adminmodel->getRepeatTransaction('Income','pending'); 
        if($action=='asyn'){
        $this->load->view('theme/process_repeat_income',$data);
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/process_repeat_income',$data);
		$this->load->view('theme/include/footer');
        }
	}
    
	  /** Method For view Process Expense page  **/
      public function processExpense($action='')
	{
        $data=array();
        $data['repeat_expense']=$this->Adminmodel->getRepeatTransaction('Expense','unpaid'); 
        if($action=='asyn'){
        $this->load->view('theme/process_repeat_expense',$data);    
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/process_repeat_expense',$data);
		$this->load->view('theme/include/footer');
        }
	}
        
		/** Method For get data for edit repeat transaction  **/
        public function editRepeatTransaction($trans_id,$action='')
    {
        $data=array();
        $data['transaction']=$this->Adminmodel->getSingleRepeatTransaction($trans_id);
        $data['p_method']=$this->Adminmodel->getAllPaymentmethod();
        if($action=='asyn'){
        $this->load->view('theme/edit_repeat_transaction',$data);    
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('theme/edit_repeat_transaction',$data);
        $this->load->view('theme/include/footer');
        }
    }

	   /** Method For Update repeat Transaction  **/
       public function updateRepeatTransaction()
    {
       $data=array();
       $id=$this->input->post('trans_id',true);
       $data['date']=$this->input->post('date',true);
       $data['p_method']=$this->input->post('p-method',true);
       $data['ref']=$this->input->post('reference',true);
       $data['description']=$this->input->post('note',true);

       $this->db->where('trans_id', $id);
       $this->db->update('repeat_transaction', $data);
       echo "true";

    }
         /** Method For view Income Calender page  **/
         public function incomeCalender($action='')
    { 
        $data=array();
        $data['repeat_income']=$this->Adminmodel->getRepeatTransaction('Income','pending','receive'); 
        if($action=='asyn'){
        $this->load->view('theme/income_calendar',$data);    
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('theme/income_calendar',$data);
        $this->load->view('theme/include/footer');
        }
    }
    
	  /** Method For view Expense Calender page  **/
      public function expenseCalender($action='')
    {
         $data=array();
        $data['repeat_expense']=$this->Adminmodel->getRepeatTransaction('Expense','unpaid','paid'); 
        if($action=='asyn'){
        $this->load->view('theme/expense_calendar',$data);    
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('theme/expense_calendar',$data);
        $this->load->view('theme/include/footer');
        }
    }

	  /** Method For process repeat Transaction  **/
      public function processRepeatTransaction($trans_id,$status)
    {
     if($this->Adminmodel->processRepeatTransaction($trans_id,$status)){
      echo "true";  
     }else{
       echo "false"; 
     }

    }

	  /** Method For View,insert, update and delete Chart of accounts  **/
      public function chartOfAccounts($action='',$param1='')
	{
        checkPermission('Employee');
        $data=array();  
        $data['accountList']=$this->Adminmodel->getAllChartOfAccounts();
        //----For ajax load-----//
        if($action=='asyn'){
        $this->load->view('theme/chart_of_accounts',$data);
        }else if($action==''){  
	    $this->load->view('theme/include/header');
		$this->load->view('theme/chart_of_accounts',$data);
		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----//
        if($action=='insert'){
        $data=array();
        $do=$this->input->post('action',true);     
        $data['accounts_name']=$this->input->post('account',true); 
        $data['accounts_type']=$this->input->post('account-type',true); 
        //-----Validation-----//    
        if($data['accounts_name']!="" && $data['accounts_type']!="" && 
        strlen($data['accounts_name'])<=30 && strlen($data['accounts_type'])<=7){
        if($do=='insert'){
        //Check Duplicate Entry    
        if(!value_exists2("chart_of_accounts","accounts_name",$data['accounts_name'],"accounts_type",$data['accounts_type'])){    
        if($this->db->insert('chart_of_accounts',$data)){
        $last_id=$this->db->insert_id();    
        echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';
        }
        }else{
        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
        }
        }else if($do=='update'){
        $id=$this->input->post('chart_id',true); 
        //Check Duplicate Entry  
        if(!value_exists2("chart_of_accounts","accounts_name",$data['accounts_name'],"accounts_type",$data['accounts_type'],"chart_id",$id)){  
        $this->db->where('chart_id', $id);
        $this->db->update('chart_of_accounts', $data);
        echo '{"result":"true","action":"update"}'; 
        }else{
        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
        }      
        }    
        }else{
        echo '{"result":"false", "message":"All Field Must Required With Valid Length !"}';
        }
        //----End validation----//
            
        }else if($action=='remove'){    
        $this->db->delete('chart_of_accounts', array('chart_id' => $param1));        
        }  
          
	}
    
	    /** Method For View,insert, update and delete payee And Payers  **/
        public function payeeAndPayers($action='',$param1='')
	{
        checkPermission('Employee');
        $data=array();  
        $data['p_list']=$this->Adminmodel->getAllPayeryAndPayee();
        //----For ajax load-----//    
        if($action=='asyn'){
        $this->load->view('theme/Payee_Payers',$data);
        }else if($action==''){    
	    $this->load->view('theme/include/header');
		$this->load->view('theme/Payee_Payers',$data);
		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){
        $data=array();
        $do=$this->input->post('action',true);     
        $data['payee_payers']=$this->input->post('p-name',true); 
        $data['type']=$this->input->post('p-type',true); 
        //-----Validation-----//    
        if($data['payee_payers']!="" && $data['type']!="" && 
        strlen($data['payee_payers'])<=30 && strlen($data['type'])<=5){
        if($do=='insert'){  
        //Check Duplicate Entry     
        if(!value_exists2("payee_payers","payee_payers",$data['payee_payers'],"type",$data['type'])){       
        if($this->db->insert('payee_payers',$data)){
        $last_id=$this->db->insert_id();    
        echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';
        } 
        }else{
        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
        }
        }else if($do=='update'){
        $id=$this->input->post('trace_id',true);  
        //Check duplicate Entry   
    if(!value_exists2("payee_payers","payee_payers",$data['payee_payers'],"type",$data['type'],"trace_id",$id)){     
        $this->db->where('trace_id', $id);
        $this->db->update('payee_payers', $data);  
        echo '{"result":"true", "action":"update"}';  
        }else{
        echo '{"result":"false", "message":"This Name Is Already Exists !"}';
        }        
        }
            
        }else{
        echo '{"result":"false", "message":"All Field Must Required With Valid Length !"}';
        }
        //----End validation----//
            
        }else if($action=='remove'){    
        $this->db->delete('payee_payers', array('trace_id' => $param1));        
        }    
         
            
	}
    
	   /** Method For View,insert, update and delete payment method  **/
        public function paymentMethod($action='',$param1='')
	{
        checkPermission('Employee');
        $data=array();  
        $data['p_list']=$this->Adminmodel->getAllPaymentmethod();    
        //----For ajax load-----//     
        if($action=='asyn'){
        $this->load->view('theme/payment_method',$data);
        }else if($action==''){
	    $this->load->view('theme/include/header');
		$this->load->view('theme/payment_method',$data);
		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['p_method_name']=$this->input->post('p-method',true); 
        //-----Validation-----//    
        if($data['p_method_name']!="" && strlen($data['p_method_name'])<=20){
        if($do=='insert'){
        //Check Duplicate Entry     
        if(!value_exists("payment_method","p_method_name",$data['p_method_name'])){      
        if($this->db->insert('payment_method',$data)){
        $last_id=$this->db->insert_id();    
        echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';
        }
        }else{
        echo '{"result":"false", "message":"This Payment Method Is Already Exists !"}';
        }    
        }else if($do=='update'){
        $id=$this->input->post('p_method_id',true);  
        //Check Duplicate Entry  
    if(!value_exists("payment_method","p_method_name",$data['p_method_name'],"p_method_id",$id)){  
        $this->db->where('p_method_id', $id);
        $this->db->update('payment_method', $data);
        $last_id=$this->db->insert_id();    
        echo '{"result":"true", "action":"update"}';
        }else{
         echo '{"result":"false", "message":"This Payment Method Is Already Exists !"}';
        }      
            
        }
            
        }else{
        echo '{"result":"false", "message":"All Field Must Required With Valid Length !"}';
        }
        //----End validation----//
            
        }else if($action=='remove'){    
        $this->db->delete('payment_method', array('p_method_id' => $param1));
        echo '{"result":"true", "action":"remove"}';       
        }    
 
	}
    
	    /** Method For User Management page  **/
        public function userManagement($action='',$param1='')
	{
        checkPermission('Employee');
        $data=array();  
        $data['users']=$this->Adminmodel->getAllUsers();    
        //----For ajax load-----//      
        if($action=='asyn'){
        $this->load->view('theme/user_management',$data);
        }else if($action==''){    
	    $this->load->view('theme/include/header');
		$this->load->view('theme/user_management',$data);
		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
        $data=array();
        $do=$this->input->post('action',true);     
        $data['user_name']=$this->input->post('username',true);
        $data['fullname']=$this->input->post('fullname',true);  
        $data['email']=$this->input->post('email',true);  
        $data['user_type']=$this->input->post('user-type',true);  
        $data['password']=$this->input->post('user-password',true);  
        $data['creation_date']=date("Y-m-d H:i:s");
   
        //-----Validation-----//   
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]');
        $this->form_validation->set_rules('user-password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('fullname', 'FullName', 'trim|required|min_length[6]|max_length[30]');


        if (!$this->form_validation->run() == FALSE)
        {
        if($do=='insert'){ 
        //Check Duplicate Entry  
        if(!value_exists("user","user_name",$data['user_name'])){ 
        if(!value_exists("user","email",$data['email'])){     
        $data['password']=md5($data['password']);       
        if($this->db->insert('user',$data)){
        $last_id=$this->db->insert_id();    
        echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';    
        }
        }else{echo '{"result":"false", "message":"This Email Is Already Exists !"}'; }
        }else{
        echo '{"result":"false", "message":"Username Is Already Exists !"}';
        }
        }else if($do=='update'){
        $id=$this->input->post('user_id',true);
        if(!value_exists("user","user_name",$data['user_name'],"user_id",$id)){
        if(!value_exists("user","email",$data['email'],"user_id",$id)){ 
        //if password change
        if(strlen($this->input->post('user-password',true))<=15){
        $data['password']=md5($data['password']);   
        }      
        $this->db->where('user_id', $id);
        $this->db->update('user', $data);
        echo '{"result":"true", "action":"update"}';
        }else{echo '{"result":"false", "message":"This Email Is Already Exists !"}'; }
        }else{
        echo '{"result":"false", "message":"Username Is Already Exists !"}'; 
        }    
        }
            
        }else{

        echo json_encode(validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>'));
        }
        //----End validation----//
            
        }
        else if($action=='remove'){    
        $this->db->delete('user', array('user_id' => $param1));        
        }      
            
	}
      /** Method For get user information for edit  **/
      public function getUser($user_id)
    {
        $this->db->select('*');
        $this->db->from('user');  
        $this->db->where("user_id",$user_id);    
        $query_result=$this->db->get();
        $result=$query_result->row();
        echo json_encode($result);
    }  
       /** Method For add new language  **/
        public function addLanguage($action='')
	{
        checkPermission('Employee');
        if($action=='asyn'){
        $this->load->view('theme/add_language');
        }else{    
	    $this->load->view('theme/include/header');
		$this->load->view('theme/add_language');
		$this->load->view('theme/include/footer');
        }
	}
    
	    /** Method For general settings page  **/
        public function generalSettings($action='')
	{
        checkPermission('Employee');
        $data=array();  
        $data['settings']=$this->Adminmodel->getAllSettings();
        $data['timezone']=timezone_list();
        if($action=='asyn'){
        $this->load->view('theme/General_settings',$data);
        }else if($action==''){    
	    $this->load->view('theme/include/header');
		$this->load->view('theme/General_settings',$data);
		$this->load->view('theme/include/footer');
        }

        //update general Settings 
        if($action=='update'){
        $data = array(
           array(
              'settings' => 'company_name' ,
              'value' => $this->input->post("company-name",true)
           ),
           array(
              'settings' => 'language' ,
              'value' => $this->input->post("language",true)
           ),
           array(
              'settings' => 'currency_code' ,
              'value' => $this->input->post("cur-symbol",true)
           ),
            array(
              'settings' => 'email_address' ,
              'value' => $this->input->post("email",true)
           ),
            array(
              'settings' => 'address' ,
              'value' => $this->input->post("address",true)
           ),
            array(
              'settings' => 'phone' ,
              'value' => $this->input->post("phone",true)
           ),
            array(
              'settings' => 'website' ,
              'value' => $this->input->post("website",true)
           ),
            array(
              'settings' => 'timezone' ,
              'value' => $this->input->post("timezone",true)
           )
        );
        //-----Validation-----//   
        $this->form_validation->set_rules('company-name', 'Company Name', 'trim|required|min_length[2]|max_length[50]');
        if (!$this->form_validation->run() == FALSE)
        {
        //Update Code
        $this->db->update_batch('settings', $data, 'settings');
        echo "true";
        //Finish Update Code
        }else{
        echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }

        }

	}

	    /** Method For upload new logo  **/
        public function uploadLogo()
    {
        checkPermission('Employee');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload("logo"))
        {
            echo $this->upload->display_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        else
        {
        $data = array('upload_data' => $this->upload->data());
        $object=array();
        $object['value']=$data['upload_data']['file_name'];
        $this->db->where('settings','logo_path');
        if($this->db->update('settings', $object)){
        echo "true";
           }
        } 

    }


        /** Method For backup database  **/
        public function backupDatabase($action='',$table='')
	{
        
            /*
            checkPermission('Employee');
        if($action=='asyn'){
        $this->load->view('theme/backup_database');
        }else if($action==''){    
	    $this->load->view('theme/include/header');
		$this->load->view('theme/backup_database');
		$this->load->view('theme/include/footer');
        }

        if($action=='backup'){
         $this->load->model('Datamodel');
         $this->Datamodel->backup($table);
        }
        /*
        else if($action=='delete'){
        $this->load->model('Datamodel');
        $this->Datamodel->truncate($table);
        }*/

	}

    /** Method For update profile  **/
    public function updateProfile(){
    $data=array();
    //-----Validation-----//   
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('fullname', 'FullName', 'trim|required|min_length[6]|max_length[30]');
    
    if (!$this->form_validation->run() == FALSE)
    {    
    $data['user_name']=$this->input->post('username',true);
    $data['fullname']=$this->input->post('fullname',true);
    $data['email']=$this->input->post('email',true);

    $id=$this->session->userdata('user_id');
    if(!value_exists("user","user_name",$data['user_name'],"user_id",$id)){
    if(!value_exists("user","email",$data['email'],"user_id",$id)){       
    $this->db->where('user_id', $id);
    $this->db->update('user', $data);
    //update session
    $this->session->set_userdata('username',$data['user_name']);
    $this->session->set_userdata('fullname',$data['fullname']);
    $this->session->set_userdata('email',$data['email']);

    echo "true";
    }else{echo "Sorry, This Email Is Already Exists !"; }
    }else{
    echo "Sorry, Username Is Already Exists !"; 
    } 
    }else{
    //echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');    
    echo "All Field Must Required With Valid Information !";

    }  

    }

	/** Method For change password  **/ 
    public function changePassword(){
     $this->form_validation->set_rules('new-password', 'Password', 'trim|required|min_length[5]');
    if (!$this->form_validation->run() == FALSE)
    {
    $data=array();    
    $data['password']=md5($this->input->post('new-password',true)); 
    $cp=md5($this->input->post('confrim-password',true)); 
    if($data['password']!=$cp){
    echo "New Password And Confrim Password Has Not Match !";
    }else{
    //update Password
    $id=$this->session->userdata('user_id');    
    $this->db->where('user_id',$id);
    $this->db->update('user',$data);
    echo "true";
    }   
    }else{
      echo "The Password field must be at least 5 characters in length";
    }    

    }
    
   
}
