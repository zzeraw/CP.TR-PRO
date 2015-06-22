<html>
<head>
<title>PHPMailer - SMTP (Gmail) advanced test</title>
</head>
<body>

<?php
$msg = "<p>Name: " . strip_tags($_POST["name"]) .  "</p>";
$msg .= "<p>Email: " . strip_tags($_POST["email"]) .  "</p>";
$msg .= "<p>Message: " . strip_tags($_POST["message"]) .  "</p>";

require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP

try {
  $mail->Host       = "mail.omega-r.com"; // SMTP server
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
  $mail->Username   = "kostya@gmail.com";  // GMAIL username
  $mail->Password   = "pass";            // GMAIL password
  $mail->AddAddress('alexey@omega-r.com', '');
  // $mail->AddAddress('karpov.kostya@gmail.com', '');
  $mail->SetFrom('kostya@omega-r.com', 'omega-r.com');
  $mail->AddReplyTo('kostya@omega-r.com', 'omega-r.com');
  $mail->Subject = 'Letter from omega-r.com';
  $mail->MsgHTML($msg);
  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
?>

</body>
</html>
