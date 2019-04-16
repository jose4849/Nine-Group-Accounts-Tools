<?php

class AdminModel extends CI_Model{
 
//get all Chart Of Accounts  
public function getAllChartOfAccounts(){
$this->db->select('*');
$this->db->from('chart_of_accounts');  
$this->db->order_by("chart_id", "desc");    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

} 

//get Chart Of Accounts by type 
public function getChartOfAccountByType($type){
$this->db->select('*');
$this->db->from('chart_of_accounts');  
$this->db->where('accounts_type',$type);  
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

}
//get all payer and payee    
public function getAllPayeryAndPayee(){
$this->db->select('*');
$this->db->from('payee_payers');  
$this->db->order_by("trace_id", "desc");    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

} 

//get payer and payee by type   
public function getPayeryAndPayeeByType($type){
$this->db->select('*');
$this->db->from('payee_payers');  
$this->db->where("type", $type);    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

} 


//get all payment method   
public function getAllPaymentmethod(){
$this->db->select('*');
$this->db->from('payment_method');  
$this->db->order_by("p_method_id", "desc");    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

}   
 
//get all user     
public function getAllUsers(){
$this->db->select('*');
$this->db->from('user');  
$this->db->order_by("user_id", "desc");    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

}    


//get all settings  
public function getAllSettings(){
$this->db->select('*');
$this->db->from('settings');    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

}  


//get all Personal/Bank Account 
public function getAllAccounts(){
$this->db->select('*');
$this->db->from('accounts');    
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

}  


//get account by id  
public function getAccount($accounts_id){
$this->db->select('*');
$this->db->from('accounts');
$this->db->where('accounts_id',$accounts_id);    
$query_result=$this->db->get();
$result=$query_result->row();
return $result;
} 


//method for calculating balance
public function getBalance($account,$amount,$action)
{
if($action=='add'){
//get last balance
$query=$this->db->query("SELECT bal FROM transaction 
WHERE accounts_name='".$account."' ORDER BY
trans_id DESC LIMIT 1");
$result=$query->row();
return $result->bal+$amount;
}else if($action=='sub'){
//get last balance
$query=$this->db->query("SELECT bal FROM transaction 
WHERE accounts_name='".$account."' ORDER BY
trans_id DESC LIMIT 1");
$result=$query->row();
return $result->bal-$amount;

}
}


//get transaction information 
public function getTransaction($limit='',$type='')
{
	
$this->db->select('*');
$this->db->from('transaction');
if($type!=''){
$this->db->where('type',$type);	
}
$this->db->order_by("trans_id", "desc");
if($limit!='all'){ 
$this->db->limit($limit);
}    

$query_result=$this->db->get();
$result=$query_result->result();
return $result;

} 


//get single transaction
public function getSingleTransaction($trans_id){
$this->db->select('*');
$this->db->from('transaction');
$this->db->where('trans_id',$trans_id);
$query_result=$this->db->get();
$result=$query_result->row();
return $result;

}


//get repeat transaction 
public function getRepeatTransaction($type,$status,$status2='')
{
	
$this->db->select('*');
$this->db->from('repeat_transaction');
$this->db->where('type',$type);
if($status2!=''){
$this->db->where_in('status',array($status,$status2));	
}else{
$this->db->where('status',$status);
}
$query_result=$this->db->get();
$result=$query_result->result();
return $result;

} 

//get single repeat transaction
public function getSingleRepeatTransaction($trans_id){
$this->db->select('*');
$this->db->from('repeat_transaction');
$this->db->where('trans_id',$trans_id);
$query_result=$this->db->get();
$result=$query_result->row();
return $result;

}

//Process Repeating Transaction
public function processRepeatTransaction($trans_id,$status)
{

$data['status']=$status;	
$data['pdate']=date("Y-m-d");

$this->db->trans_begin();
$this->db->where('trans_id',$trans_id);
$this->db->update('repeat_transaction',$data);

$trans=$this->getSingleRepeatTransaction($trans_id);
if($trans->type=="Income"){
//insert Income
$this->insertIncome($trans->account,$trans->category,$trans->amount,$trans->payer,$trans->p_method,$trans->ref,$trans->description);

}else if($trans->type=="Expense"){
//insert Expense
$this->insertExpense($trans->account,$trans->category,$trans->amount,$trans->payee,$trans->p_method,$trans->ref,$trans->description);
}

//end transaction
if ($this->db->trans_status() === FALSE)
{
    $this->db->trans_rollback();
}
else
{
    $this->db->trans_commit();
    return true;
}


}


public function insertIncome($account,$cat,$amount,$payer,$p_method,$ref,$note)
{
$data=array();	
$data['accounts_name']=$account; 
$data['trans_date']=date("Y-m-d");; 
$data['type']='Income'; 
$data['category']=$cat; 
$data['amount']=$amount; 
$data['payer']=$payer; 
$data['payee']=''; 
$data['p_method']=$p_method; 
$data['ref']=$ref; 
$data['note']=$note;  
$data['dr']=0;  
$data['cr']=$amount;
$data['bal']=$this->getBalance($account,$amount,"add");  
$this->db->insert('transaction',$data);	
}

public function insertExpense($account,$cat,$amount,$payee,$p_method,$ref,$note)
{
$data=array();	
$data['accounts_name']=$account; 
$data['trans_date']=date("Y-m-d");; 
$data['type']='Expense'; 
$data['category']=$cat; 
$data['amount']=$amount; 
$data['payer']=''; 
$data['payee']=$payee; 
$data['p_method']=$p_method; 
$data['ref']=$ref; 
$data['note']=$note;  
$data['dr']=$amount;  
$data['cr']=0;
$data['bal']=$this->getBalance($account,$amount,"sub");  
$this->db->insert('transaction',$data);	
}



    
}