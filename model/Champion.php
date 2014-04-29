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
 * Contains Champion information
 */
class Champion {
	private $id;				//The id of the champion
	private $name;				//The name of the champion
	private $active;			//Indicates if the champion is active.
	private $freeToPlay; 		//Indicates if the champion is free to play. Free to play champions are rotated periodically.
	private $botEnabled; 		//Bot enabled flag (for custom games).
	private $botMmEnabled; 		//Bot Match Made enabled flag (for Co-op vs. AI games).
	private $rankedPlayEnabled;	//Ranked play enabled flag

	public function __construct(
		$id 				= null, 
		$name 				= "", 
		$active 			= false,
		$freeToPlay 		= false,
		$botEnabled 		= false,
		$botMmEnabled 		= false,
		$rankedPlayEnabled 	= false
		) 
	{
		$this->id					= $id;
		$this->name					= $name;
		$this->active				= $active;
		$this->freeToPlay			= $freeToPlay;
		$this->botEnabled			= $botEnabled;
		$this->botMmEnabled			= $botMmEnabled;
		$this->rankedPlayEnabled	= $rankedPlayEnabled;
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

	public function getActive() {
		return $this->active;
	}

	public function getFreeToPlay() {
		return $this->freeToPlay;
	}

	public function getBotEnabled() {
		return $this->botEnabled;
	}

	public function getBotMmEnabled() {
		return $this->botMmEnabled;
	}

	public function getRankedPlayEnabled() {
		return $this->rankedPlayEnabled;
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

	public function setActive($active) {
		$this->active = $active;
		return $this;
	}

	public function setFreeToPlay($freeToPlay) {
		$this->freeToPlay = $freeToPlay;
		return $this;
	}

	public function setBotEnabled($botEnabled) {
		$this->botEnabled = $botEnabled;
		return $this;
	}

	public function setBotMmEnabled($botMmEnabled) {
		$this->botMmEnabled = $botMmEnabled;
		return $this;
	}

	public function setRankedPlayEnabled($rankedPlayEnabled) {
		$this->rankedPlayEnabled = $rankedPlayEnabled;
		return $this;
	}
}
