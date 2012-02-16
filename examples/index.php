<?php
include __DIR__ . '/settings.php';
include __DIR__.'../src/BetaSeries/Client.php';

use BetaSeries\Client;

$tvdb = new Client(BETASERIES_URL, BETASERIES_API_KEY);