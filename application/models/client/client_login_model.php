<?php

if(!defined('BASEPATH')) exit('You do not have access');
class Client_Login_Model extends CI_Model{
	
	function client_login($ans)
	{
			
				$email=$ans['email'];
				$password=md5($ans['password']);
				
		
			$query                                                           = $this->db
																			->select('
																					id,
																					full_name,
																					email,
																					password,
																					
																			')
																			->from('student_signup_info')
																			->where('email',$email)
																			->where('password',$password)
																			-> get();
		 if($query->num_rows==0)
			{
				return "no";
				
			}
			else
			{
				return  $query->result_array();
				
			}
	}		

}
?>
