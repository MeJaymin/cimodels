<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * This class contains methods for MassPay functionality.
 *
 * create(): Creates a MassPay job.
 * getJob(): Gets a MassPay job.
 * getJobItems(): Gets all items for a specific job.
 * getItem(): Gets an item from a specific job.
 * listJobs(): Lists all MassPay jobs.
 */

namespace Dwolla;

include_once('client.php');

class MassPay extends RestClient {

    /**
     * Creates a MassPay job. Must pass in an array of items.
     *
     * @param string $fundsSource Funding Source for job.
     * @param string[] $items Item array.
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function create($fundsSource, $items, $params = false, $alternate_token = false, $alternate_pin = false) {
        if (!$fundsSource) { return self::_error("create() requires `\$fundsSource` parameter.\n"); }
        if (!$items) { return self::_error("create() requires `\$items` parameter.\n"); }

        $p = [
            'oauth_token' => $alternate_token,
            'pin' => $alternate_pin ? $alternate_pin : self::$settings->pin,
            'fundsSource' => $fundsSource,
            'items' => $items
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/masspay/', $p);
    }

    /**
     * Checks the status of an existing MassPay job and
     * returns additional information.
     *
     * @param string[] $id MassPay job ID.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function getJob($id, $alternate_token = false) {
        if (!$id) { return self::_error("getJob() requires `\$id` parameter.\n"); }

        return self::_get('/masspay/' . $id,
            [
                'oauth_token' => $alternate_token
            ]);
    }

    /**
     * Gets all items from a created MassPay job.
     *
     * @param string $id MassPay job ID.
     * @param string[] $params Additional parameters.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function getJobItems($id, $params = false, $alternate_token = false) {
        if (!$id) { return self::_error("getJobItems() requires `\$id` parameter.\n"); }

        $p = [
            'oauth_token' => $alternate_token
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/masspay/' . $id . '/items', $p);
    }

    /**
     * Gets an item from a created MassPay job.
     *
     * @param string $job_id MassPay job ID.
     * @param string[] $item_id Item ID.
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return null
     */
    public function getItem($job_id, $item_id, $alternate_token = false) {
        if (!$job_id) { return self::_error("getItem() requires `\$job_id` parameter.\n"); }
        if (!$item_id) { return self::_error("getItem() requires `\$item_id` parameter.\n"); }

        return self::_get('/masspay/' . $job_id . '/items/' . $item_id,
            [
                'oauth_token' => $alternate_token
            ]);
    }

    /**
     * Lists all MassPay jobs for the user under
     * the current OAuth token.
     *
     * @param string $alternate_token OAuth token value to be used
     * instead of the current setting in the Settings class.
     *
     * @return string[] MassPay jobs.
     */
    public function listJobs($alternate_token = false) {
        return self::_get('/masspay/',
            [
                'oauth_token' => $alternate_token
            ]);
    }
}