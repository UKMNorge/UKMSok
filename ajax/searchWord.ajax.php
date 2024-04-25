<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['searchInput'], [], ['GET', 'POST'], false);


$searchInput = $handleCall->getArgument('searchInput');


$retObjs = [];

$results = SearchContentIndex::search($searchInput);


foreach($results as $result) {
    $retObjs[] = [
        'id' => $result->getId(),
        'title' => $result->getTitle(),
        'siteUrl' => $result->getSiteUrl(),
        'description' => $result->getDescription(),
    ];
}


$handleCall->sendToClient([
    'results' => $retObjs
]);