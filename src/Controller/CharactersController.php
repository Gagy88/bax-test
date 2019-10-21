<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Services\RickAndMortyApi;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/characters")
 */
class CharactersController extends ApiController
{
    private $rickyAndMortyService;

    public function __construct(RickAndMortyApi $rickyAndMortyService)
    {
        $this->rickyAndMortyService = $rickyAndMortyService;
    }

    /**
     * List all characters that exist (or are last seen) in a given dimension.
     *
     * @Route("/dimension/{dimensionName}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="all characters that exist (or are last seen) in a given dimension",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="empty response",
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Tag(name="characters")
     *
     * @param string $dimensionName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenDimension(string $dimensionName)
    {
        // check if first character is space
        if ($dimensionName[0] == ' ') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing or wrong");
        }

        $dimensions = $this->rickyAndMortyService->getDimensions($dimensionName);

        if (!empty($dimensions['results'])) {
            $response = $this->rickyAndMortyService->getCharactersById($this->getCharactersIdFromLocation($dimensions['results']));
        } else {
            $response = [];
            $this->setStatusCode(204);
        }

        return $this->respond($response);
    }

    /**
     * List all characters that exist (or are last seen) at a given location.
     *
     * @Route("/location/{locationName}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="all characters that exist (or are last seen) at a given location",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="empty response",
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Tag(name="characters")
     *
     * @param string locationName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenLocation(string $locationName)
    {
        // check if first character is space
        if ($locationName[0] == ' ') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing or wrong");
        }

        $locations = $this->rickyAndMortyService->getLocations($locationName);

        if (!empty($locations['results'])) {
            $response = $this->rickyAndMortyService->getCharactersById($this->getCharactersIdFromLocation($locations['results']));
        } else {
            $response = [];
            $this->setStatusCode(204);
        }

        return $this->respond($response);
    }

    /**
     * Show all characters that partake in a given episode.
     *
     * @Route("/episode/{episodeId}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="all characters that partake in a given episode",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="empty response",
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Tag(name="characters")
     *
     * @param string episodeId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenEpisode(string $episodeId)
    {
        // check if first character is space
        if ($episodeId[0] == ' ') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing or wrong");
        }

        $episodes = $this->rickyAndMortyService->getEpisodesById([$episodeId]);

        if (!empty($episodes['characters'])) {
            $response = $this->rickyAndMortyService->getCharactersById($this->getCharactersId($episodes['characters']));
        } else {
            $response = [];
            $this->setStatusCode(204);
        }

        return $this->respond($response);
    }

    /**
     * Showing all information of a character (Name, species, gender, last location, dimension, etc).
     *
     * @Route("/{id}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="all information of a character",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="empty response",
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Tag(name="characters")
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchCharacter(int $id)
    {
        $character = $this->rickyAndMortyService->getCharactersById([$id]);

        if (!empty($character)) {
            $response = $character;
        } else {
            $response = [];
            $this->setStatusCode(204);
        }

        return $this->respond($response);
    }

    /**
     * Gets id of characters form some locations
     *
     * @param array $data
     * @return array
     */
    private function getCharactersIdFromLocation(array $data)
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
    private function getCharactersId($data)
    {
        $charactersId = [];

        foreach ($data as $item) {
            $charactersId[] = substr($item, strrpos($item, "/") + 1, strlen($item));
        }

        return $charactersId;
    }
}
