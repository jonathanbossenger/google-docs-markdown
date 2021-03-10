<?php
/**
 * Pull the contents of a Google Document into a markdown file
 */
require __DIR__ . '/vendor/autoload.php';
// just a place to hold the google quickstart and other functions in one central place for now
require 'google_api.php';

/**
 * used to slugify the document title
 */
use Cocur\Slugify\Slugify;
$slugify = new Slugify();

/**
 * Get the API client and construct the service object.
 */
$client = getClient();
$service = new Google_Service_Docs($client);

/**
 * Get arguments
 */
$arguments = $argv;
if (empty($arguments[0])) {
    printf('No document ID specified, exiting');
    exit();
}
$documentId = $arguments[0];
$outputDirectory = isset($arguments[1]) ? $arguments[0] : getcwd() . DIRECTORY_SEPARATOR;

/**
 * Pull doc and store to file
 */
$doc = $service->documents->get($documentId);
printf("Retrieving the document : %s\n", $doc->getTitle());

$fileName = $slugify->slugify($doc->getTitle()).'.md';
$fileContents = getDocContents($doc);

file_put_contents($outputDirectory . $fileName, $fileContents);

printf("Created file : %s\n", $outputDirectory . $fileName);
