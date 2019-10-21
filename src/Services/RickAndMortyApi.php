<?php

namespace App\Services;

class RickAndMortyApi extends RickAndMortyApiWrapper
{
    /**
     *
     * @param string $dimension
     * @return array
     */
    public function getDimensions(string $dimensionName): array
    {
        return $this->getByParams('location', ['dimension' => $dimensionName]);
    }

    /**
     *
     * @param string $locationName
     * @return array
     */
    public function getLocations(string $locationName): array
    {
        return $this->getByParams('location', ['name' => $locationName]);
    }

    /**
     *
     * @param array $id
     * @return array
     */
    public function getCharactersById(array $id)
    {
        return $this->getById('character/' . implode(", ", $id));
    }
}
