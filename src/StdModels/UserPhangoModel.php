<?php
/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraUtils/Login
*
* Now, we define components for use in models. Components are fields on a table.
*
*/
/*
namespace PhangoApp\Framework\StdModels;

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
*/
I18n::loadLang('users');

/**
* Children class of webmodel for use with login class
*
*/

class UserPhangoModel extends Webmodel {

	public $username='username';
	public $email='email';
	public $password='password';
	public $repeat_password='repeat_password';

	public function insert($post)
	{
	
		if($this->check_user_exists($post[$this->username], $post[$this->email]))
		{
		
			if(!$this->check_password($post['password'], $post['repeat_password']))
			{
			
				//$this->components['password']->required=0;
				
				$this->components[$this->password]->std_error=I18n::lang('users', 'pasword_not_equal_repeat_password', 'Passwords are not equal');
				
				return false;
			
			}
		
			return parent::insert($post);
		
		}
		else
		{
		
			$this->std_error=I18n::lang('users', 'cannot_insert_user_email_or_user', 'A user already exists with this email or username');
		
			return false;
		
		}
	
	}
	
	public function update($post, $where_sql='')
	{
	
		if(isset($post[$this->username]) && $post[$this->email])
		{
	
			if($this->check_user_exists($post[$this->username], $post[$this->email], $post['IdUser_admin']))
			{
			
				if(!$this->check_password($post['password'], $post['repeat_password']))
				{
				
					//$this->components['password']->required=0;
					
					$this->components[$this->password]->std_error=I18n::lang('users', 'pasword_not_equal_repeat_password', 'Passwords are not equal');
					
					return false;
				
				}
				
				if(Utils::form_text($post['password'])=='')
				{
				
					$this->components[$this->password]->required=0;
					unset($post[$this->password]);
				
				}
			
				return parent::update($post, $where_sql);
			
			}
			else
			{
			
				$this->std_error=I18n::lang('users', 'cannot_insert_user_email_or_user', 'A user already exists with this email or username');
			
				return false;
			
			}
			
		}
		else
		{
		
			return parent::update($post, $where_sql);
		
		}
	
	}
	
	public function check_password($password, $repeat_password)
	{
	
		$password=Utils::form_text($password);
		$repeat_password=Utils::form_text($repeat_password);
		
		if($password!=$repeat_password)
		{
		
			return false;
		
		}
		
		return true;
	
	}
	
	public function check_user_exists($user, $email, $iduser=0)
	{
	
		$user=$this->components[$this->username]->check($user);
		$email=$this->components[$this->email]->check($email);
		
		$where_sql='where ('.$this->username.'="'.$user.'" or '.$this->email.'="'.$email.'")';
		
		settype($iduser, 'integer');
		
		if($iduser>0)
		{
		
			$where_sql.=' and IdUser_admin!='.$iduser;
		
		}
		
		$c=$this->select_count($where_sql);
		
		if($c==0)
		{
		
			return true;
		
		}
		else
		{
		
			return false;
		
		}
		
	
	}

}

?>