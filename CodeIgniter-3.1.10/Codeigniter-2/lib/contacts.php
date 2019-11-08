<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * This class contains methods for all exposed contact related endpoints.
 *
 * get(): Retrieve a user's contacts.
 * nearby(): Get spots near a location.
 */

namespace Dwolla;

require_once('client.php');

class Contacts extends RestClient {

    /**
     * Get contacts from user associated with OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] Contacts.
     */
    public function get($params = false, $alternate_token = false) {
        $p = [
            'oauth_token' => $alternate_token
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/contacts', $p);
    }

    /**
     * Returns Dwolla spots near the specified geographical location.
     *
     * @param double $lat Latitudinal coordinates.
     * @param double $lon Longitudinal coordinates.
     * @param string[] $params Additional parameters.
     *
     * @return string[] Returned spots.
     */
    public function nearby($lat, $lon, $params = false) {
        if (!$lat) { return self::_error("nearby() requires `\$lat` parameter.\n"); }
        if (!$lon) { return self::_error("nearby() requires `\$lon` parameter.\n"); }

        $p = [
            'client_id' => self::$settings->client_id,
            'client_secret' => self::$settings->client_secret,
            'latitude' => $lat,
            'longitude' => $lon
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/contacts/nearby', $p);
    }
}