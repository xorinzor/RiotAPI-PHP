<?php
/*
== RiotAPI-php ==
Jorin Vermeulen (http://jorinvermeulen.com)
https://github.com/xorinzor/RiotAPI-php

== License ==
The MIT License (MIT)

Copyright (c) 2014 Jorin Vermeulen

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace RiotAPI;

$path = realpath(dirname(__FILE__)) . '/';

require_once($path . 'lib/CacheAPC.php');

require_once($path . 'exceptions/ApiException.php');
require_once($path . 'exceptions/CurlException.php');
require_once($path . 'model/Summoner.php');
require_once($path . 'model/Champion.php');

use \RiotAPI\exceptions\ApiException;
use \RiotApi\exceptions\CurlException;
use \RiotAPI\model\Summoner;
use \RiotAPI\model\Champion;

use \CacheAPC;
use \ArrayObject;
use \Exception;

class RiotAPI {
	const STATIC_SERVER_URL		    = 'http://prod.api.pvp.net/api/lol/static-data/{region}/v1.2/';
	const CHAMPION_SERVER_URL 	    = 'http://prod.api.pvp.net/api/lol/{region}/v1.2/champion';
	const GAME_SERVER_URL		    = 'http://prod.api.pvp.net/api/lol/{region}/v1.3/game/';
	const LEAGUE_SERVER_URL 	    = 'http://prod.api.pvp.net/api/lol/{region}/v2.3/league/';
	const STATS_SERVER_URL		    = 'http://prod.api.pvp.net/api/lol/{region}/v1.3/stats/';
	const SUMMONER_SERVER_URL 	    = 'http://prod.api.pvp.net/api/lol/{region}/v1.4/summoner/';
	const TEAM_SERVER_URL 		    = 'http://prod.api.pvp.net/api/lol/{region}/v2.2/team/';
 
	const API_KEY 			= 'YOUR_API_KEY'; //The Riot API key to use

	const CACHE_LIFETIME_CHAMPIONS  = 900; //Time (in seconds) until champion cache results are refreshed
	const CACHE_LIFETIME_SUMMONERS  = 900; //Time (in seconds) until summoner cache results are refreshed
	const CACHE_LIFETIME_STATICDATA = 3600; //Time (in seconds) until static data results are refreshed

	const CACHE_ENABLED             = true; //setting this to false will disable all caching regardless of other settings
	
	const CACHE_CHAMPIONS           = true; //Enable/Disable Champion caching, it is highly recommended to leave this enabled
	const CACHE_SUMMONERS           = true; //Enable/Disable Summoner caching, it is highly recommended to leave this enabled, adjust the cache lifetime where necessary
	const CACHE_STATICDATA 		= true; //Enable/Disable Static Data caching, it is highly recommended to leave this enabled
	
	const CACHE_METHOD_APC          = true; //Enable/Disable PHP-APC caching, see: http://www.php.net/manual/en/book.apc.php

	private $region; 

	private $validRegions = array(
			'na',
			'br',
			'euw',
			'eune',
			'lan',
			'las',
			'oce',
			'kr',
			'ru',
			'tr'
		);

	private $sql;

	/**
	 * Constructor
	 * May throw exceptions when extensions are missing
	 * @param string region
	 */
	public function __construct($region = null) {
		$this->region = null;
		
		//Check when CACHE_APC is set to true if the extension is loaded too to prevent errors later on
		if(self::CACHE_ENABLED && self::CACHE_METHOD_APC && !extension_loaded('apc')) {
			throw new Exception("the PHP APC extension is not installed or has not been loaded, either enable the 'apc' extension or set CACHE_METHOD_APC to FALSE");
		}
	}

	/**
	 * Get the region to use for the API calls
	 * @return string
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * Get an array of valid regions
	 * @return array
	 */
	public function getValidRegions() {
		return $this->validRegions;
	}

	/**
	 * Set the region to use for the API calls
	 * @param string the region to use for API calls
	 */
	public function setRegion($region) {
		$this->region = $region;
	}

	/**
	 * Check if the region is set and is valid
	 * @return boolean
	 */
	public function regionIsSet() {
		return in_array($this->getRegion(), $this->getValidRegions());
	}

	/**
	 * Build the URL to use for the call
	 * @param string url the base url
	 * @param array query parameters (such as region, summonerIds, etc)
	 * @param array path parameters (such as locale, version, etc)
	 * @return string
	 */
	private function buildURL($url, $query = array(), $path = array()) {
		//Check if the region is valid, if it isn't executing the API call won't be of any use
		if(!$this->regionIsSet()) {
			throw new ApiException("Invalid region is set for the Riot API call!");
		}

		//Set the region in the url
		$url = str_replace('{region}', $this->getRegion(), $url);

		//Add path parameters
		foreach($query as $key=>$value) {
			$url = str_replace('{'.$key.'}', rawurlencode($value), $url);
		}

		//Add the API key query parameter to the url
		$url .= '?api_key=' . self::API_KEY;

		//Add the query parameters to the URL
		foreach($path as $key=>$value) {
			$url .= '&' . rawurlencode($key) . '=' . rawurlencode($value);
		}

		//Return the finished URL
		return $url;
	}

	/**
	 * Open up a cURL connection
	 * @return mixed returns the body as string on success else returns httpcode as int
	 */ 
	private function openUrl($url) {
		//  Initiate curl
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	//Disable SSL verification
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 	//Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_URL,$url); 				// Set the url
		curl_setopt($ch, CURLOPT_HEADER, true);             //We want headers

		// Execute
		$response       = curl_exec($ch);
		
		if($response === false) {
			throw new CurlException("An error occured while executing the cURL request", 0, $ch, curl_getinfo($ch));
		}
		
		$header_size    = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header         = substr($response, 0, $header_size);
		$body           = substr($response, $header_size);
		
		$httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		/*
			Return the HTTP code if its not 200 (OK), since the result otherwise always would be of type "string"
			any method using openUrl can tell by checking if the result is numeric if something went wrong
		*/
		if($httpcode === 401) {
			throw new ApiException("Access Denied to the API, did you set the correct API_KEY?");
		} elseif($httpcode !== 200) {
			return $httpcode;
		}
		
		//Close connection and clean up server resources
		curl_close($ch);

		return $body;
	}

	/**
	 * Open the url and try to decode the resulting JSON into an array, also handles errors that may occur
	 * @param string the URL to fetch
	 * @return mixed either numeric when an http error code is returned, else returns the decoded json as array
	 */
	public function executeCall($url) {
		//Check if a HTTP code is returned
		$result = $this->openUrl($url);
		
		if(is_numeric($result)) {
			return $result;
		}
		
		//Check if the body is valid JSON
		$decoded = json_decode($result, true);
		
		if($decoded === null || $decoded === false) {
			throw new ApiException("JSON result could not be decoded or was invalid JSON");
		}

		return $decoded;
	}

	/**
	 * Fetch all champions and their information
	 * @TODO fix fetching only FtP champions from cache
	 * @param boolean only fetch Free To Play champions
	 * @return array
	 */
	public function getChampions($ftp = false) {
		//Fetch cache results IF enabled
		if(self::CACHE_ENABLED && self::CACHE_CHAMPIONS && self::CACHE_METHOD_APC) {
			$cache = CacheAPC::getData('champions');
		} else {
			$cache = null;
		}

		//Check if cache exists
		if(!is_null($cache)) {
			//Check if only Free To Play champions are requested
			if($ftp === true) {
				$result = $cache->getArrayCopy();
				
				//Remove each non-free to play champion from the result array
				foreach($result as &$value) {
					if(!$value->getFreeToPlay()) {
						unset($value);
					}
				}
				
				//Return the free to play champions
				return $result;
			}
			
			//Return all champions
			return $cache->getArrayCopy();
		}

		//Create the URL to call
		$url = $this->buildURL(self::CHAMPION_SERVER_URL, array(), array('freeToPlay' => (($ftp === true) ? 'true' : 'false')));

		//Execute the API call
		$champions = $this->executeCall($url);
		
		//Get the static champions
		$staticChampions = $this->getStaticData('champion', array(), array('dataById' => 'true'));
		$staticChampions = $staticChampions['data'];

		//Initialize return variable
		$result = array();

		//Populate the return array with the champions fetched from the API
		foreach($champions['champions'] as $champion) {
			$result[$champion['id']] = new Champion(
					$champion['id'],
					$staticChampions[$champion['id']]['name'],
					$champion['active'],
					$champion['freeToPlay'],
					$champion['botEnabled'],
					$champion['botMmEnabled'],
					$champion['rankedPlayEnabled']
				);
		}

		//store cache results IF enabled
		if(self::CACHE_ENABLED && self::CACHE_CHAMPIONS && self::CACHE_METHOD_APC) {
			//Store the Champion information in the memory for 5 minutes (cached, but not too long)
			CacheAPC::setData('champions', new ArrayObject($result), self::CACHE_LIFETIME_CHAMPIONS);
		}

		return $result;
	}

	/**
	 * Fetch the champion with the given ID and its information from the cache
	 * if the cache doesn't exist it will be refreshed
	 * @param int the ID from the champion to fetch
	 * @return \RiotAPI\model\Champion
	 */
	public function getChampion($id) {
		if(self::CACHE_ENABLED && self::CACHE_CHAMPIONS && self::CACHE_METHOD_APC) {
			$cache = CacheAPC::getData('champions');
		} else {
			$cache = null;
		}

		//The cache will contain an ArrayObject instance
		if(!is_null($cache)) {
			$cache = $cache->getArrayCopy();
			return $cache[$id];
		}

		//No cache of the champions exists, refresh it
		$result = $this->getChampions();

		return $result[$id];
	}

	/**
	 * Get the summoner information by id
	 * @parameter id the id of the summoner
	 * @return \RiotAPI\model\Summoner
	 */
	public function getSummonerById($id) {
		//Make sure the id provided is not empty and is numeric
		if(!is_numeric($id) || $id == '') {
			return null;
		}

		//Create the URL to call
		$url = $this->buildURL(self::SUMMONER_SERVER_URL . '{summonerIds}', array('summonerIds' => $id));

		//Execute the API call
		$summoner = $this->executeCall($url);
		
		//This API call will return 404 if no summoner with such a name is found
		if($summoner === 404) {
			return null;
		}
		
		//Get the array value from the first key, set it as the main array
		$summoner = reset($summoner);
		
		//Add the current region to the array
		$summoner['region'] = $this->getRegion();

		//Return the summoner object
		return (new Summoner())
					->setId($summoner['summonerId'])
					->setName($summoner['name'])
					->setProfileIconId($summoner['profileIconId'])
					->setRevisionDate($summoner['revisionDate'])
					->setSummonerLevel($summoner['summonerLevel'])
					->setRegion($summoner['region']);
	}

	/**
	 * Get the summoner information by name
	 * @parameter string the name of the summoner
	 * @return \RiotAPI\model\Summoner
	 */
	public function getSummonerByName($name) {
		//Make sure the name provided is not empty
		if($name == '') {
			throw new ApiException("The summoner name to fetch can't be empty!");
		}

		//Create the URL to call
		$url = $this->buildURL(self::SUMMONER_SERVER_URL . 'by-name/{summonerNames}', array('summonerNames' => $name));

		//Execute the API call
		$summoner = $this->executeCall($url);
		
		//This API call will return 404 if no summoner with such a name is found
		if($summoner === 404) {
			return null;
		}
		
		//Get the array value from the first key, set it as the main array
		$summoner = reset($summoner);
		
		//Add the current region to the array
		$summoner['region'] = $this->getRegion();

		//Return the summoner object
		return (new Summoner())
					->setId($summoner['id'])
					->setName($summoner['name'])
					->setProfileIconId($summoner['profileIconId'])
					->setRevisionDate($summoner['revisionDate'])
					->setSummonerLevel($summoner['summonerLevel'])
					->setRegion($summoner['region']);
	}
	
	/**
	 * Get data from the static-data API, these requests will not be counted in your rate limit
	 * @param string the type of data you want to request (champion, champion/{id},  item, item/{id}, etc (full list can be found at http://developer.riotgames.com/api/methods#!/710/))
	 * @param array an array containing path parameters (such as region, id, etc)
	 * @param array an array containing query parameters (such as locale, version, etc)
	 * @return mixed
	 */
	public function getStaticData($type, $path = array(), $query = array()) {
		//Make sure the type provided is not empty
		if($type == '') {
			throw new ApiException("The static data type to fetch can't be empty!");
		}
		
		//Check if cache is enabled and if so, whether cached data exists
		if(self::CACHE_ENABLED && self::CACHE_STATICDATA && self::CACHE_METHOD_APC) {
			$cache = CacheAPC::getData('static_'.$type);
		} else {
			$cache = null;
		}
		
		//Check if cache exists
		if(!is_null($cache)) {
			return $cache;
		}
		
		//Create the URL to call
		$url = $this->buildURL(self::STATIC_SERVER_URL . $type, $path, $query);
		
		//Execute the API call
		$result = $this->executeCall($url);
		
		/* 
		An array will only be returned when the call succeeds and no exceptions occur
		therefor we can safely assume if the result is NOT an array that the api call returned an error code
		*/
		if(!is_array($result)) {
			return null;
		}
		
		//store results in cache IF caching is enabled
		if(self::CACHE_ENABLED && self::CACHE_STATICDATA && self::CACHE_METHOD_APC) {
			CacheAPC::setData('static_'.$type, $result, self::CACHE_LIFETIME_STATICDATA);
		}
		
		return $result;
	}
}
