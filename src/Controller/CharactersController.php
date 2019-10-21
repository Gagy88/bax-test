<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Model\Characters;
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
     * @Route("/dimension", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    /**
     * List all characters that exist (or are last seen) in a given dimension.
     *
     * @Route("/dimension", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="all characters that exist (or are last seen) in a given dimension",
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
    public function fetchAllCharactersFromGivenDimension(Request $paramFetcher)
    {
        $charactersId = [];
        $dimension = $paramFetcher->get('dimension');

        $dimensions = $this->rickyAndMortyService->getDimension($dimension);

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
}
