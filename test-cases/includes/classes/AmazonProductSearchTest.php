<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-12 at 13:17:14.
 */
class AmazonProductSearchTest extends PHPUnit_Framework_TestCase {

    /**
     * @var AmazonProductSearch
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        resetLog();
        $this->object = new AmazonProductSearch('testStore', null, true, null, __DIR__.'/../../test-config.php');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testSetUp(){
        $obj = new AmazonProductSearch('testStore', 'platinum', true, null, __DIR__.'/../../test-config.php');
        
        $o = $obj->getOptions();
        $this->assertArrayHasKey('Query',$o);
        $this->assertEquals('platinum', $o['Query']);
    }
    
    public function testSetQuery(){
        $this->assertFalse($this->object->setQuery(null)); //can't be nothing
        $this->assertFalse($this->object->setQuery(5)); //can't be an int
        $this->assertNull($this->object->setQuery('platinum'));
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('Query',$o);
        $this->assertEquals('platinum',$o['Query']);
    }
    
    public function testSetContextId(){
        $this->assertFalse($this->object->setContextId(null)); //can't be nothing
        $this->assertFalse($this->object->setContextId(5)); //can't be an int
        $this->assertNull($this->object->setContextId('Kitchen'));
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('QueryContextId',$o);
        $this->assertEquals('Kitchen',$o['QueryContextId']);
    }
    
    public function testSearchProducts(){
        resetLog();
        $this->object->setMock(true,'searchProducts.xml');
        $this->assertFalse($this->object->searchProducts()); //no query yet
        $this->object->setQuery('platinum');
        
        $this->assertNull($this->object->searchProducts());
        
        $o = $this->object->getOptions();
        $this->assertEquals('ListMatchingProducts',$o['Action']);
        
        $check = parseLog();
        $this->assertEquals('Single Mock File set: searchProducts.xml',$check[1]);
        $this->assertEquals('Search Query must be set in order to search for a query!',$check[2]);
        $this->assertEquals('Fetched Mock File: mock/searchProducts.xml',$check[3]);
        
        return $this->object;
    }
    
    /**
     * @depends testSearchProducts
     */
    public function testGetProduct($o){
        $product = $o->getProduct(0);
        $this->assertInternalType('object',$product);
        
        $list = $o->getProduct(null);
        $this->assertInternalType('array',$list);
        $this->assertArrayHasKey(0,$list);
        $this->assertEquals($product,$list[0]);
        
        $default = $o->getProduct();
        $this->assertEquals($list,$default);
        
        $check = $product->getData();
        $this->assertArrayHasKey('Identifiers',$check);
        $this->assertArrayHasKey('AttributeSets',$check);
        $this->assertArrayHasKey('Relationships',$check);
        $this->assertArrayHasKey('SalesRankings',$check);
        
        $this->assertFalse($this->object->getProduct()); //not fetched yet for this object
    }
    
}

require_once('helperFunctions.php');