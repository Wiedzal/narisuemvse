<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	const ERROR_IP_INVALID = 3;
	const ERROR_EMAIL_INVALID=4;
	
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function __construct($username, $password)
	{
		parent::__construct($username, $password);
	}
	 
	public function authenticate()
	{
		if (strpos($this->username,"@"))
		{
			$record = Users::model()->find('email=:username', array(':username' => $this->username));  
		}
		else
		{
			$record = Users::model()->find('username=:username', array(':username' => $this->username));  
		}

		if ($record === null)
		{
			if (strpos($this->username,"@")) 
			{
				$this->errorCode = self::ERROR_EMAIL_INVALID;
			} 
			else
			{
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			}
		}
		else if ($record->password !== $this->password)
		{
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			$this->_id = $record->getPrimaryKey();
			$this->setState('username', $record->username);
			$this->setState('id', $record->id);
			$this->errorCode = self::ERROR_NONE;
		}

		return !$this->errorCode;
	}
	
	public function getId()
	{
    	return $this->_id;
	}
}