<?php
/**
 *
 * Copyright 2011 Minno, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

if (!function_exists('curl_init')) {
  throw new Exception('Minno needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Minno needs the JSON PHP extension.');
}

/**
 * Provides easy access to Minno developer features, e.g.  purchase
 * verification.
 *
 * @author Noah Ready-Campbell <noah@minno.co>
 */
class Minno
{
    /**
     * Check to see whether the user had made a valid purchase of the invitem.
     *
     * If the invitem is single-use, and the user has already used it once,
     * this method will return false.
     *
     * @param String $user_id the ID of the user who made the putative purchase
     * @param String $invitem_id the ID of the invitem being purchased
     * @param String $verif_token the auth token allowing partners to verify
     *     purchases
     *
     * @return Boolean whether the purchase is valid
     */
    public static function is_valid_purchase($user_id, $invitem_id, $verif_token)
    {
        // Ping the minno.co endpoint to see if this purchase is valid
        $check_url = "https://www.minno.co/p/"
            . $user_id . "/"
            . $invitem_id
            . "?verifToken=" . $verif_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $check_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);

        // Decode the JSON that was returned
        $data = json_decode($content, true);
        return $data["isValid"];
    }

    /**
     * Assert that the specified purchase is valid; die() if not.
     *
     * @param String $user_id the ID of the user who made the putative purchase
     * @param String $invitem_id the ID of the invitem being purchased
     * @param String $verif_token the auth token allowing partners to verify
     *     purchases
     */
    public static function assert_valid_purchase($user_id, $invitem_id, $verif_token)
    {
        if (!Minno::is_valid_purchase($user_id, $invitem_id, $verif_token)) {
            die();
        }
    }
}
?>
