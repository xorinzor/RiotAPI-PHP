<?php
/**
 * ====================================================================================
 * = Make sure to have set API_KEY to the correct value in RiotAPI.php before testing =
 * ====================================================================================
 * 
 * It is not recommended to run this file as-is because it will execute 5 API calls at once
 * not only will this impact your rate limit but the loading time can also be very long.
 */

//Include the class required (which will in turn load required files on its own)
require("./RiotAPI.php");

use \RiotAPI\RiotAPI;

$api = new RiotApi("na");   //Set the region in the constructor
$api->setRegion("euw");     //If needed you can change the region to use at any time using setRegion(<region>)

try {
    //Fetch all champions from the API, returns an array with \RiotAPI\model\Champion objects
    $champions      = $api->getChampions($freeToPlay = false);

    //Fetch an individual champion from the API, returns a \RiotAPI\model\Champion object
    $champion       = $api->getChampion($id = 143);

    //Fetch a summoner by name
    $summonerByName = $api->getSummonerByName("xorinzor");
    
    //Fetch a summoner by ID
    $summonerById   = $api->getSummonerById(25622575);

    //Fetch static data
    $static         = $api->getStaticData('item/{id}', array('id' => 3101));

    //pretty print the results
    echo '<pre>';
    print_r($champions);
    print_r($champion);
    print_r($summonerByName);
    print_r($summonerById);
    print_r($static);
    
} catch(Exception $e) {
    //Catch any errors that may occur and handle them within your application
    echo "<p><strong>Error:</strong> An exception occured</p><br /><pre>";
    var_dump($e);
}
