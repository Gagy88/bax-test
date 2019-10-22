<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Services\RickAndMortyApi;
use Swagger\Annotations as SWG;
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
            $response = $this->rickyAndMortyService->getCharactersById($this->rickyAndMortyService->getCharactersIdFromLocation($dimensions['results']));
            return $this->respond($response);
        } else {
            return $this->respondNoContent();
        }
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
            $response = $this->rickyAndMortyService->getCharactersById($this->rickyAndMortyService->getCharactersIdFromLocation($locations['results']));
            return $this->respond($response);
        } else {
            return $this->respondNoContent();
        }
    }

    /**
     * Show all characters that partake in a given episode.
     *
     * @Route("/episode/{episodeId}", methods={"GET"}, requirements={"page"="\d+"}))
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
     * @param int episodeId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenEpisode(int $episodeId)
    {
        $episodes = $this->rickyAndMortyService->getEpisodesById([$episodeId]);

        if (!empty($episodes['characters'])) {
            $response = $this->rickyAndMortyService->getCharactersById($this->rickyAndMortyService->getCharactersId($episodes['characters']));
            return $this->respond($response);
        } else {
            return $this->respondNoContent();
        }
    }

    /**
     * Showing all information of a character (Name, species, gender, last location, dimension, etc).
     *
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"}))
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
            return $this->respond($character);
        } else {
            return $this->respondNoContent();
        }
    }
}
