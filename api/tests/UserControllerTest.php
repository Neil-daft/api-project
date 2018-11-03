<?php
namespace App\tests;

use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{

    public function testGetAllEndUsers()
    {
        $client = static::createClient();

        $client->request('GET', '/user/all');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetUserById()
    {

    }

    public function testGetEndUserByEmail()
    {

    }
}
