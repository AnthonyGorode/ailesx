<?php

Namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlControllerTest extends WebTestCase
{
    public function testShowUrl()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowPost2()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowPost3()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowPost4()
    {
        $client = static::createClient();

        $client->request('GET', '/home/tarifs');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

?>