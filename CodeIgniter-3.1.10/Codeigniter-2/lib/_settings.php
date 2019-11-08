<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * All recommended user configurable options are available below.
 */

namespace Dwolla;

// Instead of manually setting multiple variables in the main constructor, we just use one big settings class.

class Settings {

    public $client_id = "812-191-9465";
    public $client_secret = "OM125q3HGMYN9GCXLmqHXjCQp7EeP1GYhuSZajC4AfCnhJhqkg";
    public $pin = 1234;

    public $oauth_scope = "Send|Transactions|Balance|Request|Contacts|AccountInfoFull|Funding|ManageAccount|Scheduled";
    public $oauth_token = "pfAcasiuJ6hBR4ZJYPB3Kq98Nd8irzxFpaBDRJ8v0KNWqR1cCs";
    public $refresh_token = "rKn4IGsrR5TNlrNIArWBLKwuM4pW4GrayhKOiTctzpE00wnggq";

    // Hostnames, endpoints
    public $production_host = 'https://www.dwolla.com/';
    public $sandbox_host = 'https://uat.dwolla.com/';
    public $default_postfix = 'oauth/rest';

    // Client behavior
    public $sandbox = true;
    public $debug = false;
    public $browserMessages = false;
    public $logfilePath = '';
    public $rest_timeout = 15;
    public $proxy = false;

   /**
     * PHP "magic" getter.
     *
     * @param $name
     * @return $value
     */
    public function __get($name) {
        return $this->$name;
    }

   /**
     * PHP "magic" setter.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

}
