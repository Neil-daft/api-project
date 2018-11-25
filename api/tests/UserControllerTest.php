<?php

namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testGetAllEndUsers()
    {
        $this->client->request('GET', '/users/all');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetUserById()
    {
        $this->client->request('GET', '/users/3');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testSearchEndUserByEmail()
    {
        $this->client->request('GET', '/search/users?email=neilo2000@gmail.com');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetAUsersJobs()
    {
        $this->client->request('GET', '/users/3/jobs');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
