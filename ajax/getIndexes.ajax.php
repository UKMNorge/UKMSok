<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\ContentIndex;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);



$retObjs = [];

$results = ContentIndex::getAllWithKeywords();


foreach($results as $contentIndex) {
    $retObjs[] = [
        'id' => $contentIndex->getId(),
        'title' => $contentIndex->getTitle(),
        'siteUrl' => $contentIndex->getSiteUrl(),
        'description' => $contentIndex->getDescription(),
        'keywords' => [],
    ];

    foreach($contentIndex->getKeywords() as $keyword) {
        $retObjs[count($retObjs) - 1]['keywords'][] = [
            'id' => $keyword->getId(),
            'name' => $keyword->getName(),
            'weight' => '1'
        ];
    }
}


$handleCall->sendToClient([
    'results' => $retObjs
]);