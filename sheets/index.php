<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets\SpreadSheet;


create("TEST SPK");


/**
 * create an empty spreadsheet
 *
 */

function create($title)
{
    /* Load pre-authorized user credentials from the environment.
       TODO(developer) - See https://developers.google.com/identity for
        guides on implementing OAuth2 for your application. */
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $client->setAuthConfig('credentials.json');

    $service = new Google_Service_Sheets($client);
    try{

        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $title
            ]
        ]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ]);
        printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
        return $spreadsheet->spreadsheetId;
    }
    catch(Exception $e) {
        // TODO(developer) - handle error appropriately
        echo 'Message: ' .$e->getMessage();
    }
}