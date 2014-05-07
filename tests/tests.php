<?php

class tests extends PHPUnit_Framework_TestCase
{
    private $api;
    
    public function setUp()
    {
        $this->api = new RiotAPI("euw");
    }

    public function testGetValidRegions()
    {
        $this->assertInstanceOf('array', $this->api->getValidRegions());
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
        $this->setExpectedException('CurlException');
        $this->api->executeCall('http://nonexisiting.example.com/');
    }
    
    public function testExecuteCallWithoutValidApiKey()
    {
        $this->setExpectedException('ApiException');
        $this->api->executeCall($this->api->buildURL(RiotAPI::STATIC_SERVER_URL . 'versions'));
    }
}
