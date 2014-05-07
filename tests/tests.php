<?php

require("../RiotAPI.php");

use \RiotAPI\RiotAPI;
use \RiotAPI\Exceptions\CurlException;
use \RiotAPI\Exceptions\ApiException;
        
class tests extends PHPUnit_Framework_TestCase
{
    private $api;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->api = new RiotAPI("euw");
        $this->assertNotNull($this->api);
    }

    public function testRegionIsSet()
    {
        $this->assertTrue($this->api->regionIsSet());
    }
    
    public function testBuildUrl()
    {
        $this->assertNotEmpty($this->api->buildURL(RiotAPI::STATIC_SERVER_URL . 'versions'));
    }

    public function testExecuteCallToIncorrectUrl()
    {
        $this->setExpectedException('\RiotAPI\Exceptions\CurlException');
        $this->api->executeCall('http://nonexisiting.example.com/');
    }
    
    public function testExecuteCallWithoutValidApiKey()
    {
        $this->setExpectedException('\RiotAPI\Exceptions\ApiException');
        $this->api->executeCall($this->api->buildURL(RiotAPI::STATIC_SERVER_URL . 'versions'));
    }
}
