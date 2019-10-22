<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharactersControllerTest extends WebTestCase
{
    public function testGetCharactersFormDimensions()
    {
        $client = static::createClient();

        $client->request('GET', '/api/characters/dimension/Dimension C-137');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetCharactersFormLocation()
    {
        $client = static::createClient();

        $client->request('GET', '/api/characters/location/earth');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetCharactersFormEpisode()
    {
        $client = static::createClient();

        $client->request('GET', '/api/characters/episode/12');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    

    public function testGetCharacter()
    {
        $client = static::createClient();

        $client->request('GET', '/api/characters/12');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}