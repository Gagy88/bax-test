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
     * @Route("/dimension", methods={"GET"})
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
     * @SWG\Parameter(
     *     name="dimension",
     *     in="query",
     *     type="string",
     *     description="The field used to get characters in dimension"
     * )
     * @SWG\Tag(name="characters")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenDimension(Request $request)
    {
        if ($request->get('dimension') == '') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing");
        } else {
            $dimension = $request->get('dimension');
        }

        $dimensions = $this->rickyAndMortyService->getDimensions($dimension);

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
     * @Route("/location", methods={"GET"})
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
     * @SWG\Parameter(
     *     name="location",
     *     in="query",
     *     type="string",
     *     description="The field used to get characters in location"
     * )
     * @SWG\Tag(name="characters")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenLocation(Request $request)
    {
        if ($request->get('location') == '') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing");
        } else {
            $location = $request->get('location');
        }

        $locations = $this->rickyAndMortyService->getLocations($location);

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
     * @Route("/episode", methods={"GET"})
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
     * @SWG\Parameter(
     *     name="episode",
     *     in="query",
     *     type="string",
     *     description="The field used to get characters in episode"
     * )
     * @SWG\Tag(name="characters")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenEpisode(Request $request)
    {
        if ($request->get('episode') == '') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing");
        } else {
            $episode = $request->get('episode');
        }

        $episodes = $this->rickyAndMortyService->getEpisodesById([$episode]);

        if (!empty($episodes['characters'])) {
            $response = $this->rickyAndMortyService->getCharactersById($this->getCharactersId($episodes['characters']));
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
