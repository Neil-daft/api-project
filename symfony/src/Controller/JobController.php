<?php

namespace App\Controller;

use App\Service\FractalService;
use App\Service\JobService;
use App\Transformers\JsonJobTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    private $jobService;
    private $fractalService;

    public function __construct(JobService $jobService, FractalService $fractalService)
    {
        $this->jobService = $jobService;
        $this->fractalService = $fractalService;
    }

    /**
     * @Route("/job/all", name="all_jobs")
     */
    public function getAllJobs()
    {
        $allJobs = $this->jobService->getAllJobs();
        $resource = new Collection($allJobs, new JsonJobTransformer(), 'jobs');

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/jobs/{id}", name="job_by_id", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function getJobById(int $id): Response
    {
        $job = $this->jobService->getOneJobById($id);
        $resource = new Item($job, new JsonJobTransformer(), 'jobs');

        return new Response($this->fractalService->getFractal()->parseIncludes('users')
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
