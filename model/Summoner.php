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

namespace RiotAPI\model;

/**
 * Contains Summoner information
 *
 * @author Jorin Vermeulen
 */
class Summoner {
	private $id;			//The id of the summoner
	private $name;			//The name of the summoner
	private $profileIconId;		//The summoners profile icon ID
	private $revisionDate; 		//timestamp from when the profile is last updated
	private $summonerLevel;		//the summoners level
	private $region;            	//the region the summoner is in

	public function __construct(
		$id 			= null, 
		$name 			= "", 
		$profileIconId 		= 0,
		$revisionDate 		= 0,
		$summonerLevel 		= 0,
		$region 		= ""
		) 
	{
		$this->id		= $id;
		$this->name		= $name;
		$this->profileIconId	= $profileIconId;
		$this->revisionDate	= $revisionDate;
		$this->summonerLevel	= $summonerLevel;
		$this->region 		= $region;
	}

	/**
	 * Getters
	 */
	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getProfileIconId() {
		return $this->profileIconId;
	}

	public function getRevisionDate() {
		return $this->revisionDate;
	}

	public function getSummonerLevel() {
		return $this->summonerLevel;
	}

	public function getRegion() {
		return $this->region;
	}

	public function toArray() {
		return array(
				'id' 		=> $this->getId(),
				'name' 		=> $this->getName(),
				'profileIconId' => $this->getProfileIconId(),
				'revisionDate' 	=> $this->getRevisionDate(),
				'summonerLevel' => $this->getSummonerLevel(),
				'region' 	=> $this->getRegion()
			);
	}

	/**
	 * Setters
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function setProfileIconId($profileIconId) {
		$this->profileIconId = $profileIconId;
		return $this;
	}

	public function setRevisionDate($revisionDate) {
		$this->revisionDate = $revisionDate;
		return $this;
	}

	public function setSummonerLevel($summonerLevel) {
		$this->summonerLevel = $summonerLevel;
		return $this;
	}

	public function setRegion($region) {
		$this->region = $region;
		return $this;
	}
}
