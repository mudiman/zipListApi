<?php
function strbool($value)
{
	return $value ? 'true' : 'false';
}

function curlCall($url,$auth=null,$headerarr=null,$method="GET",$body=null,$debug=false,$followlocation=true){
	
	
	$headers=array('User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22 (KHTML, like Gecko) Ubuntu Chromium/25.0.1364.160 Chrome/25.0.1364.160 Safari/537.22');
	if ($headerarr!=null){
		foreach ($headerarr as $head){
			$headers[]=$head;
		}
	}
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	if ($method=="POST"){
		curl_setopt($ch, CURLOPT_POST, 1);
		if(count($body) < 2 && !array_key_exists('payer_id', $body))
			$formdata=http_build_query($body);
		else
			$formdata = json_encode($body);
		
		//var_dump($formdata);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formdata);
	}
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followlocation);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	if($auth != Null)
		curl_setopt($ch, CURLOPT_USERPWD, $auth);
	
	$response = curl_exec ($ch);
	$responsenew = substr($response, strpos($response, "\r\n\r\n")+strlen(strpos($response, "\r\n\r\n")));
	$respheaders = get_headers_from_curl_response($response);
	curl_close ($ch);
	
	return array($responsenew,$respheaders);
}
function get_headers_from_curl_response($response)
{
	$headers = array();
	$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
	foreach (explode("\r\n", $header_text) as $i => $line){
	if ($i === 0)
	$headers['http_code'] = $line;
	else
	{
	list ($key, $value) = explode(': ', $line);
	$headers[$key] = $value;
	}
	}
	return $headers;
} 