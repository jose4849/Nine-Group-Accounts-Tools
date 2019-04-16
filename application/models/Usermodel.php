<?php

class Usermodel extends CI_Model{

public function login($email,$password)
{
$this->db->select('*');
$this->db->from('user');
$this->db->where('email',$email);
$this->db->where('password',$password);
$query=$this->db->get();
$row_count=$query->num_rows();
if($row_count>0){
$userdata=$query->row();	
$newdata = array(
'user_id'  => $userdata->user_id,	
'username'  => $userdata->user_name,
'fullname'  => $userdata->fullname,
'user_type' => $userdata->user_type,
'email'     => $userdata->email,
'logged_in' => TRUE
);
$this->session->set_userdata($newdata);	
$this->setLoginTime($userdata->user_id);
return true;	
}
return false;
}	

public function logout(){
$newdata = array(
'user_id'   => '',
'username'  => '',
'fullname'  => '',
'email'     => '',
'logged_in' => FALSE
);
$this->session->set_userdata($newdata);	

}

public function setLoginTime($user_id){
$data['last_login']=date("Y-m-d H:i:s");
$this->db->where('user_id',$user_id);
$this->db->update('user',$data);
}

}