<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * This class contains methods for all exposed funding source related endpoints.
 *
 * info(): Retrieve information regarding a funding source via ID.
 * get(): List all funding sources.
 * add(): Add a funding source.
 * verify(): Verify a funding source.
 * withdraw(): Withdraw from Dwolla into funding source.
 * deposit(): Deposit to Dwolla from funding source.
 */

namespace Dwolla;

require_once('client.php');

class fundingSources extends RestClient {

    /**
     * Retrieves information about a funding source by ID.
     *
     * @param string[] $funding_id Funding ID of account to retrieve information for.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] Funding ID info.
     */
    public function info($funding_id, $alternate_token = false) {
        if (!$funding_id) { return self::_error("info() requires `\$funding_id` parameter.\n"); }

        return self::_get('/fundingsources/' . $funding_id,
            [
                'oauth_token' => $alternate_token
            ]);
    }

    /**
     * Returns a list of funding sources associated to the account
     * under the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] List of funding sources.
     */
    public function get($params = false, $alternate_token = false) {
        $p = [
            'oauth_token' => $alternate_token
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/fundingsources', $p);
    }

    /**
     * Adds a funding source to the account under the current
     * OAuth token.
     *
     * @param string $account Account number
     * @param string $routing Routing number
     * @param string $type Account type
     * @param string $name User defined name for account.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function add($account, $routing, $type, $name, $alternate_token = false) {
        if (!$account) { return self::_error("add() requires `\$account` parameter.\n"); }
        if (!$routing) { return self::_error("add() requires `\$routing` parameter.\n"); }
        if (!$type) { return self::_error("add() requires `\$type` parameter.\n"); }
        if (!$name) { return self::_error("add() requires `\$name` parameter.\n"); }

        return self::_post('/fundingsources/',
            [
                'oauth_token' => $alternate_token,
                'account_number' => $account,
                'routing_number' => $routing,
                'account_type' => $type,
                'name' => $name
            ]);
    }

    /**
     * Verifies a funding source for the account associated
     * with the funding ID under the current OAuth token via
     * the two micro-deposits.
     *
     * @param double $dep1 Micro-deposit 1
     * @param double $dep2 Micro-deposit 2
     * @param string $funding_id Funding ID.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function verify($dep1, $dep2, $funding_id, $alternate_token = false) {
        if (!$dep1) { return self::_error("verify() requires `\$dep1` parameter.\n"); }
        if (!$dep2) { return self::_error("verify() requires `\$dep2` parameter.\n"); }
        if (!$funding_id) { return self::_error("verify() requires `\$funding_id` parameter.\n"); }

        return self::_post('/fundingsources/' . $funding_id . '/verify',
            [
                'oauth_token' => $alternate_token,
                'deposit1' => $dep1,
                'deposit2' => $dep2
            ]);
    }

    /**
     * Withdraws funds from a Dwolla account to the funding source
     * associated with the passed ID, under the account associated
     * with the current OAuth token.
     *
     * @param double $amount Amount to withdraw.
     * @param string $funding_id Funding ID to withdraw to.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function withdraw($amount, $funding_id, $alternate_token = false, $alternate_pin = false) {
        if (!$amount) { return self::_error("withdraw() requires `\$amount` parameter.\n"); }
        if (!$funding_id) { return self::_error("withdraw() requires `\$funding_id` parameter.\n"); }

        return self::_post('/fundingsources/' . $funding_id . '/withdraw',
            [
                'oauth_token' => $alternate_token,
                'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
                'amount' => $amount
            ]);
    }

    /**
     * Deposits funds into the Dwolla account associated with the
     * OAuth token from the funding ID associated with the passed
     * ID.
     *
     * @param double $amount Amount to deposit.
     * @param string $funding_id Funding ID to deposit from.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function deposit($amount, $funding_id, $alternate_token = false, $alternate_pin = false) {
        if (!$amount) { return self::_error("deposit() requires `\$amount` parameter.\n"); }
        if (!$funding_id) { return self::_error("deposit() requires `\$funding_id` parameter.\n"); }

        return self::_post('/fundingsources/' . $funding_id . '/deposit',
            [
                'oauth_token' => $alternate_token,
                'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
                'amount' => $amount
            ]);
    }
}