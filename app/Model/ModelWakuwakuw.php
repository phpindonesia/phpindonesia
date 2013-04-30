<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Parameter;

/**
 * ModelWakuwakuw
 *
 * @author PHP Indonesia Dev
 */
class ModelWakuwakuw extends ModelBase 
{
    const ACCESS_TOKEN_URL = 'https://www.wakuwakuw.com/oauth/access_token';
    const AUTHORIZE_URL = 'https://www.wakuwakuw.com/oauth/authorize';
    const API_URL = 'https://www.wakuwakuw.com/graph/';
    const API_USER_IMG = 'https://www.wakuwakuw.com/img/user/';
    protected $clientId = '713C11CDEF33B';
    protected $clientSecret = '8913DC472145B285FD38DB631541E';
    protected $scope = 'community,meetup,event';
    protected $accessToken = '';
    protected $redirectUrl = '';

    /**
     * Constructor
     */
    public function __construct(Parameter $params) {
        $this->accessToken = $params->get('wakuwakuwToken');
        $this->redirectUrl = $params->get('redirectUrl');
    }

    /**
     * Get all valid meetups
     */
    public function getMeetups() {
        $response = $this->getData(self::API_URL.'event', array('community' => 'developer'));

        if ($response->get('result') == false || strpos($response->get('body'),'data')===false) {
            return false;
        }

        // Parsing the body
        if (($events = json_decode($response->get('body'),true)) && empty($events)) {
            return false;
        }

        // Proses event data
        $events_data = array();
        $init = false;
        $pending_count = $confirmed_count = 0;

        foreach ($events['data'] as $event) {
            // Tanggal
            $start = $event['time_start'];
            $end = $event['time_end'];
            $time = date('d M, H:i', $start).' - '.date('H:i Y', $end);
            $confirmed = '';
            $pending = '';

            foreach ($event['guests'] as $guest) {
                if ($guest['is_approved']) {
                    $confirmed .= '<img src="'.self::API_USER_IMG.$guest['user'].'?size=small"/>';
                    $confirmed_count++;
                } else {
                    $pending .= '<img src="'.self::API_USER_IMG.$guest['user'].'?size=small"/>';
                    $pending_count++;
                }
            }

            $events_data[] = array(
                'state' => $init ? '' : 'active',
                'time' => $time,
                'place' => $event['location'],
                'lat' => $event['lat'],
                'lng' => $event['lng'],
                'eid' => $event['id'],
                'title' => $event['title'],
                'description' => $event['description'],
                'confirmed' => $confirmed,
                'pending' => $pending,
                'confirmed_count' => $confirmed_count,
                'pending_count' => $pending_count,
            );

            if (!$init) $init = true;
        }
        
        return $events_data;
    }

    /**
     * Get user data
     */
    public function getUser() {
        if (empty($this->accessToken)) return false;

        $response = $this->getData(self::API_URL.'user', array('access_token' => $this->accessToken));

        if ($response->get('result') == false || strpos($response->get('body'),'login')===false) {
            return false;
        }

        // Parsing the body
        if (($user = json_decode($response->get('body'))) && empty($user)) {
            return false;
        }

        $user = (array) $user;
        $userData = new Parameter($user);
        $userData->set('username', $userData->get('login'));

        return $userData;
    }

    /**
     * RSVP
     *
     * @param int
     */
    public function rsvp($event) {
        if (empty($this->accessToken)) return false;

        $response = $this->getData(self::API_URL.'event', array(
            'access_token' => $this->accessToken,
            'rsvp' => $event,
        ));

        if ($response->get('result') == false || strpos($response->get('body'),'data')===false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Retrieve access parameter from code
     * @param string Code
     */
    public function getAccessToken($code) {
        $params = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUrl,
            'grant_type' => 'authorization_code',
            'code' => $code,
        );

        $response = $this->postData(self::ACCESS_TOKEN_URL,$params);

        if (strpos($response->get('body'), 'access_token') !== false) {
            // Get access token and token type
            list($accessTokenQuery,$tokenTypeQuery) = explode('&', $response->get('body'));

            return str_replace('access_token=', '', $accessTokenQuery);
        } else {
            return false;
        }
    }

    /**
     * Build login url
     */
    public function getLoginUrl() {
        $params = array(
            'client_id' => $this->clientId,
            'scope' => $this->scope,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
        );

        return self::AUTHORIZE_URL.'?'.http_build_query($params);
    }
   
}