<?php
require __DIR__ . '/bootstrap.php';

use Moinax\BetaSeries\Client;

$bs = new Client(BETASERIES_URL, BETASERIES_API_KEY, Client::JSON, Client::LANGUAGE_VF);

$shows = json_decode($bs->search('Alcatraz'), true);

$url = $shows['root']['shows'][0]['url'];

$episodes = $bs->getEpisodes($url);

print_r(json_decode($episodes, true));