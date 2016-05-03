<?php
header('Content-Type: application/json');
/*
@author:Bryan Herrera
@description: program provides a json response with n numbers of
the fibonacci sequence. N is provided by the url in a curl command.
*/
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$value = explode("?", $actual_link);
$n = key($_GET);
if(isset($n)){
	//determine response code
	$code = http_response_code();
	if($code == 200){
		$success = "success";
	}
	else $success = "fail";

	//prepare solution array
	$result = array();
	fib($n,$result);
	//form the response
	$response = array(

		"status" => $code,
		"success" =>$success,
		"version"=>"JSON-Array-0.1",
		"Fibonacci" => $n,
		"numbers" =>$result,
		);
	//return the response
	echo json_encode($response,JSON_PRETTY_PRINT|JSON_FORCE_OBJECT);
}else{
	//throw an error if no N value provided.
	echo "value not provided in the URL!";
}
/*
	@parameters
	@n: the number of fibonacci numbers to return
	@&result: the fibonacci numbers that are returned.
*/
function fib($n, &$result){
	$index = 0;
	$val = 0;
	if($n >= 1){
		$result[$index] = $val;
		$index+=1;
		if($n >= 2){
			$val += 1;
			$result[$index] = $val;
			$index +=1;
			while($index<$n){
				$result[$index]=$result[$index-1] + $result[$index-2];
				$index++;
			}

		}
	}
}
?>