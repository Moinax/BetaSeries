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
     * @param string $baseUrl Domain name of the api without trailing slash
     * @param string $apiKey Api key provided by http://betaseries.com
     */
    public function __construct($baseUrl, $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }
}