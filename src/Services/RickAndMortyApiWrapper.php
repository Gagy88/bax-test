<?php

namespace App\Services;

use GuzzleHttp\Client;

class RickAndMortyApiWrapper
{
    /**
     * The Guzzle instance used for HTTP requests
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Guzzle HTTP request options
     * @var array
     */
    private $options = [
        'base_uri' => 'https://rickandmortyapi.com/api/',
        'http_errors' => false,
    ];

    /**
     * RickAndMortyApi constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = array_merge($options, $this->options);
        $this->client = new Client($this->options);
    }

    /**
     * @param Client $client
     * @return RickAndMortyApi
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set Guzzle options
     * @param array $options
     * @return RickAndMortyApi
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Send HTTP Request to API
     *
     * @param null $uri
     * @param null $arguments
     * @return mixed
     */
    public function sendRequest($uri = null, $arguments = null)
    {
        return $this->client->get($uri, $arguments);
    }

    /**
     * Get all data from RickAndMortyApi
     *
     * @param string $uri
     * @return array
     */
    public function getAll(string $uri): array
    {
        $response = $this->sendRequest($uri);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get quired data from RickAndMortyApi
     *
     * @param string $uri
     * @param array $params
     * @return array
     */
    public function getByParams(string $uri, array $params): array
    {
        $response = $this->sendRequest($uri, ['query' => $params]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get enities from one or multiple id
     *
     * @param string $uri
     * @return array
     */
    public function getById(string $uri): array
    {
        $response = $this->sendRequest($uri);

        return json_decode($response->getBody()->getContents(), true);
    }
}
