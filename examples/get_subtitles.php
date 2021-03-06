<?php
require __DIR__ . '/bootstrap.php';

use Moinax\BetaSeries\Client;

$bs = new Client(BETASERIES_URL, BETASERIES_API_KEY, Client::XML, Client::LANGUAGE_VF);

$shows = simplexml_load_string($bs->search('Alcatraz'));

$url = $shows->shows->show->url;

$subtitles = simplexml_load_string($bs->getSubtitles($url));

print_r($subtitles->subtitles->subtitle);
