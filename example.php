<?php
/**
 * ====================================================================================
 * = Make sure to have set API_KEY to the correct value in RiotAPI.php before testing =
 * ====================================================================================
 */

//Include the class required (which will in turn load required files on its own)
require("./RiotAPI.php");

use \RiotAPI\RiotAPI;

$api = new RiotApi("na");   //Set the region in the constructor
$api->setRegion("euw");     //If needed you can change the region to use at any time using setRegion(<region>)

try {
    //Fetch the champions from the API
    $champions = $api->getChampions($freeToPlay = false);

    //pretty print the results
    echo '<pre>';
    print_r($champions);
    
} catch(Exception $e) {
    
    echo "<p><strong>Error:</strong> An exception occured</p><br /><pre>";
    var_dump($e);
}