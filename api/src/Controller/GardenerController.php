<?php

namespace App\Controller;

use App\Service\FractalService;
use App\Service\GardenerService;
use App\Transformers\JsonGardenerTransformer;
use App\Transformers\JsonJobTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GardenerController
{
    const RESOURCE_KEY = 'gardeners';
    const INCLUDES_JOBS = 'jobs';

    /** @var FractalService */
    private $fractalService;
    /** @var GardenerService  */
    private $gardenerService;

    /**
     * @param FractalService $fractalService
     * @param GardenerService $gardenerService
     */
    public function __construct(FractalService $fractalService, GardenerService $gardenerService)
    {
        $this->fractalService = $fractalService;
        $this->gardenerService = $gardenerService;
    }

    /**
     * @Route("/gardeners/all", name="all_gardeners")
     */
    public function getAllGardeners()
    {
        $gardeners = $this->gardenerService->getAllGardeners();
        $resource = new Collection($gardeners, new JsonGardenerTransformer(), self::RESOURCE_KEY);

        return $this->buildResponse($resource);
    }

    /**
     * @Route(
     *     "/gardeners/{id}",
     *     name="gardener_by_id",
     *     requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function getGardenerById(int $id)
    {
        $gardener = $this->gardenerService->getOneGardenerById($id);
        $resource = new Item($gardener, new JsonGardenerTransformer(), self::RESOURCE_KEY);

        return $this->buildResponseWithIncludes($resource, self::INCLUDES_JOBS);
    }

    /**
     * @Route("/gardeners/{id}/jobs", name="gardeners_jobs")
     * @param int $id
     * @return Response
     */
    public function getGardenersJobs($id)
    {
        $gardener = $this->gardenerService->getOneGardenerById($id);
        $jobs = $gardener->getJobs();

        $resource = new Collection($jobs, new JsonJobTransformer(), self::INCLUDES_JOBS);

        return $this->buildResponse($resource);
    }

    /**
     * @param Item|Collection $resource
     * @return Response
     */
    private function buildResponse($resource): Response
    {
        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @param string $includes
     * @param Item|Collection $resource
     * @return Response
     */
    private function buildResponseWithIncludes($resource, $includes): Response
    {
        return new Response($this->fractalService->getFractal()->parseIncludes($includes)
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
