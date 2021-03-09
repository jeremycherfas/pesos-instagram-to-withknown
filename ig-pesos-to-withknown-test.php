<?php

/*
* ig-pesos-to-known.php 
* version 0.1
* 2020-07-29 
*
* Offers a way to automatically post new photographs from YOUR InstaGram account
* to YOUR installation of WithKnown.
*
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(600);

// Get variables from config
// $config = include 'config.php';
// $newfile = $config->feedUrl;
// $endpoint = $config->endpoint;
// $username = $config->username;
// $action = $config->action;
// $known_api_key = $config->known_api_key;

// Stored value of previous item
$pubdate = strtotime(file_get_contents('pubdate.txt'));

# $knowntoken = base64_encode(hash_hmac('sha256', $action, $known_api_key, true));

$newfile = 'test-text.xml';
// Get the contents of the RSS feed from the test file
$newfilecontents = file_get_contents($newfile);

// Convert feed to array and discard elements not an item
$newfilecontents = makeArray($newfilecontents);

// Extract only items newer than $pubdate
$newitems = [];
foreach ($newfilecontents as $newfilecontent) {
$thepubdate = strtotime($newfilecontent['pubDate']);
if ($thepubdate > $pubdate) {
$newitems[] = $newfilecontent;
}
}

// No point doing anything if there is nothing to do
if (empty($newitems)) {
exit('No new items');
}

// Just in case
copy('pubdate.txt', 'oldpubdate.txt');

// Set the pubDate marker for next time
# file_put_contents('pubdate.txt', $newitems[0]['pubDate']);

$newitems = array_reverse($newitems);


// Extract the data and construct the cURL
foreach ($newitems as $newitem) {
$title = $newitem['title'];
$pubdate = $newitem['pubDate'];
$description = $newitem['description'];

// Now get the data out of $description, pending use of simpleXML
$caption = substr($description, 0, (strpos($description, '<img'))); // everything before the img tag
$caption = preg_replace('/<br\/>/', '', preg_replace('/<br\/><br\/>/', '</p><p>', $caption)); // replace <br> with <p> where appropriate

var_dump($caption);

// GOOD TO HERE 


$endurl = strpos($description, '"', strpos($description,'https'));
$photourl = substr($description, (strpos($description, 'src="')+5),($endurl-(strpos($description, 'src="')+5)));

//now use these to construct the cURL

# doCurl($title, $caption, $photourl, $endpoint, $username, $knowntoken);
}
die;
// Set the pubDate marker for next time
file_put_contents('pubdate.txt', $newitems[0]['pubDate']);

// Convert received XML string to array, pending use of simpleXML
function makeArray($filename) {
$xml=simplexml_load_string($filename,'SimpleXMLElement',LIBXML_NOCDATA) or die("Error: Cannot create object"); 
$json = json_encode($xml); // not an array
$results = json_decode($json, TRUE); //an array
// Extract just the items
$results = $results['channel']['item'];
return($results);
}

// Initially from Paw, then modified
function doCurl($title, $caption, $photourl, $endpoint, $username, $knowntoken) {

// get cURL resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, $endpoint);

// set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

// return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/x-www-form-urlencoded',
  'Accept: application/json',
//   'X-KNOWN-USERNAME: Jeremy',
//   'X-KNOWN-SIGNATURE: m1Ooz9mTkqvuGhVuxpTkif1x9EtOjaTfwe3cCbF22S4=',
  'X-KNOWN-USERNAME: ' . $username,
  'X-KNOWN-SIGNATURE: ' . $knowntoken,
  'Cookie: known=03a03330ec74f91efa11b3ea623e9a05',
]);

// form body
$body = [
  'description' => $caption,
  'title' => $title,
  'photo' => $photourl,
  'tags' => 'pesos', // Or whatever you want.
];
$body = http_build_query($body);

// set body
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// send the request and save response to $response
$response = curl_exec($ch);

// stop if fails
if (!$response) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
}

echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
echo 'Response Body: ' . $response . PHP_EOL;

// close curl resource to free up system resources 
curl_close($ch);
}

?>