<?php
function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    echo "You have CORS!". PHP_EOL;
}
cors();

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
date_default_timezone_set('Asia/Bangkok');

error_reporting(E_ALL);
// $request_data = json_decode(file_get_contents("php://input"));
// if ($request_data->action == "email") {
//     $from_email = $request_data->from_email;
//     $to_email = $request_data->to_email;
//     $subject_name = $request_data->subject;
//     $message = $request_data->message;
    
//     $mail = new PHPMailer(true); // Passing `true` enables exceptions
//     $mail->CharSet = "utf-8";
    
//     try {
//         //Server settings
//         $mail->SMTPDebug = 2; // Enable verbose debug output
//         $mail->isSMTP(); // Set mailer to use SMTP
//         $mail->Host = 'smtp.gmail.com'; 
//         $mail->SMTPAuth = true; 
//         $mail->Username="somnuk.sin2@gmail.com";
//         $mail->Password="ggaofdglveyezfag";
//         $mail->SMTPSecure = 'ssl';
//         $mail->Port = 465; 
//         //Recipients
//         $mail->setFrom('somnuk.sin2@gmail.com', 'Sender');  
//         $mail->addAddress($to_email, 'Receiver'); 
//         //Content
//         $mail->isHTML(true);
//         $mail->Subject = $subject_name;
        
//         $headers = "MIME-Version: 1.0" . "\r\n";
//         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//         $headers .= 'From: '.$from_email.'<'.$from_email.'>' . "\r\n".'Reply-To: '.$from_email."\r\n" . 'X-Mailer: PHP/' . phpversion();
        
//         $message = '<!doctype html>
//     			<html lang="en">
//     			<head>
//     				<meta charset="UTF-8">
//     				<meta name="viewport"
//     					  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
//     				<meta http-equiv="X-UA-Compatible" content="ie=edge">
//     				<title>Document</title>
//     			</head>
//     			<body>
//     			<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">'.$message.'</span>
//     				<div class="container">
//                      '.$message.'<br/>
//                         Regards<br/>
//                       '.$from_email.'
//     				</div>
//     			</body>
//     			</html>';       
    			
//         $mail->Body    = $message;
//         $mail->WordWrap = 50;
        
//         if(!$mail->Send())
//         {
//             echo "Message could not be sent. <p>";
//             echo "Mailer Error: " . $mail->ErrorInfo;
//             exit;
//         }else {
//             $mailer->ClearAllRecipients();        
//             echo "Message has been sent";
//         }
//     } catch (Exception $e) {
//         echo 'Message could not be sent.';
//         echo 'Mailer Error: ' . $mail->ErrorInfo;
//     }
// }else{
//     echo json_encode(false, JSON_UNESCAPED_UNICODE);
// }
?>