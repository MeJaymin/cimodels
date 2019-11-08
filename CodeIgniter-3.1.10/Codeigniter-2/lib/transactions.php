<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * This class contains methods for all exposed request related endpoints.
 *
 * send(): Sends money
 * refund(): Refunds money
 * get(): Lists transactions for user
 * info(): Get information for transaction by ID.
 * stats(): Get transaction statistics for current user.
 */

namespace Dwolla;

require_once('client.php');

class Transactions extends RestClient {

    /**
     * Sends money to the specified destination user.
     *
     * @param string $destinationId Dwolla ID to send funds to.
     * @param double $amount Amount to send.
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return int Transaction ID of sent funds.
     */
    public function send($destinationId, $amount, $params = false, $alternate_token = false, $alternate_pin = false) {
        if (!$destinationId) { return self::_error("send() requires `\$destinationId` parameter.\n"); }
        if (!$amount) { return self::_error("send() requires `\$amount` parameter.\n"); }

        $p = [
            'oauth_token' => $alternate_token,
            'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
            'destinationId' => $destinationId,
            'amount' => $amount
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/transactions/send', $p);
    }

    /**
     * Lists transactions for the user associated with
     * the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] List of transactions,
     */
    public function get($params = false, $alternate_token = false) {
        $p = [
            'oauth_token' => $alternate_token,
            'client_id' => self::$settings->client_id,
            'client_secret' => self::$settings->client_secret
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/transactions', $p);
    }

    /**
     * Returns transaction information for the transaction
     * associated with the passed transaction ID.
     *
     * @param string $id Transaction ID.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] Information about transaction.
     */
    public function info($id, $alternate_token = false) {
        if (!$id) { return self::_error("info() requires `\$id` parameter.\n"); }

        return self::_get('/transactions/' . $id,
            [
                'oauth_token' => $alternate_token,
                'client_id' => self::$settings->client_id,
                'client_secret' => self::$settings->client_secret
            ]);
    }

    /**
     * Refunds (either completely or partially) funds to
     * the sending user for a transaction.
     *
     * @param string $id Transaction ID.
     * @param string $fundingSource Funding source for refund transaction.
     * @param double $amount Amount to refund.
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] Information about refund transaction.
     */
    public function refund($id, $fundingSource, $amount, $params = false, $alternate_token = false, $alternate_pin = false) {
        if (!$id) { return self::_error("refund() requires `\$id` parameter.\n"); }
        if (!$fundingSource) { return self::_error("refund() requires `\$fundingSource` parameter.\n"); }
        if (!$amount) { return self::_error("refund() requires `\$amount` parameter.\n"); }

        $p = [
            'oauth_token' => $alternate_token,
            'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
	        'fundsSource' => $fundingSource,
            'transactionId' => $id,
            'amount' => $amount
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/transactions/refund', $p);
    }

    /**
     * Retrieves transaction statistics for
     * the user associated with the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] Transaction statistics.
     */
    public function stats($params = false, $alternate_token = false) {
        $p = [
            'oauth_token' => $alternate_token
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/transactions/stats', $p);
    }

    /**
     * Schedules a transaction on behalf of the currently assigned/passed
     * OAuth token
     * 
     * @param string $destinationId Dwolla ID to send funds to.
     * @param double $amount Amount to send.
     * @param string ('YYYY-MM-DD') $scheduleDate Date to send on.
     * @param string $fundsSource Funding Source ID to fund scheduled transaction.
     * @param string[] $params Additional parameters
     * @param string $alternate_token Alternate OAuth token 
     * 
     * @return mixed[] Information about scheduled transaction
     */
    public function schedule($destinationId, $amount, $scheduleDate, $fundsSource, $params = false, $alternate_token = false, $alternate_pin = false) {
        if (!$destinationId) { return self::_error("schedule() requires `\$destinationId` parameter.\n"); }
        if (!$amount) { return self::_error("schedule() requires `\$amount` parameter.\n"); }
        if (!$scheduleDate) { return self::_error("schedule() requires `\$scheduleDate` parameter.\n"); }
        if (!$fundsSource) { return self::_error("schedule() requires `\$fundsSource` parameter.\n"); }


        $p = [
            'oauth_token' => $alternate_token,
            'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
            'destinationId' => $destinationId,
            'amount' => $amount,
            'scheduleDate' => $scheduleDate,
            'fundsSource' => $fundsSource
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/transactions/scheduled', $p);
    }

    /**
     * Lists scheduled transactions for the user associated with
     * the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return mixed[] List of scheduled transactions
     */
    public function scheduled($params = false, $alternate_token = false) {
        $p = [
            'oauth_token' => $alternate_token,
            'client_id' => self::$settings->client_id,
            'client_secret' => self::$settings->client_secret
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/transactions/scheduled', $p);
    }

    /**
     * Retrieve a scheduled transaction by ID for the user associated with
     * the current OAuth token.
     *
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return mixed[] Requested scheduled transaction
     */
    public function scheduledById($id, $alternate_token = false) {
        if (!$id) { return self::_error("scheduled() requires `\$id` parameter.\n"); }

        return self::_get('/transactions/scheduled/' . $id, 
            [
                'oauth_token' => $alternate_token
            ]);
    }

    /**
     * Edit a scheduled transaction by ID
     * for the user associated with the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return mixed[] Edited scheduled transaction
     */
    public function editScheduled($id, $params = false, $alternate_token = false, $alternate_pin = false) {
        if (!$id) { return self::_error("editScheduled() requires `\$id` parameter.\n"); }

        $p = [
            'oauth_token' => $alternate_token,
            'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_put('/transactions/scheduled/' . $id, $p);
    }

    /**
     * Delete a scheduled transaction by ID
     * for the user associated with the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return mixed[] Edited scheduled transaction
     */
    public function deleteScheduledById($id, $alternate_token = false, $alternate_pin = false) {
        if (!$id) { return self::_error("schedule() requires `\$id` parameter.\n"); }

        return self::_delete('/transactions/scheduled/' . $id, 
            [
                'oauth_token' => $alternate_token,
                'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin
            ]);
    }

    /**
     * Delete all scheduled transactions
     * for the user associated with the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return mixed[] Edited scheduled transaction
     */
    public function deleteAllScheduled($alternate_token = false, $alternate_pin = false) {

        return self::_delete('/transactions/scheduled', 
            [
                'oauth_token' => $alternate_token,
                'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin
            ]);
    }

}
