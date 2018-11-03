<?php

namespace App\Service;

use App\Entity\Job;
use App\Entity\User;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class JobService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var JobRepository */
    private $jobRepository;

    private $userService;

    public function __construct(EntityManagerInterface $entityManager, JobRepository $jobRepository, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->jobRepository = $jobRepository;
        $this->userService = $userService;
    }

    public function getAllJobs(): array
    {
        $jobs = $this->jobRepository->findAll();

        return $jobs;
    }

    public function getOneJobById($id)
    {
        $job = $this->jobRepository->find($id);

        return $job;
    }

    public function createJob(Request $request)
    {
        $userId = $request->query->get('userId');
        $user = $this->userService->getUserById($userId);

        $job = new Job();
        $job->setTitle($request->query->get('title'));
        $job->setDescription($request->query->get('description'));
        $job->setCreatedOn(new \DateTime('now'));
        $job->setUser($user);

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }
}
