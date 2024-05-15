<?php

use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\SearchArrangorsystemet\SearchContentIndex;
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
// Hvis kontekst er 1 (min side) kan man søke flere områder
if($searchContext == 1) {
    $retBlogs = sokBlogs($searchInput);
}


$handleCall->sendToClient([
    'results' => $retObjs,
    'blogs' => $retBlogs
]);



// Brukere kan søke på arrangementer, innslag, blogginnlegg, og brukere
function sokBlogs(String $searchInput) {
	$blogs = get_blogs_of_user(get_current_user_id());

    $retBlogs = [];
    // Search for blogname
    foreach ($blogs as $blog) {
        if(_searchBlog($blog, $searchInput)) {
            $blog_id = $blog->userblog_id; // Get the blog ID
            switch_to_blog($blog_id); // Switch to the context of the current blog
            $retBlogs[] = [
                'site_id' => $blog->site_id,
                'userblog_id' => $blog->userblog_id,
                'title' => $blog->blogname,
                'siteUrl' => $blog->siteurl,
                'site_type' => get_option('site_type'),
            ];
            restore_current_blog();
        }
    }

    return $retBlogs;
       // object(stdClass)#956 (10) {
    //     ["userblog_id"]=>
    //     int(1)
    //     ["blogname"]=>
    //     string(14) "UKM for ungdom"
    //     ["domain"]=>
    //     string(7) "ukm.dev"
    //     ["path"]=>
    //     string(1) "/"
    //     ["site_id"]=>
    //     int(1)
    //     ["siteurl"]=>
    //     string(14) "http://ukm.dev"
    //     ["archived"]=>
    //     string(1) "0"
    //     ["mature"]=>
    //     string(1) "0"
    //     ["spam"]=>
    //     string(1) "0"
    //     ["deleted"]=>
    //     string(1) "0"
    // }
}

function _searchBlog($blog, $searchInput) : bool {
    $searchThreshold = 3;
    $distance = levenshtein($searchInput, $blog->blogname);

    return $distance < $searchThreshold;
}

function sokDeltakere() {

}

