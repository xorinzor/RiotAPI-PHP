RiotAPI-php
============
A PHP library to get you started quickly with implementing the League of Legends API from Riot Games in your web application.<br /><br />
Simply replace YOUR_API_KEY with your API key from [Riot Games](http://developer.riotgames.com/) to get started.

Example
============
```php
<?php
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
    //Catch any errors that may occur and handle them within your application
    echo "<p><strong>Error:</strong> An exception occured</p><br /><pre>";
    var_dump($e);
}
```


Current functions
------------
 + setRegion($region);
 + executeCall($url);
 + getChampions($freeToPlay);
 + getChampion($id);
 + getSummonerById($id);
 + getSummonerByName($name);


@TODO
------------
 + Implement the remaining functions (api calls)
 + Rate Limit counter
 + More caching methods
 + Suggestions? Let me know via a ticket in the issues section
