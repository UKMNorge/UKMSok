<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
use UKMNorge\SearchArrangorsystemet\Write;
use UKMNorge\SearchArrangorsystemet\Keyword;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['title', 'description', 'site_url', 'context_id'], [], ['GET', 'POST'], false);

$title = $handleCall->getArgument('title');
$description = $handleCall->getArgument('description');
$siteUrl = $handleCall->getArgument('site_url');
$contextId = $handleCall->getArgument('context_id');

$contentIndex = Write::createContentIndex($title, $siteUrl, $description, $contextId);

$contentIndexObj = [
    'id' => $contentIndex->getId(),
    'title' => $contentIndex->getTitle(),
    'siteUrl' => $contentIndex->getSiteUrl(),
    // 'contentType' => $contentIndex->getContentType(),
    'contextId' => $contentIndex->getContextId(),
    'description' => $contentIndex->getDescription(),
    'keywords' => [],
];

$handleCall->sendToClient([
    'results' => $contentIndexObj
]);