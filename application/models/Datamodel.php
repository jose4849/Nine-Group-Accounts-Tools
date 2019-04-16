<?php

class DataModel extends CI_Model{

		function __construct()
    {
        parent::__construct();
    }

    //Backup Database
	function backup($table)
	{
		$this->load->dbutil();
			
		$options = array(
                'format'      => 'txt',            
                'add_drop'    => TRUE,           
                'add_insert'  => TRUE,              
                'newline'     => "\n"              
              );
				 
		if($table == 'all')
		{
			$tables = array('');
			$file_name	=	'system_backup';
		}
		else 
		{
			$tables = array('tables'	=>	array($table));
			$file_name	=	'backup_'.$table;
		}

		$backup =$this->dbutil->backup(array_merge($options , $tables)); 


		$this->load->helper('download');
		force_download($file_name.'.sql', $backup);
	}
	
	
	//RESTORE Database
	function restoreDatabase()
	{
		move_uploaded_file($_FILES['restore-file']['tmp_name'], 'uploads/backup.sql');
		$this->load->dbutil();
		
		
		$prefs = array(
            'filepath'						=> 'uploads/backup.sql',
			'delete_after_upload'			=> TRUE,
			'delimiter'						=> ';'
        );
		$restore =& $this->dbutil->restore($prefs); 
		unlink($prefs['filepath']);
	}

	function truncate($type)
	{
		if($type == 'all')
		{
			$this->db->truncate('accounts');
			$this->db->truncate('chart_of_accounts');
			$this->db->truncate('language');
			$this->db->truncate('payee_payers');
			$this->db->truncate('payment_method');
			$this->db->truncate('repeat_transaction');
			$this->db->truncate('settings');
			$this->db->truncate('transaction');
			$this->db->truncate('user');
		}
		else
		{	
			$this->db->truncate($type);
		}
	}


}