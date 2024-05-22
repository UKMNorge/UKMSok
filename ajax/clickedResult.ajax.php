<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\Search;


require_once('UKM/Autoloader.php');

$handleCall = new HandleAPICall(['logId'], ['resultId', 'text'], ['GET', 'POST'], false);

$logId = $handleCall->getArgument('logId');

$resultId = $handleCall->getOptionalArgument('resultId');
$text = $handleCall->getOptionalArgument('text');

Search::clickedResult($logId, $resultId, $text);

$handleCall->sendToClient([]);