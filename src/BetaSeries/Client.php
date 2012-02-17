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
     * Search shows by title
     *
     * @link http://api.betaseries.com/shows/search.xml?title=<search>
     * @param string $title
     * @return string
     */
    public function searchShows($title)
    {
        return $this->fetch('/shows/search', array('title' => $title));
    }

    /**
     * Get subtitles for a show
     *
     * @link http://api.betaseries.com/subtitles/show/<url>.xml<?language=(VO|VF)><&season=N><&episode=N>
     * @param string $url
     * @return string
     */
    public function showSubtitles($url, $season = null, $episode = null)
    {
        return $this->fetch('/subtitles/show/' . $url, array('season' => $season, 'episode' => $episode, 'language' => $this->language));
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