<?php
// Allow from any origin
if(isset($_SERVER["HTTP_ORIGIN"]))
{
    // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}
else
{
    //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");    // cache for 10 minutes

if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
{
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}


require_once "classes/DB.php";
require_once "classes/FormValidator.php";
require_once "classes/Json.php";

use FormValidator\Form;
use DB\DB;
use Json\Json;


$db = new DB;
$validator = new form($_POST);
$json = new json();

$raw_input = file_get_contents("php://input");
$_POST = json_decode($raw_input, true);

$method = $_SERVER['REQUEST_METHOD'];
//print_r($_POST);die();
if($method == 'POST'){

	$validator->validate([
		'firstname' 	=> 'required|string',
		'lastname' 		=> 'required|string',
		'email' 		=> 'required|email',
		'message' 		=> 'required|string',
	]);

	if($validator->passed()){

		http_response_code(200);

		$db->insert_contact([
			'firstname' 	=> $validator->input('firstname'),
			'lastname' 		=> $validator->input('lastname'),
			'email' 		=> $validator->input('email')
			'message' 		=> $validator->input('message'),
		]);

		$subject = $validator->input('firstname');
		$to = "abdulsalamkayodeishaq@gmail.com";
		$from = $validator->input('email');
		$msg = $validator->input('message');

		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: ". $from. "\r\n";
		$headers .= "Reply-To: ". $from. "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		$headers .= "X-Priority: 1" . "\r\n";
		
		$sent;

		$mail = mail($to, $subject, $msg, $headers);

		if($mail){
			$sent = true; 
		}else {
			$sent = false; 
		}

		$json->encode([
			'sent' => $sent,
			'data' => [
				'firstname'	=> $subject,
				'lastname' 	=> $validator->input('lastname'),
				'email' 		=> $from,
				'message' 		=> $msg,
			]
		]);
	}else {
		$json->encode([
			'errors' => $validator->errors
		]);
	}
		
}else {
	$json->encode([
		'message' => $method.' is not supported for the route'
	]);
}