<?php

namespace App\Controller;

use App\Service\FractalService;
use App\Service\JobService;
use App\Transformers\JobTransformer;
use League\Fractal\Resource\Collection;
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
        $resource = new Collection($allJobs, new JobTransformer(), 'job');

        return new Response($this->fractalService->getFractal()->parseIncludes('user')
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }


}