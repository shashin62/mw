<?php

//This File is required with class.phpmailer.php, class.smtp.php, class.pop3.php for the email
require 'PHPMailerAutoload.php';
require_once ("upload_class.php");

//There should be submit button in your form with name="submit"
$submit=$_POST['submit'];
$msg = "";
$max_size = 1024*1024*8; // the max. size for uploading
$error = '';

if(!empty($submit)){
	
$headers = "From: ".$_REQUEST['name'].":\r\n";
//$headers .= "Reply-To: ". FROM_ADDRESS . "\r\n";''.$_REQUEST['email']
$headers .= "Reply-To: ". $_REQUEST['email'] . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
//Create html page for the users data 
$message = '<html><body>';
$message .= '<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
              <tr>
                <td width="35%" class="contact-form">Name:<font color="red" size="3">*</font></td>
                <td width="5%" class="contact-form">:</td>
                <td width="70%" class="contact-text-form">'.$_REQUEST['name'].'</td>
              </tr>
              <tr>
                <td class="contact-form">Email:<font color="red" size="3">*</font></td>
                <td class="contact-form">:</td>
                <td width="70%" class="contact-text-form">'.$_REQUEST['email'].'</td>
              </tr>
              <tr>
                <td class="contact-form">Enquiry:<font color="red" size="3">*</font></td>
                <td class="contact-form">:</td>
                <td width="70%" class="contact-text-form">'.$_REQUEST['enquiry'].'</td>
              </tr>
			</table>';
$message .= '</body></html>';
//End Html Page Here

$mail = new PHPMailer;
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host = 'localhost';
$mail->Port = 25;
$mail->ssl = false;
$mail->authentication = false;


$mail->From = ''.$_REQUEST['email'];	//Any Email Can be put here
$mail->FromName = ''.$_REQUEST['name'];	//Any Name Can be put here

$mail->addAddress('makewhale@gmail.com');	// Add a recipient
$mail->addAddress('contact@makewhale.com');		// Name is optional
//$mail->addBCC('shaileshghadge@gmail.com');		// Name is optional
//$mail->addBCC('shailesh.ghadge@yahoo.co.in');		// Name is optional
//$mail->addBCC('rohit@supramind.com');		// Name is optional
//$mail->addBCC('abhishek.kamble@supramind.com');		// Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('vishal@togglehead.in');
//$mail->addBCC('tanvi@togglehead.in','tanvi');

//Looping Files Here
$num_files = count($_FILES['upload']['name']);
	for ($i = 0; $i < $num_files; $i++) {
		if ($_FILES['upload']['name'][$i] != '') {

			$my_upload = new file_upload;
			$my_upload->rename_file = true;
			$my_upload->upload_dir = "files/mail"; 
			$my_upload->extensions = array(".png", ".jpg", ".gif", ".docx", ".doc", ".jpeg", ".pdf"); 
			$my_upload->the_temp_file = $_FILES['upload']['tmp_name'][$i];
			$my_upload->the_file = $_FILES['upload']['name'][$i];
			$my_upload->http_error = $_FILES['upload']['error'][$i];
			$my_upload->replace = "n"; 
			if ($my_upload->upload()) { 
				$full_path = $my_upload->upload_dir.$my_upload->file_copy;
//				echo"$full_path";
				$mail->addAttachment($full_path); // Add attachments
				$error .= 'File &quot;'.$my_upload->file_copy.'&quot; uploaded<br />';
			} else {
				break;
				$error = 'Error uploading file(s)';
			}
		}
	}
//End Looping Files

//$mail->addAttachment('../images', '3C Collective.jpg');    // Optional name
$mail->isHTML(true);	// Set email format to HTML
$mail->Subject = 'Email From Makewhale Website - Contact Us Form';
$mail->Body    = $message;
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent. Your Email Format should be abc@example.com';
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
	//Test is email sending is working
	//$to = "shaileshghadge@gmail.com"; // 
    //$subject = $mail->Subject;//"Test mail";
    // //$message = "Hello! This is a simple test email message.";
    //$from = "sender@emailaddress.here";
    //$headers1 = "From:" . $from;
    //mail($to,$subject,$message,$headers1);
    //echo "Mail Sent.".$mail->From."Other: ".$headers;
} else {
    //echo ' Message has been sent';
}

header("Location:../contact-us-thank-you.php#con-thank-you");

exit();

}else{
	echo 'any field value is missing or you have not given name="submit" to submit button';
}

?>