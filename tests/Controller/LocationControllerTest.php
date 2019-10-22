<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationControllerTest extends WebTestCase
{
    public function testGetCharactersFormDimensions()
    {
        $client = static::createClient();

        $client->request('GET', '/api/locations/type/Planet');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}