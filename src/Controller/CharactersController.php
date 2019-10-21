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
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Parameter(
     *     name="dimension",
     *     in="query",
     *     type="string",
     *     description="The field used to get character in dimension"
     * )
     * @SWG\Tag(name="characters")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenDimension(Request $request)
    {
        $charactersId = [];

        if ($request->get('dimension') == '') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing");
        } else {
            $dimension = $request->get('dimension');
        }

        $dimensions = $this->rickyAndMortyService->getDimensions($dimension);

        if (!empty($dimensions['results'])) {
            foreach ($dimensions['results'] as $dimension) {
                foreach ($dimension['residents'] as $resident) {
                    array_push($charactersId, substr($resident, strrpos($resident, "/") + 1, strlen($resident)));
                }
            }

            $response = $this->rickyAndMortyService->getCharactersById($charactersId);
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
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Parameter(
     *     name="location",
     *     in="query",
     *     type="string",
     *     description="The field used to get character in location"
     * )
     * @SWG\Tag(name="characters")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenLocation(Request $request)
    {
        $charactersId = [];

        if ($request->get('location') == '') {
            $this->setStatusCode(400);
            return $this->respondWithErrors("request param is missing");
        } else {
            $location = $request->get('location');
        }

        $locations = $this->rickyAndMortyService->getLocations($location);

        if (!empty($locations['results'])) {
            foreach ($locations['results'] as $location) {
                foreach ($location['residents'] as $resident) {
                    array_push($charactersId, substr($resident, strrpos($resident, "/") + 1, strlen($resident)));
                }
            }
            
            $response = $this->rickyAndMortyService->getCharactersById($charactersId);
        } else {
            $response = [];
            $this->setStatusCode(204);
        }

        return $this->respond($response);
    }
}
