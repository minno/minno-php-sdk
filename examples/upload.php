<?php

/**
 * Configuration
 *
 * You will need to customize these values to make this example work on your own
 * server.
 */

// The name/email that will be set in the email's "From:" field
$email_from = "";

// The SMTP server from which to send the email (ex. ssl://smtp.gmail.com)
$email_host = "";

// The port used to connect to the SMTP server (ex. 465)
$email_port = "";

// Whether or not to use SMTP authentication
$email_auth = true;

// Username for SMTP authentication
$email_username = "";

// Password for SMTP authentication
$email_password = "";

// A temporary directory to store the uploaded files. They will be deleted after
// they're sent. The name of this directory should end with a trailing slash,
// and its permissions should be set to 777.
$temp_file_dir = "";

/***********************************************************
 * Begin Minno Integration
 *
 * This is all the code you need to include in order to perform server-side
 * purchase verification using the Minno PHP SDK.
 *
 * For more information and examples, check out our full documentation at
 * https://www.minno.co/docs
 */
require_once "../src/minno.php";

$user_id = $_POST["userId"];
$invitem_id = $_POST["invitemId"];
$verif_token = $_POST["verifToken"];

// Dies if the purchase is invalid. You can also use is_valid_purchase(), which
// returns a boolean
Minno::assert_valid_purchase($user_id, $invitem_id, $verif_token);

/**
 * End Minno Integration
 *
 * All code beyond this point is specific to the example. In this case, it
 * simply uploads the file and sends it to the specified email address.
 **********************************************************/

// Relies on the PEAR Mail package
// http://pear.php.net/package/Mail/
require_once "Mail.php";
require_once "Mail/mime.php";

// Collect info on the file that is being sent
$f_name = $_FILES["file"]["name"];
$f_tmp_path = $_FILES["file"]["tmp_name"];
$f_new_path = $temp_file_dir . $f_name;
$f_size = $_FILES["file"]["size"];

if ($f_size < 1024 * 1024 * 1) {
    // Move the file so that it is named correctly, so that
    // attachment has correct name.
    if (move_uploaded_file($f_tmp_path, $f_new_path)) {
        // Define the email that will be sent
        $from = $email_from;
        $to = $_POST["email"];
        $subject = "File delivery!";
        $text = "You've just been sent: " . $f_name . ".\n\n"
            . "This email is part of a simple example of Minno usage. "
            . "The example page is at http://example.minno.co/, and "
            . "the Minno home page is at https://www.minno.co/.";

        $message = new Mail_mime();
        $message->setTXTBody($text);
        $message->addAttachment($f_new_path);
        $body = $message->get();

        // The headers() method in $message will get the proper MIME
        // headers for the content included. But still need the basic
        // mail headers, too
        $extraheaders = array ("From" => $from,
                               "To" => $to,
                               "Subject" => $subject);
        $headers = $message->headers($extraheaders);

        $smtp = Mail::factory("smtp",
                              array ("host" => $email_host,
                                     "port" => $email_port,
                                     "auth" => $email_auth,
                                     "username" => $email_username,
                                     "password" => $email_password));
        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            echo "Sorry, there was an error sending your file.";
        } else {
            echo "Cool! Your file has been sent!";
        }

        // Delete the file, since sent, and no longer needed
        unlink($f_new_path);
    } else {
        echo "Sorry, there was an error receiving your file.";
    }
} else {
    echo "File is too big!";
}
?>

