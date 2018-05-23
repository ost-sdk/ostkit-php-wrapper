<?php

namespace Ostkit;

class OstkitTest extends \PHPUnit_Framework_TestCase
{
    protected $ostkit;
    
    function setUp() {
        $apikey = 'ad7143e1ff8518588bcd';
        $apisecret = '435462fb707d33a6664cb46f947f0b778a8a0fe5d02a2be09f8749e5f78d2281';
        $this->ostkit = new Ostkit($apikey, $apisecret);
    }

    public function testUserCreate() {
        $name = 'Luong';
        $response = $this->ostkit->userCreate($name);
        $this->assertEquals($response['success'], true);
        $this->assertEquals(array_keys($response), ['success', 'data']);
        $this->assertEquals(array_keys($response['data']), ['result_type', 'user']);
        $this->assertEquals($response['data']['result_type'], 'user');
        $this->assertEquals(array_keys($response['data']['user']), ['id', 'addresses', 'name', 'airdropped_tokens', 'token_balance']);
        
        $this->assertCount(1, $response['data']['user']['addresses']);
        $this->assertCount(2, $response['data']['user']['addresses'][0]);
    
        $this->assertEquals(isset($response['data']['user']['addresses']), true);
        $this->assertEquals(isset($response['data']['user']['addresses'][0]), true);
    }


    public function testUserEdit() {
        $id = 'f3b6bf71-6135-4961-b713-3a62f5582a02';
        $name = 'Luong Updated';
        $response = $this->ostkit->userEdit($id, $name);
        $this->assertEquals($response['success'], true);
        $this->assertEquals(array_keys($response), ['success', 'data']);
        $this->assertEquals(array_keys($response['data']), ['result_type', 'user']);
        $this->assertEquals($response['data']['result_type'], 'user');
        $this->assertEquals(array_keys($response['data']['user']), ['id', 'addresses', 'name', 'airdropped_tokens', 'token_balance']);
        
        $this->assertCount(1, $response['data']['user']['addresses']);
        $this->assertCount(2, $response['data']['user']['addresses'][0]);
    
        $this->assertEquals(isset($response['data']['user']['addresses']), true);
        $this->assertEquals(isset($response['data']['user']['addresses'][0]), true);
    }

     public function testUserRetrieve() {
        $id = 'f3b6bf71-6135-4961-b713-3a62f5582a02';
        $response = $this->ostkit->userRetrieve($id);
        $this->assertEquals($response['success'], true);
        $this->assertEquals(array_keys($response), ['success', 'data']);
        $this->assertEquals(array_keys($response['data']), ['result_type', 'user']);
        $this->assertEquals($response['data']['result_type'], 'user');
        $this->assertEquals(array_keys($response['data']['user']), ['id', 'addresses', 'name', 'airdropped_tokens', 'token_balance']);
        
        $this->assertCount(1, $response['data']['user']['addresses']);
        $this->assertCount(2, $response['data']['user']['addresses'][0]);
    
        $this->assertEquals(isset($response['data']['user']['addresses']), true);
        $this->assertEquals(isset($response['data']['user']['addresses'][0]), true);
    }

    public function testUserList() {
        $response = $this->ostkit->userList();
        $this->assertEquals($response['success'], true);
        $this->assertEquals(array_keys($response), ['success', 'data']);
        $this->assertEquals(array_keys($response['data']), ['result_type', 'users', 'meta']);
        $this->assertEquals($response['data']['result_type'], 'users');
        $this->assertEquals(array_keys($response['data']['users'][0]), ['id', 'addresses', 'name', 'airdropped_tokens', 'token_balance']);
        
        $this->assertCount(1, $response['data']['users'][0]['addresses']);
        $this->assertCount(2, $response['data']['users'][0]['addresses'][0]);
    
        $this->assertEquals(isset($response['data']['users'][0]['addresses']), true);
        $this->assertEquals(isset($response['data']['users'][0]['addresses'][0]), true);
    }
}