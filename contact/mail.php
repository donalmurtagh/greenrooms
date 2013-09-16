<?php
require 'class.phpmailer.php';

/* Set e-mail recipient, change this to matthewshearer@gmail.com once testing is complete */
$myemail = "domurtag@yahoo.co.uk";

/* Check all form inputs using check_input function */
$name    = check_input($_POST['name'], "Enter your name");
$email   = check_input($_POST['email']);
$subject = "Green Rooms Design feedback from: $email";
$message = check_input($_POST['message'], "Write your message");

/* If e-mail is not valid show error message */
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
    show_error("E-mail address not valid");
}
/* Let's prepare the message for the e-mail */
$message = "Name: $name
E-mail: $email
Subject: $subject
----------------------------
Message:
$message";

/* Send the message using mail() function */
smtpmailer($myemail, $email, $name, $subject, $message);

/* Redirect visitor to the thank you page */
header('Location: index.html');
exit();

function smtpmailer($to, $from, $from_name, $subject, $body) {
    global $error;
    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = 'festivals@festivals.ie';

    // TODO don't commit password
    $mail->Password = '';
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    if(!$mail->Send()) {
        $error = 'Mail error: '.$mail->ErrorInfo;
        return false;
    } else {
        $error = 'Message sent!';
        return true;
    }
}

function check_input($data, $problem = '')
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($problem && strlen($data) == 0) {
        show_error($problem);
    }
    return $data;
}

function show_error($myError)
{
?>
<html>
<body>

<p>Please correct the following error:</p>
<strong><?php
    echo $myError;
?></strong>
<p>Hit the back button and try again</p>

</body>
</html>
<?php
    exit();
}
?>