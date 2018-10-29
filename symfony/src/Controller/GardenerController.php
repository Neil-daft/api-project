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
    /** @var FractalService */
    private $fractalService;
    /** @var GardenerService  */
    private $gardenerService;

    /**
     * @param $fractalService
     * @param $gardenerService
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
        $resource = new Collection($gardeners, new JsonGardenerTransformer(), 'gardeners');

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route(
     *     "/gardeners/{id}",
     *     name="gardener_by_id",
     *     requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function getGardenerById($id)
    {
        $gardener = $this->gardenerService->getOneGardenerById($id);
        $resource = new Item($gardener, new JsonGardenerTransformer(), 'gardeners');

        return new Response($this->fractalService->getFractal()->parseIncludes('jobs')
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/gardeners/{id}/jobs", name="gardeners_jobs")
     */
    public function getGardenersJobs($id)
    {
        $gardener = $this->gardenerService->getOneGardenerById($id);
        $jobs = $gardener->getJobs();

        $resource = new Collection($jobs, new JsonJobTransformer(), 'jobs');

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
