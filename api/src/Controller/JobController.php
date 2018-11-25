<?php

namespace App\Controller;

use App\Service\FractalService;
use App\Service\JobService;
use App\Transformers\JsonJobTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    const RESOURCE_KEY = 'jobs';

    /** @var JobService */
    private $jobService;

    /** @var FractalService */
    private $fractalService;

    /**
     * @param JobService $jobService
     * @param FractalService $fractalService
     */
    public function __construct(JobService $jobService, FractalService $fractalService)
    {
        $this->jobService = $jobService;
        $this->fractalService = $fractalService;
    }

    /**
     * @Route("/jobs/all", name="all_jobs")
     */
    public function getAllJobs()
    {
        $allJobs = $this->jobService->getAllJobs();
        $resource = new Collection($allJobs, new JsonJobTransformer(), self::RESOURCE_KEY);

        return $this->buildResponse($resource);
    }

    /**
     * @Route("/jobs/{id}", name="job_by_id", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function getJobById(int $id): Response
    {
        $job = $this->jobService->getOneJobById($id);
        $resource = new Item($job, new JsonJobTransformer(), self::RESOURCE_KEY);

        return $this->buildResponseWithIncludes($resource, ['users', 'gardeners']);
    }

    /**
     * @Route("/jobs", name="create_job", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createNewJob(Request $request)
    {
        $job = $this->jobService->createJob($request);
        $resource = new Item($job, new JsonJobTransformer(), self::RESOURCE_KEY);

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
     * @param Item|Collection $resource
     * @param string|array $includes
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
