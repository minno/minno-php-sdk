Minno PHP Example
=================

This is a simple example that lets the user send a file to the specified email
address.

For more examples, visit our [API Documentation page](https://www.minno.co/docs).

Usage
-----

**NOTE**: If you want to run this on your own server, you will need to create a
  Minno button item in the Partner Dashboard. Any purchases made with that
  button on your own server will make real charges to your Minno account that
  can't be reverted!

To run this example on your own server, you'll first need to edit the
configuration variables at the top of the upload.php file:

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

    // A temporary directory to store the uploaded files. They will be deleted
    // after they're sent. The name of this directory should end with a trailing
    // slash, and its permissions should be set to 777.
    $temp_file_dir = "";

    // Your partner verification token. This can be found on the "Settings" tab
    // in the Partner Dashboard
    $verif_token = "";

You will also need to create an inventory item in the partner dashboard and edit
the line that adds the Minno button in index.php to reflect your newly created
item:

    <div class="minno-button" id="xxx"></div>

Then simply visit the index.php page and watch this example app run!
