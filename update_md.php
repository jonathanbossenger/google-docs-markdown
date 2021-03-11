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
 * First, we should pull the existing document
 */
try {
    $doc = $service->documents->get($documentId);
} catch (Google\Service\Exception $e){
    $message = json_decode($e->getMessage());
    printf($message->error->message . PHP_EOL);
    exit;
}

$fileName = $slugify->slugify($doc->getTitle()).'.md';

printf('Retrieving the document : %s' . PHP_EOL, $doc->getTitle());

$docContent = $doc->getBody()->getContent();
$fileContents = array();
foreach ($docContent as $contentElement){
    if (isset($contentElement['paragraph'])){
        $elements = $contentElement->getParagraph()->getElements();
        foreach ($elements as $element){
            $textRun = $element->getTextRun();
            if(!$textRun){
                continue;
            }
            $startIndex = $element->getStartIndex();
            $endIndex = $element->getEndIndex();
            $fileContents[] = array(
                'start_index' => $startIndex,
                'end_index' => $endIndex,
                'contents' => $textRun->getContent()
            );
        }
    }
}

unlink('output.txt');

$data_string = print_r(array('Document Contents', $fileContents), true) . "\n";
file_put_contents('output.txt', $data_string, FILE_APPEND);

printf('Dumped document contents to log' . PHP_EOL);

$updatedFileArray = file($fileName);

$data_string = print_r(array('Updated File Array', $updatedFileArray), true) . "\n";
file_put_contents('output.txt', $data_string, FILE_APPEND);

printf('Dumped updated document contents to log' . PHP_EOL);

//$fileContents = getDocContents($doc);

//file_put_contents($outputDirectory . $fileName, $fileContents);

//printf('Created file : %s' . PHP_EOL, $outputDirectory . $fileName);
