<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
use UKMNorge\SearchArrangorsystemet\Write;
use UKMNorge\SearchArrangorsystemet\Keyword;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['content_index_id', 'keyword_name', 'weight'], [], ['GET', 'POST'], false);

$contentIndexId = $handleCall->getArgument('content_index_id');
$keywordName = $handleCall->getArgument('keyword_name');
$weight = $handleCall->getArgument('weight');

$res = Write::createKeyword($contentIndexId, $weight, new Keyword(-1, $keywordName));

$handleCall->sendToClient([
    'results' => $res
]);