<?php

namespace BetaSeries;

/**
 * Base BetaSeries library class, provides universal functions and variables
 *
 * @package BetaSeries
 * @author Jérôme Poskin <moinax@gmail.com>
 **/
class Client
{
    const JSON = 'json';
    const XML = 'xml';

    const POST = 'post';
    const GET = 'get';

    const LANGUAGE_VO = 'VO';
    const LANGUAGE_VF = 'VF';

    /**
     * Base url for betaseries.com
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * API key for betaseries.com
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The default return format
     *
     * @var Client::JSON or Client::XML
     */
    protected $format;

    /**
     * The default language
     *
     * @var string
     */
    protected $language;

    /**
     * Token used when user connection is needed
     *
     * @var string
     */
    protected $token;

    /**
     * Constructor
     *
     * @param string $baseUrl Url of the Api
     * @param string $apikey Key of the Api
     * @param string $format Response format
     * @param string $language Response language
     */
    public function __construct($baseUrl, $apikey, $format = Client::JSON, $language = Client::LANGUAGE_VO)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apikey;
        $this->format = $format;
        $this->language = $language;
    }

    /**
     * Get the status of the api
     *
     * @link http://api.betaseries.com/status.xml
     * @return string
     */
    public function getStatus() {
        return $this->fetch('status');
    }
    /**
     * Search serie by title
     *
     * @link http://api.betaseries.com/shows/search.xml?title=<search>
     * @param string $title
     * @return string
     */
    public function search($title)
    {
        return $this->fetch('/shows/search', array('title' => $title));
    }

    /**
     * Get all series at once
     * @link http://api.betaseries.com/shows/display/all.xml
     * @return string
     */
    public function getSeries()
    {
        return $this->fetch('/shows/display/all');
    }

    /**
     * Get a serie by is slug
     *
     * @link http://api.betaseries.com/shows/display/<url>.xml
     * @param string $slug
     * @return string
     */
    public function getSerie($slug)
    {
        return $this->fetch('/shows/display/' .$slug);
    }

    /**
     * Get all episode for a serie
     *
     * @link http://api.betaseries.com/shows/episodes/<url>.xml<?season=N><&episode=N><&summary=1>
     * @param string $slug
     * @return string
     */
    public function getEpisodes($slug)
    {
        return $this->fetch('/shows/episodes/' . $slug);
    }

    /**
     * Get all episode for a serie and a specific season
     *
     * @link http://api.betaseries.com/shows/episodes/<url>.xml<?season=N><&episode=N><&summary=1>
     * @param string $slug
     * @param int $season
     * @return string
     */
    public function getEpisodesBySeason($slug, $season)
    {
        return $this->fetch('/shows/episodes/' . $slug, array('season' => $season));
    }

    /**
     * Get a specific episode for serie by season and number
     *
     * @link http://api.betaseries.com/shows/episodes/<url>.xml<?season=N><&episode=N><&summary=1>
     * @param string $slug
     * @param int $season
     * @param int $number
     * @return string
     */
    public function getEpisode($slug, $season, $number)
    {
        return $this->fetch('/shows/episodes/' . $slug, array('season' => $season, 'episode' => $number));
    }

    /**
     * Set a token when we need to work with a specific user
     *
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * Get a token for a particular user
     *
     * @link http://api.betaseries.com/members/auth.xml?login=<login>&password=<md5>
     * @param string $login
     * @param string $password
     * @return string
     */
    public function getToken($login, $password)
    {
        $this->token = $this->fetch('/members/auth', array('login' => $login, 'password' => md5($password)));
    }

    /**
     * Check if the token is still valid
     *
     * @link http://api.betaseries.com/members/is_active.xml
     * @param string $token
     * @return string
     */
    public function isValid($token)
    {
        return $this->fetch('/members/is_active', array('token' => $token));
    }

    /**
     * Destroy a specific token
     *
     * @link http://api.betaseries.com/members/destroy.xml
     * @param string $token
     * @return string
     */
    public function destroy($token) {
        return $this->fetch('/members/destroy', array('token' => $token));
    }

    /**
     * Add a serie to the user favorites
     *
     * @link http://api.betaseries.com/shows/add/<url>.xml
     * @param string $slug
     * @return string
     */
    public function addFavorite($slug)
    {
        return $this->fetch('/shows/add/' . $slug, array('token' => $this->token));
    }

    /**
     * Remove a serie from the user favorites
     *
     * @link http://api.betaseries.com/shows/remove/<url>.xml
     * @param string $slug
     * @return string
     */
    public function removeFavorite($slug)
    {
        return $this->fetch('/shows/remove/' . $slug, array('token' => $this->token));
    }

    /**
     * Set an episode as watched
     *
     * @link http://api.betaseries.com/members/watched/<url>.xml?season=<N>&episode=<N><&note=N>
     * @param string $slug
     * @param int $season
     * @param int $number
     * @return string
     */
    public function watched($slug, $season, $number)
    {
        return $this->fetch('/members/watched/' . $slug, array('season' => $season, 'episode' => $number, 'token' => $this->token));
    }

    /**
     * Get subtitles for a serie
     *
     * @link http://api.betaseries.com/subtitles/show/<url>.xml<?language=(VO|VF)><&season=N><&episode=N>
     * @param string $slug
     * @return string
     */
    public function getSubtitles($slug, $season = null, $episode = null)
    {
        return $this->fetch('/subtitles/show/' . $slug, array('season' => $season, 'episode' => $episode, 'language' => $this->language));
    }

    /**
     * Get the url of a picture for a serie or an episode
     *
     * @param string $slug
     * @param int|null $season
     * @param int|null $number
     * @return string
     */
    public function getPictureUrl($slug, $season = null, $number = null)
    {
        if ($season == null || $number == null) {
            return $this->baseUrl  . '/pictures/show/' . $slug . '.jpg?key=' . $this->apiKey;
        }

        return $this->baseUrl  . '/pictures/episode/'. $slug .'.jpg?season=' . $season . '&episode=' . $number . '&key=' . $this->apiKey;
    }

    /**
     * Fetches data via curl and returns result
     *
     * @access protected
     * @param $url string The url to fetch data from
     * @return string The data
     **/
    protected function fetch($url, $params = array(), $method = Client::GET)
    {
        $params = array_merge($params, array('key' => $this->apiKey));

        $query = '';
        if ($method == Client::GET) {
            $query = '?' . http_build_query($params);
        }
        $url = $this->baseUrl . $url . '.' . $this->format . $query;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($method == Client::POST) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $data = substr($response, $headerSize);
        curl_close($ch);

        if ($httpCode != 200) {
            return false;
        }

        return $data;
    }

}