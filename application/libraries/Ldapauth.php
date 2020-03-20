<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// https://ellislab.com/codeigniter/user-guide/general/creating_libraries.html

class Ldapauth
{
	

	private $ldap_host;
	private $ldap_port;
	private $ldap_bind_dn;
	private $ldap_bind_pass;
	private $ldap_base_dn;
	
  	public function __construct($ldap_config)
    {
			
			
		//print_r($ldap_config); die;
			
		$this->ldap_host=$ldap_config['ldap_host'];
		$this->ldap_port=$ldap_config['ldap_port'];
		$this->ldap_bind_dn=$ldap_config['ldap_bind_dn'];
		$this->ldap_bind_pass=$ldap_config['ldap_bind_pass'];
		$this->ldap_base_dn=$ldap_config['ldap_base_dn'];

    }
	
	
	
	public function auth($email, $password)
	{
		
			$host=$this->ldap_host;
			$port=$this->ldap_port;
			$bind_dn=$this->ldap_bind_dn;
			$bind_pass=$this->ldap_bind_pass;
			$base_dn=$this->ldap_base_dn;
	
			$auth=0;
			$error=array();
			$user=array();
		
					
			$con = ldap_connect($host, $port);
			
			if(!$con)
			{
				$error[]="Could not connect to LDAP server.";
	
			}
			else
			{
				
		
				ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($con, LDAP_OPT_REFERRALS, 0);
	
				$bind = @ldap_bind($con, $bind_dn, $bind_pass);
				
				if($bind)
				{
						$filter = "(mail=$email)";
						
						$read = ldap_search($con, $base_dn, $filter, array('givenname','sn','cn','mail', 'uid','dn'));
	
						if(!$read)
						{
							$error[]="Unable to search LDAP server.";
	
						}
						else
						{
							$info = @ldap_get_entries($con, $read);
							
							
							$count=$info['count'];
							
							$user['uid']=$info[0]["uid"][0];
							$user['name']=$info[0]["cn"][0];
							$user['email']=$info[0]["mail"][0];
							$user['dn']=$info[0]["dn"];
							

							if($count > 0 && !empty($user['email']) && !empty($user['uid']) && !empty($user['dn'])) // before calling ldap_bind, make sure that the user is found in search
							{
	
								$dn=$info[0]["dn"];
								$bind = @ldap_bind($con, $dn, $password);

								if($bind)
								{
									$auth=1;	
								}
								else
								{	
									$error[]="Invalid Email ID/Password.";
								}
							
							}
							else
							{
								$error[]="Invalid Email ID/Password.";
							}
							
						}			
		
				}
				else
				{
					$error[]="LDAP bind command failed.";	
				}
				
				ldap_close($con);
				
			}
			
			
			
			
			$ret=array('auth'=>$auth, 'error'=>$error, 'user'=>$user);
								
								
			return $ret;
		
		
	}
	
	
	public function search($email)
	{
		
			$host=$this->ldap_host;
			$port=$this->ldap_port;
			$bind_dn=$this->ldap_bind_dn;
			$bind_pass=$this->ldap_bind_pass;
			$base_dn=$this->ldap_base_dn;
			
			$found=0;
			$error=array();
			$user=array();
		
			$con = ldap_connect($host, $port);
			
			if(!$con)
			{
				$error[]="Could not connect to LDAP server.";
	
			}
			else
			{
				ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($con, LDAP_OPT_REFERRALS, 0);
	
				$bind = @ldap_bind($con, $bind_dn, $bind_pass);
	
				if($bind)
				{	
						$filter = "(mail=$email)";
						
						$read = ldap_search($con, $base_dn, $filter, array('givenname','sn','cn','mail', 'uid','dn'));
	
						if(!$read)
						{
							$error[]="Unable to search LDAP server.";
	
						}
						else
						{
							
							
							$info = @ldap_get_entries($con, $read);
							
							//echo "<pre>";
							//print_r($info);	
							
							if($info['count']>0) // found
							{
								$found=1;
								$user['uid']=$info[0]["uid"][0];
								$user['name']=$info[0]["cn"][0];
								$user['email']=$info[0]["mail"][0];
								$user['dn']=$info[0]["dn"];
								
							}
							else
							{
								
								$found=0;
								$user=array();
								$error[]='Email not found in LDAP';
							}	
							
					   }			
		
				}
				else
				{
					$error[]="LDAP bind command failed.";		
				}
				
				ldap_close($con);
				
			}
			
			$ret=array('found'=>$found, 'error'=>$error, 'user'=>$user);
			
			return $ret;
		
	}
	
}
