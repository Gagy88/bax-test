<?php

namespace App\Services;

class RickAndMortyApi extends RickAndMortyApiWrapper
{
    public function getDimension(string $dimension)
    {
        return $this->getByParams('location', ['dimension' => $dimension]);
    }

    public function getCharactersById(array $id)
    {
        return $this->getById('character/' . implode(", ", $id));
    }
}
