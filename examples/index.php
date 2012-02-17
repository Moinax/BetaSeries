<?php
include __DIR__ . '/settings.php';
include __DIR__.'/../src/BetaSeries/Client.php';

use BetaSeries\Client;

$bs = new Client(BETASERIES_URL, BETASERIES_API_KEY, Client::XML, Client::LANGUAGE_VF);

$shows = simplexml_load_string($bs->searchShows('Alcatraz'));

$url = $shows->shows->show->url;

$subtitles = simplexml_load_string($bs->showSubtitles($url));

var_dump($subtitles->subtitles->subtitle);

/*$shows = json_decode($bs->searchShows('Alcatraz'), true);

$url = $shows['root']['shows'][0]['url'];

$subtitles = $bs->showSubtitles($url);

var_dump(json_decode($subtitles, true));*/