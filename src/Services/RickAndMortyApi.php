<?php

namespace App\Services;

class RickAndMortyApi extends RickAndMortyApiClient
{
    /**
     *
     * @param string $dimension
     * @return array
     */
    public function getDimensions(string $dimensionName): array
    {
        return $this->sendRequest('location', ['dimension' => $dimensionName]);
    }

    /**
     *
     * @param string $locationName
     * @return array
     */
    public function getLocations(string $locationName): array
    {
        return $this->sendRequest('location', ['name' => $locationName]);
    }

    /**
     * @param array $episodeName
     * @return array
     */
    public function getEpisodesById(array $id): array
    {
        return $this->sendRequest('episode/' . implode(", ", $id));
    }

    /**
     *
     * @param array $id
     * @return array
     */
    public function getCharactersById(array $id)
    {
        return $this->sendRequest('character/' . implode(", ", $id));
    }

    /**
     * Gets id of characters form some locations
     *
     * @param array $data
     * @return array
     */
    public function getCharactersIdFromLocation(array $data)
    {
        $charactersId = [];

        foreach ($data as $result) {
            foreach ($result['residents'] as $resident) {
                $charactersId[] = substr($resident, strrpos($resident, "/") + 1, strlen($resident));
            }
        }

        return $charactersId;
    }

    /**
     * Gets id of characters
     *
     * @param array $data
     * @return array
     */
    public function getCharactersId($data)
    {
        $charactersId = [];

        foreach ($data as $item) {
            $charactersId[] = substr($item, strrpos($item, "/") + 1, strlen($item));
        }

        return $charactersId;
    }
}
