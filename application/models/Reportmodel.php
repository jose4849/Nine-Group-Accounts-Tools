<?php

class ReportModel extends CI_Model{

public function getIncomeExpense(){
//Get Current Day Income, Expense AND Current Month Income, Expense
date_default_timezone_set(get_current_setting('timezone'));	
$date=date("Y-m-d");
//Current Day Income
$income_query=$this->db->query("SELECT sum(amount) as amount FROM transaction 
WHERE type='Income' AND trans_date='".$date."'");	
$income_result=$income_query->row();
//Current Day Expense
$expense_query=$this->db->query("SELECT sum(amount) as amount FROM transaction 
WHERE type='Expense' AND trans_date='".$date."'");	
$expense_result=$expense_query->row();

//Current Month Income
$query1=$this->db->query("SELECT sum(amount) as amount FROM `transaction` 
WHERE type='Income'And trans_date between ADDDATE(LAST_DAY(SUBDATE('".$date."',
INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')");	
$curmonth_income_result=$query1->row();

//Current Month Expense
$query2=$this->db->query("SELECT sum(amount) as amount FROM `transaction` 
WHERE type='Expense'And trans_date between ADDDATE(LAST_DAY(SUBDATE('".$date."',
INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')");	
$curmonth_expense_result=$query2->row();


$transaction=array(
"current_day_income"=>isset($income_result->amount) ? decimalPlace($income_result->amount) : decimalPlace(0),
"current_day_expense"=> isset($expense_result->amount) ? decimalPlace($expense_result->amount) : decimalPlace(0),
"current_month_income"=> isset($curmonth_income_result->amount) ? decimalPlace($curmonth_income_result->amount) : decimalPlace(0),
"current_month_expense"=> isset($curmonth_expense_result->amount) ? decimalPlace($curmonth_expense_result->amount) : decimalPlace(0)

);	

return $transaction;
}	

public function dayByDayIncomeExpense(){
$income=array();		
$expense=array();

$date=date("Y-m-d");	
$date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
$date2=$this->db->query("SELECT LAST_DAY('".$date."') as d")->row()->d;

$income_query=$this->db->query("SELECT trans_date,sum(amount) as amount,MONTHNAME('".$date."') as m_name FROM transaction where
type='Income' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."') GROUP BY trans_date")->result();

$expense_query=$this->db->query("SELECT trans_date,sum(amount) as amount,MONTHNAME('".$date."') as m_name FROM transaction where
type='Expense' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."') GROUP BY trans_date")->result();

//For Income
$maxIncome=count($income_query);
$i=0;
//For Expense
$maxExpense=count($expense_query);
$j=0;

$day=1;
while (strtotime($date1) <= strtotime($date2)) {
//For Income
if($maxIncome>0){
if($date1==$income_query[$i]->trans_date){	
$income[$day]=array(
"amount"=>$income_query[$i]->amount,
"date"=>$date1,
"m_name"=>$income_query[$i]->m_name
);

if($i<($maxIncome-1)){$i++;}
}else{
$income[$day]=array("amount"=>0,"date"=>$date1,"m_name"=>$income_query[$i]->m_name);;	
}
}
//End Income

//For Expense
if($maxExpense>0){
if($date1==$expense_query[$j]->trans_date){	
$expense[$day]=array(
	"amount"=>$expense_query[$j]->amount,
	"date"=>$date1,
	"m_name"=>$expense_query[$j]->m_name
	);
if($j<($maxExpense-1)){$j++;}
}else{
$expense[$day]=array("amount"=>0,"date"=>$date1,"m_name"=>$expense_query[$j]->m_name);;	
}
}
$day++;
$date1 = date ("Y-m-d", strtotime("+1 day", strtotime($date1)));
}
return array($income,$expense);
}

//
public function sumOfIncomeExpense(){
$date=date("Y-m-d");	
$income=$this->db->query("SELECT sum(amount) as amount from transaction
WHERE type='Income' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."')")->row();

$expense=$this->db->query("SELECT sum(amount) as amount from transaction
WHERE type='Expense' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."')")->row();

return array("income"=>$income->amount,"expense"=>$expense->amount);

}

public function financialBalance(){
$query=$this->db->query("SELECT a.accounts_name as account,(SELECT bal FROM transaction
WHERE accounts_name=a.accounts_name ORDER BY trans_id DESC LIMIT 1) 
as balance FROM accounts as a");
$result=$query->result();
return $result;	
}

//Start Report Page
public function getAccountStatement($account,$from_date,$to_date,$trans_type){
if($trans_type=='All'){	
return $this->db->query("SELECT trans_date,note,dr,cr,bal FROM
 transaction WHERE accounts_name='".$account."' AND trans_date between
 '".$from_date."' AND '".$to_date."'")->result();	
}else{
return $this->db->query("SELECT trans_date,note,dr,cr,bal FROM 
transaction WHERE accounts_name='".$account."' AND $trans_type>0 AND trans_date
 between '".$from_date."' AND '".$to_date."'")->result();
}

}

//Day wise and date wise Income Report

public function getIncomeReport($from_date,$to_date,$group=''){
if($group=="group"){
return $this->db->query("SELECT trans_date,sum(amount) as amount FROM
	transaction WHERE type='Income' AND trans_date between '".$from_date."'
	AND '".$to_date."' GROUP BY trans_date")->result();	
}else{
return $this->db->query("SELECT * FROM transaction WHERE type='Income'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}
}

//Day wise and date wise Income Report

public function getExpenseReport($from_date,$to_date,$group=''){
if($group=="group"){
return $this->db->query("SELECT trans_date,sum(amount) as amount FROM
	transaction WHERE type='Expense' AND trans_date between '".$from_date."'
	AND '".$to_date."' GROUP BY trans_date")->result();	
}else{
return $this->db->query("SELECT * FROM transaction WHERE type='Expense'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}
}

//Transfer Report

public function getTransferReport($from_date,$to_date){
return $this->db->query("SELECT * FROM transaction WHERE type='Transfer'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		

}

//Category Report
public function getCategoryReport($from_date,$to_date,$cat){
return $this->db->query("SELECT * FROM transaction WHERE category='".$cat."'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}

//Payer Report
public function getPayerReport($from_date,$to_date,$payer){
return $this->db->query("SELECT * FROM transaction WHERE payer='".$payer."'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}

//Payee Report
public function getPayeeReport($from_date,$to_date,$payee){
return $this->db->query("SELECT * FROM transaction WHERE payee='".$payee."'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}


}