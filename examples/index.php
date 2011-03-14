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
                <b>Note:</b> The examples on this page will actually <b>charge
                your Minno account</b>. We do this so you can see <i>exactly</i>
                what it's like to make a purchase with Minno.
            </p>
            <p>
                <b>But:</b> You can clear your example purchases by visiting
                your <a href="https://www.minno.co/account#history"
                target="_blank">account history</a>.  Look for a button in the
                right sidebar that says <b>Clear</b>.
            </p>
            <p>
                If you have any trouble, shoot us an email at
                <strong>&#115;&#117;&#112;&#112;&#111;&#114;&#116;&#64;&#109;&#105;&#110;&#110;&#111;&#46;&#99;&#111;</strong>,
                and we'll be happy to help!
        </div>
        <div id="examplesColumn">
            <div class="exampleCont">
                <div class="exampleTitle">
                    Example 1: File Emailer
                </div>
                <div class="exampleBody">
                    <p>
                        This example lets you send a file to a specified email
                        address.
                    </p>
                    <p>
                        Simply choose a file to send and enter the destination
                        email. The file will then be sent as an attachment to
                        that address. (Max file size is 1MB.)
                    </p>
                    <p>
                        First select your file and enter an email.
                        Then pay $0.25 using Minno &mdash; if you're new,
                        you can sign up by clicking the Minno button.
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
                        Please click the Minno button to pay $0.25 and send
                        your file.
                    </div>
                    <div id="buttonCont">

                        <!-- Begin Add Minno Button -->
                        <div id="buyButton">
                            <div class="minno-button" id="xxx"></div>
                        </div>
                        <!-- End Add Minno Button -->

                        <div style="clear: both;"></div>
                    </div>
		</div>
            </div>
        </div>
        <script type="text/javascript">
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
         * Defining the minnoPreCallback() function
         *
         * The callback that is executed when a user clicks the Minno button,
         * but before the purchase is actually made. This function is optional
         * -- if it exists, it should serve as a last-minute check to ensure
         * that all user input required for the purchase has been obtained.
         *
         * If all the necessary user input has been collected, the function
         * should return true; if not, it should alert the user what remaining
         * input is needed, and return false. The Minno button will only make
         * the purchase if true is returned.
         *
         * This particular implementation ensures that the user has selected
         * a file and recipient prior to completing the purchase. The arguments
         * are ignored this time.
         */
        function minnoPreCallback(userId, invitemId) {
            if ("" == $("#fileInput").attr("value").trim()) {
                alert("Please choose a file to send.");
                return false;
            }            
            if ("" == $("#emailInput").attr("value").trim()) {
                alert("Please enter a recipient email address.");
                return false;
            }
            return true;
        }

        /********************************************************************
         * Defining the minnoPostCallback() function
         *
         * The callback that the Minno button executes when a user completes a
         * purchase. This particular implementation sends the file to the
         * specified email address.
         *
         * To see an example of the server-side code that interacts with this
         * function, check out the upload.php file in this directory.
         */
        function minnoPostCallback(userId, invitemId) {
            // Fill in the values of the hidden input elements so that the user
            // ID and the invitem ID are posted to the server. This allows the
            // the server to verify that the purchase completed successfully.
            $("#userIdInput").attr("value", userId);
            $("#invitemIdInput").attr("value", invitemId);

            // Fade in the wait div while file is uploading and emailing
            $("#fileForm").fadeTo(250, 0, function() {
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
                        $("#outcomeDiv").html(statusText);
                        $("#outcomeDiv").fadeIn();
                    });
                }
            });
      
        }
        </script>
    </body>
</html>
