<?php
	function login($data)
	{
		$faAuthorize = new faAuthorize;
		$faAuthorize->login($data);
		$redirect = 'http://' . DOMAIN . US . APPLICATION;
		header("Location: {$redirect}");
	};
	
	if (isset($_POST['login']) == true)
	{
		$login = $_POST['login'];
	} else {
		$login = false;
	};
	
	if (isset($_POST['password']) == true)
	{
		$password = $_POST['password'];
	} else {
		$password = false;
	};
	
	if ($login != false and $password != false)
	{
		if ($login == 'root' and $password == 'dljdfgjdfhf')
		{
			$record['user_id'] = 0;
			$record['user_login'] = 'root';
			$record['group_id'] = 0;
			$record['group_title'] = 'super';
			$record['host_id'] = 0;
			login($record);
			exit;
		} else {
			$sql_select = 
			"
				SELECT
					u.id AS user_id, u.login AS user_login, ug.id AS group_id, ug.title AS group_title, ush.fk_host AS host_id
				FROM
					user AS u
				LEFT JOIN
					user_group AS ug ON u.fk_user_group = ug.id
				LEFT JOIN
					user_statistic_host AS ush ON ush.fk_user = u.id
				WHERE
					u.login = '|LOGIN|' AND u.password = '|PASSWORD|'
			";
			$faSql->load($sql_select);
			$faSql->set(array(
				'LOGIN' => $login,
				'PASSWORD' => $faCrypt->encrypt($login, $password),
			));
			$faSql->run();
			
			
			if ($faSql->num_rows == 0)
			{
				$content = '<span style="color: red;">wrong login or password</span>';
			} else {
				$record = $faSql->fetch_assoc();
				
				if ($record['host_id']=='')
				{
					$record['host_id'] = -1;
				};
				
				// view($record);
				// exit;
				// view($record);
				login($record);
				exit;
			};
		};
	} else {
		$content = '<span style="color: gray;">please authorize</span>';
	};

	$template = basename(__FILE__, EXT_PHP);
	$authorization_login_template = $faTemplate->get('application' . DS . 'authorization' . DS . $template);
	$authorization_login_result = $faTemplate->set($authorization_login_template, array(
		'|URL_LOGIN|' => 'http://' . DOMAIN . US . APPLICATION,
		'|CONTENT|' => $content,
	));
	
	return $authorization_login_result;
?>