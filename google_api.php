<?php

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 * @throws \Google\Exception
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Docs API For PHP');
    $client->setScopes(Google_Service_Docs::DOCUMENTS_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory('token.json');
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            if (!mkdir($concurrentDirectory = dirname($credentialsPath), 0700, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

/**
 * Traverse through a document's elements and return the contents
 *
 * @param $doc
 * @return string
 */
function getDocContents($doc){
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
    return $fileContents;
}