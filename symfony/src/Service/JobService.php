<?php

namespace App\Service;

use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;

class JobService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var JobRepository */
    private $jobRepository;

    public function __construct(EntityManagerInterface $entityManager, JobRepository $jobRepository)
    {
        $this->entityManager = $entityManager;
        $this->jobRepository = $jobRepository;
    }

    public function getAllJobs(): array
    {
        $jobs = $this->jobRepository->findAll();

        return $jobs;
    }
}