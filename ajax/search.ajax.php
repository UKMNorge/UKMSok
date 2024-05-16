<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
use UKMNorge\SearchArrangorsystemet\Search;
global $current_user;

require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['searchInput', 'context'], [], ['GET', 'POST'], false);

$searchInput = $handleCall->getArgument('searchInput');
$searchContext = (int) $handleCall->getArgument('context');


$retObjs = [];

$results = SearchContentIndex::search($searchInput, $searchContext);


foreach($results as $result) {
    $retObjs[] = [
        'id' => $result->getId(),
        'title' => $result->getTitle(),
        'siteUrl' => $result->getSiteUrl(),
        'description' => $result->getDescription(),
    ];
}

$retBlogs = [];
$retOmrader = [];
// Hvis kontekst er 1 (min side) kan man søke flere områder
if($searchContext == 1) {
    $retBlogs = Search::sokBlogs($searchInput);
    $retOmrader = Search::searchOmraader($searchInput);
}


$handleCall->sendToClient([
    'results' => $retObjs,
    'blogs' => $retBlogs,
    'omrader' => $retOmrader,
]);