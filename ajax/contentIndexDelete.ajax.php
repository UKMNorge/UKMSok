<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\ContentIndex;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
use UKMNorge\SearchArrangorsystemet\Write;
use UKMNorge\SearchArrangorsystemet\Keyword;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['id', 'title', 'description', 'site_url', 'context_id'], [], ['GET', 'POST'], false);

$id = $handleCall->getArgument('id');
$title = $handleCall->getArgument('title');
$description = $handleCall->getArgument('description');
$siteUrl = $handleCall->getArgument('site_url');
$contextId = $handleCall->getArgument('context_id');

$res = Write::deleteContentIndex(new ContentIndex($id, $siteUrl, $title, $description, null, $contextId));

$handleCall->sendToClient([
    'results' => $res
]);