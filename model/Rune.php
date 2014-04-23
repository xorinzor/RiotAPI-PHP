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
 * Contains all Champion information available
 *
 * @author Jorin Vermeulen
 */
class Rune extends \InfinityCMS\mvc\AbstractModel {
	private $id;			//The id of the Rune
	private $name;			//The name of the Rune
	private $description;	//The description of the Rune
	private $tier;			//The tier level of the Rune

	public function __construct() {
		$this->id			= null;
		$this->name			= "";
		$this->description 	= "";
		$this->tier 		= 0;
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

	public function getDescription() {
		return $this->description;
	}

	public function getTier() {
		return $this->tier;
	}

	/**
	 * Setters
	 */
	public function setId(int $id) {
		$this->id = $id;
	}

	public function setName(string $name) {
		$this->name = $name;
	}

	public function setDescription(string $description) {
		$this->description = $description;
	}

	public function setTier(int $tier) {
		$this->tier = $tier;
	}
}