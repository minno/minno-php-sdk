<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript" src="jquery.form.js"></script>
        <script type="text/javascript" src="https://www.minno.co/minno.js"></script>
        <link type="text/css" href="style.css" media="screen" rel="stylesheet" />
        <title>Minno Examples</title>
    </head>
    <body>
        <div id="header">
           Welcome to the Minno Examples Page!
        </div>
        <div id="exampleNotif">
            <p>
                The examples on this page will charge
                your Minno account. Don't have one? Just click the Minno
                button below to sign up. You get <b>$2.00 in free Minno
                credit</b> to start with &mdash; <b>no credit card</b>,
                no forms, and you don't even have to touch your keyboard.
            </p>
            <p>
                <b>Plus:</b> You can clear your example purchases by visiting
                your <a href="https://www.minno.co/account#history"
                target="_blank">account history</a>.
				If you have any trouble, shoot us an email at
                <strong>&#115;&#117;&#112;&#112;&#111;&#114;&#116;&#64;&#109;&#105;&#110;&#110;&#111;&#46;&#99;&#111;</strong>,
                and we'll be happy to help!
			</p>
        </div>
        <div id="examplesColumn">
            <div class="exampleCont">
                <div class="exampleTitle">
                    Example 1: File Emailer
                </div>
                <div class="exampleBody">
                    <p>
                        This example lets you send a file to an email address.
                        (Of course you wouldn't need this "in real life", but it's
                        similar to services that use Minno.)
                    </p>
                    <p>
                        Simply choose a file to send and enter the destination
                        email. The file will then be sent as an attachment to
                        that address. (Max file size is 1MB.)
                    </p>
                    <div id="formCont">
                        <form id="fileForm" action="upload.php" method="POST">
                            <span class="formLabel">File:</span>
                            <input id="fileInput" type="file" name="file" />
                            <span id="fileName"></span>
                            <br/><br/>
                            <span class="formLabel">Email:</span>
                            <input id="emailInput" type="text" name="email" />
                            <input id="userIdInput"
                                   type="hidden"
                                   name="userId" />
                            <input id="invitemIdInput"
                                   type="hidden"
                                   name="invitemId" />
                            <input id="verifTokenInput"
                                   type="hidden"
                                   name="verifToken" />
		        </form>
                        <div id="waitDiv">
                            <p>
                                Please wait while your file is sent...
                            </p>
                            <img src="throbber.gif" />
                        </div>
                        <div id="outcomeDiv"></div>
                    </div>
                    <div id="buyInstructions">
                        Please pay $0.25, then click the "Send File" button.
                        <br/>
                        Sign up by clicking the Minno
                        button &mdash; no credit card required!
                    </div>
                    <div id="buttonCont">



                        <!-- Begin Add Minno Button -->
                        <div id="buyButton">
                            <div class="minno-button" id="xxx"></div>
                        </div>
                        <!-- End Add Minno Button -->



                        <button id="sendButton" disabled="true">
                            Send File
                        </button>
                        <div style="clear: both;"></div>
                    </div>
		</div>
            </div>
        </div>
        <script type="text/javascript">
            // Make sure can use trim() method
            if (typeof String.prototype.trim !== 'function') {
                String.prototype.trim = function() {
                    return this.replace(/^\s+|\s+$/g, '');
                }
            }

            $(document).ready(function() {
                // Initialize the form, so we can POST asynchronously
                $("#fileForm").ajaxForm();

                // Display chosen file in bold, once user selects it
                $("#fileInput").bind("change", function() {
                    $("#fileInput").css("top", "-100px");
                    var fileName = $("#fileInput").val();

                    // Modify fileName if too long, or if include fake path
                    if (fileName.lastIndexOf("\\") != -1) {
                        fileName = fileName.substr(
                            fileName.lastIndexOf("\\") + 1);
                    }
                    if (fileName.lastIndexOf("/") != -1) {
                        fileName = fileName.substr(
                            fileName.lastIndexOf("/") + 1);
                    }
                    if (fileName.length > 35) {
                        fileName = fileName.substr(0, 32) + "...";
                    }

                    $("#fileName").html("<b>" + fileName  + "</b>");
                    $("#fileName").fadeIn();
                });
            });


        /********************************************************************
         * Defining the minnoCallback function
         *
         * The callback that the Minno button executes when a user completes a
         * purchase. This is the only function that you need to define in order
         * to get the Minno button working!
         *
         * To see an example of the server-side code that interacts with this
         * function, check out the upload.php file in this directory.
         * (Download at https://github.com/minno/minno-php-sdk.)
         */
        function minnoCallback(userId, invitemId, verifToken) {
            // Fill in the values of the hidden input elements so that the user
            // ID, invitem ID, and verifToken are posted to the server. This
            // allows the the server to verify that the purchase completed
            // successfully.
            $("#userIdInput").attr("value", userId);
            $("#invitemIdInput").attr("value", invitemId);
            $("#verifTokenInput").attr("value", verifToken);

            // Start the send button listening for clicks
            $("#sendButton").bind("click",
                function() {
                    // Disable the send button, since the transfer is
                    // happening right now
                    $("#sendButton").unbind("click");
                    $("#sendButton").attr("disabled", "true");
                    $("#sendButton").css("cursor", "default");

                    // Fade in the wait div while file is uploading and emailing
                    // And hide the form
                    $("#fileForm").fadeOut(250, function() {
                        $("#waitDiv").fadeIn();
                    });

                    // Post the form to our upload.php script. Examine that file
                    // to see what happens on the server.
                    $("#fileForm").ajaxSubmit({
                        // Make form plugin use iframe, not XHR, since uploading
                        // files is tricky with XHR
                        "iframe" : true,
                        "resetForm" : true,
                        // Notify user once file is sent
                        "success" : function(statusText) {
                            $("#waitDiv").fadeOut(function() {
                                // Handle case where webserver rejects file if too big
                                if (statusText.trim() == "") {
                                    statusText = "File too big!";
                                }
                                $("#outcomeDiv").text(statusText);
                                $("#outcomeDiv").fadeIn();
                            });
                        }
                    });
                    return false;
                }
            );

            // Allow the user to click the send file button
            $("#sendButton").removeAttr("disabled");
            $("#sendButton").css("cursor", "pointer");
            $("#sendButton").text("Send File!");
        }
        </script>
    </body>
</html>
