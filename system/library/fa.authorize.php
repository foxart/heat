<?php
class faAuthorize
{
	var $login = 'authorization';
	
	public function check_request()
	{
		$result = false;
		if (isset($_COOKIE[session_name()]))
		{
			$result = true;
		};
		return $result;
	}
	
	public function check_session()
	{
		$result = false;
		if (isset($_SESSION))
		{
			$result = true;
		};
		return $result;
	}

	protected function start_session()
	{
		if ($this->check_session()==false)
		{
			session_start();
		}
	}
	
	public function check_parameter($name)
	{
		$result = false;
		if ($this->check_session()==true)
		{
			if (isset($_SESSION[$name]) == true)
			{
				$result = true;
			};
		};
		return $result;
	}
	
	public function get_parameter($name)
	{
		$result = false;
		if ($this->check_session()==true)
		{
			if (isset($_SESSION[$name]) == true)
			{
				$result = $_SESSION[$name];
			} else {
				raise_error('parameter not found: ' . $name);
				exit;
			};
		};
		return $result;
	}
	
	public function set_parameter($key, $value)
	{
		if ($this->check_session()==true)
		{
			$_SESSION[$key] = $value;
		};
	}
	
	
	
	/* AUTHORIZATION */
	public function check_login()
	{
		$result = false;
		if ($this->check_request()==true)
		{
			$this->start_session();
			if (isset($_SESSION[$this->login]['ip']) and $_SESSION[$this->login]['ip'] == $_SERVER['REMOTE_ADDR'])
			{
				$result = true;
			}
		};
		return $result;
	}
	
	public function get_login()
	{
		$result = false;
		if ($this->check_session()==true)
		{
			if (isset($_SESSION[$this->login])==true)
			{
				$result = $_SESSION[$this->login];
			}
		};
		return $result;
	}
	
	public function set_login_parameter($key, $value)
	{
		if ($this->check_session()==true)
		{
			$_SESSION[$this->login][$key] = $value;
		};
	}
	
	public function login($data)
	{
		if ($this->check_session()==false)
		{
			$this->start_session();
		};
		$_SESSION[$this->login]['ip'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION[$this->login]['time'] = time();
		foreach ($data as $key => $value)
		{
			$_SESSION[$this->login][$key] = $value;
		};
	}
	
	public function logout()
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
		session_unset();
		session_destroy();
		session_write_close();
	}
	/* AUTHORIZATION */
}
?>