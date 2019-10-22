<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Services\RickAndMortyApi;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/locations")
 */
class LocationController extends ApiController
{
    private $rickyAndMortyService;

    public function __construct(RickAndMortyApi $rickyAndMortyService)
    {
        $this->rickyAndMortyService = $rickyAndMortyService;
    }

    /**
     * List all location in given location type.
     *
     * @Route("/type/{type}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="all location in given location type",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="empty response",
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Tag(name="location")
     *
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAllCharactersFromGivenDimension(string $type)
    {
            // check if first character is space
            if ($type[0] == ' ') {
                $this->setStatusCode(400);
                return $this->respondWithErrors("request param is missing or wrong");
            }

            $locations = $this->rickyAndMortyService->getLocationsByType($type);

            if (!empty($locations['results'])) {
                return $this->respond($locations['results']);
            } else {
                return $this->respondNoContent();
            }
    
    }
}
