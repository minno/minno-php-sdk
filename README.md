Minno PHP SDK
================

This is the basic PHP SDK for server-side integration with Minno.

For more information, visit our [API Documentation page](https://www.minno.co/docs).

Usage
-----

The [examples][examples] are a good place to start. To verify a purchase, simply
include the following lines:

    <?php

    require './minno.php';

    // Make sure to set these POST variables in your minnoCallback() function
    $user_id = $_POST["userId"];
    $invitem_id = $_POST["invitemId"];
    $verif_token = $_POST["verifToken];

    Minno::assert_valid_purchase($user_id, $invitem_id, $verif_token);

The `assert_valid_purchase()` function dies if the purchase is invalid. For more
complex behavior, use the `is_valid_purchase()`, which returns a boolean.

You receive the `userId`, `invitemId`, and `verifToken` request parameters from
the client-side `minnoCallback()` function that gets executed after a user
purchases an item. You can then POST these parameters to your server endpoint
for verification before giving the user access to your premium item.

[examples]: http://github.com/minno/minno-php-sdk/tree/master/examples

Feedback
--------

If you have any questions or comments about this package, feel free to email us
at support@minno.co! We'd love to hear your thoughts!
