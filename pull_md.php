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
/**
 * The first (0) argument is the script file name, will this be the same when this is a phar?
 */
if (empty($arguments[1])) {
    printf('No document ID specified, exiting.' . PHP_EOL);
    exit();
}
$documentId = $arguments[1];
$outputDirectory = isset($arguments[2]) ? $arguments[2] : getcwd() . DIRECTORY_SEPARATOR;

/**
 * Pull doc and store to file
 */
try {
    $doc = $service->documents->get($documentId);
} catch (Google\Service\Exception $e){
    $message = json_decode($e->getMessage());
    printf($message->error->message . PHP_EOL);
    exit;
}

printf('Retrieving the document : %s' . PHP_EOL, $doc->getTitle());

$fileName = $slugify->slugify($doc->getTitle()).'.md';
$fileContents = getDocContents($doc);

file_put_contents($outputDirectory . $fileName, $fileContents);

printf('Created file : %s' . PHP_EOL, $outputDirectory . $fileName);
