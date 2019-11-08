<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * This class contains methods for obtaining and refreshing OAuth tokens.
 *
 * genAuthUrl(): Generates OAuth permission link URL
 * get(): Retrieves OAuth + Refresh token pair from Dwolla servers.
 * refresh(): Retrieves OAuth + Refresh pair with refresh token.
 */

namespace Dwolla;

require_once('client.php');

class OAuth extends RestClient {

    /**
     * Returns an OAuth permissions page URL. If no redirect is set,
     * the redirect in the Dwolla Application Settings will be used.
     * If no scope is set, the scope in the settings object will be used.
     *
     * @param string $redirect Redirect destination.
     * @param string $scope Optional. Scope string to override default scope in settings object.
     * @param bool $verified_account Optional verified account flag.
     *
     * @return string
     */
    public function genAuthUrl($redirect = false, $scope = false, $verified_account = false) {
        if (!$scope) { $scope = self::$settings->oauth_scope; }

        return self::_host()
        . 'oauth/v2/authenticate?client_id='
        . urlencode(self::$settings->client_id)
        . "&response_type=code&scope="
        . urlencode($scope)
        . ($redirect ? "&redirect_uri=" . urlencode($redirect) : "")
        . ($verified_account ? "&verified_account=true" : "");
    }

    /**
     * Returns an OAuth token + refresh pair in an array. If no redirect
     * is set, the redirect in the Dwolla Application Settings will be used.
     *
     * @param string $code Code from redirect response.
     * @param string $redirect Redirect destination.
     *
     * @return string[] Access and refresh token pair.
     */
    public function get($code, $redirect = false) {
        if (!$code) { return self::_error("get() requires `\$code` parameter.\n"); }

        $params = [
            'client_id' => self::$settings->client_id,
            'client_secret' => self::$settings->client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code
        ];

        if ($redirect) { $params['redirect_uri'] = $redirect; }
        return self::_post('token', $params, 'oauth/v2/', false);
    }

    /**
     * Returns a newly refreshed access token and refresh token pair.
     *
     * @param string $refreshToken Refresh token from initial OAuth handshake.
     *
     * @return string[] Access and refresh token pair.
     */
    public function refresh($refreshToken) {
        if (!$refreshToken) { return self::_error("refresh() requires `\$refreshToken` parameter.\n"); }

        $params = [
            'client_id' => self::$settings->client_id,
            'client_secret' => self::$settings->client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        return self::_post('/token', $params, 'oauth/v2', false);
    }

    /**
     * Returns a "catalog" of endpoints that are available for use
     * with the current/passed OAuth token
     * 
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     * 
     * @return string[] Catalog of endpoints and their URIs
     */
    public function catalog($alternate_token = false) {
        return self::_get('/catalog',
            [
                'oauth_token' => $alternate_token
            ], false, false)['_links'];
    }
}
