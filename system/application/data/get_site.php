<?php
	function get_request($url, $parameters = false)
	{
		$result = array();

		/* set cURL Resource */
		$curl_handle = curl_init();
		
		/* Tell cURL to set URL */
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		
		/* Tell cURL to set user agent */
		curl_setopt($curl_handle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		
		/* Tell cURL to return the output */
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		
		/* Tell cURL to return the headers */
		curl_setopt($curl_handle, CURLOPT_HEADER, true);
		/* Tell cURL NOT to return the headers */
		// curl_setopt($curl_handle, CURLOPT_HEADER, false);
		
		/* Tell cURL to handle https */
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		
		/* Tell cURL to use default HTTP POST. Given POST method uses default application/x-www-form-urlencoded, used in HTML forms. */
		// curl_setopt($curl_handle, CURLOPT_POST, true);
		
		/* Tell cURL to use GET method */
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
		
		/* Tell cURL to use POST method */
		// curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'POST');
		
		/* Tell cURL to set custom headers */
		// curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
			// 'Content-Type: application/xml'
			// 'Content-Type: application/json',
		// ));
		
		// curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
			// 'Content-Type: multipart/form-data'
		// ));
		
		/* Tell cURL to include POST parameters */
		if (isset($parameters)==true)
		{
			// $post_parameters = urldecode(http_build_query($parameters));
			$post_parameters = $parameters;
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_parameters);
		};
		
		/* Tell cURL to show outgoing headers */ 
		curl_setopt($curl_handle, CURLINFO_HEADER_OUT, true);
		
		/* Tell cURL to set request timeout */ 
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5);
		
		/* Tell cURL to output additional information */ 
		// curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
		
		$response = curl_exec($curl_handle);
		$request = curl_getinfo($curl_handle);
		
		$response_header_size = curl_getinfo($curl_handle, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $response_header_size);
		
		$result['request'] = $request;
		$result['response']['header'] = $response_header;
		$result['response']['body'] = substr($response, $response_header_size);
		
		curl_close($curl_handle);
		return $result;
	};
	
	
	if (isset($_GET['site'])==false)
	{
		echo 'no site';
		exit;
	};
	
	$site = $_GET['site'];
	
	$response = get_request($site);
	$body = $response['response']['body'];
	
	$html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $body);
	
	// $api = '<script src="comodo.heat.api.php" type="text/javascript"></script>';
	// $html = str_replace($api, '', $body);
	
	echo $html;
	
	exit;
?>