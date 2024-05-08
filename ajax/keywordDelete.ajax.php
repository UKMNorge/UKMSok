<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
use UKMNorge\SearchArrangorsystemet\Write;
use UKMNorge\SearchArrangorsystemet\Keyword;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['content_index_id', 'keyword_id'], [], ['GET', 'POST'], false);

$contentIndexId = $handleCall->getArgument('content_index_id');
$keywordId = $handleCall->getArgument('keyword_id');

$res = Write::deleteKeyword($contentIndexId, $keywordId);

$handleCall->sendToClient([
    'results' => $res
]);