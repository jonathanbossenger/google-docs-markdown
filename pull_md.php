<?php
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/
// [START docs_quickstart]

require __DIR__ . '/vendor/autoload.php';
require 'google_api.php'; // just a place to hold the google quickstart functions in one central place for now

use Cocur\Slugify\Slugify; // used to slugify the document title

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Docs($client);

// Pulls the contents of a Google Doc into a markdown file:
// Replace the document id with an id of a Google Document containing pure markdown that you own/have access to
// eg https://docs.google.com/document/d/1cuOmX0jAdkVocEZcPjVcdRCCw77QGjAm8pVOuSGAPls/edit
// This will need to be passed as a variable, or chosen from a list somehow...

$documentId = '1cuOmX0jAdkVocEZcPjVcdRCCw77QGjAm8pVOuSGAPls';
$doc = $service->documents->get($documentId);

printf("The document title is: %s\n", $doc->getTitle());

$slugify = new Slugify();
$fileName = $slugify->slugify($doc->getTitle()).'.md';
$docContent = $doc->getBody()->getContent();
$fileContents = '';
foreach ($docContent as $contentElement){
    if (isset($contentElement['paragraph'])){
        $elements = $contentElement->getParagraph()->getElements();
        foreach ($elements as $element){
            $textRun = $element->getTextRun();
            if(!$textRun){
                continue;
            }
            $fileContents .= $textRun->getContent();
        }
    }
}
file_put_contents($fileName, $fileContents);
printf("Created file : %s\n", $fileName);
