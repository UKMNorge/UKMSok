<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
use UKMNorge\SearchArrangorsystemet\Search;
use UKMNorge\Arrangement\Arrangement;


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
// Hvis kontekst er 2 (arrangement) kan man søke deltakere og innslag i arrangementet
elseif($searchContext == 2) {
    $arrangement = new Arrangement(get_option('pl_id'));
    $retOmrader = array_merge($retOmrader, Search::sokDeltakere($arrangement, $searchInput));
    $retOmrader = array_merge($retOmrader, Search::sokInnslag($arrangement, $searchInput));
}


$handleCall->sendToClient([
    'results' => $retObjs,
    'blogs' => $retBlogs,
    'omrader' => $retOmrader,
]);