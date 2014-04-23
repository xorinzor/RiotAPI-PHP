RiotAPI-php
============
A PHP library to get you started quickly with implementing the League of Legends API from Riot Games in your web application.<br /><br />
Simply replace YOUR_API_KEY with your API key from [Riot Games](http://developer.riotgames.com/) to get started.
<br /><br />
<strong>Warning: The code has not been tested yet, errors and/or warnings may occur when used</strong>, I'll get around to testing and fixing any errors when I've had some sleep.


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
