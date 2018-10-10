<?php

namespace App\Service;

use App\Entity\EndUser;
use App\Repository\EndUserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EndUserRepository */
    private $endUserRepository;

    public function __construct(EntityManagerInterface $entityManager, EndUserRepository $endUserRepository)
    {
        $this->entityManager = $entityManager;
        $this->endUserRepository = $endUserRepository;
    }

    public function getAllEndUsers(): array
    {
        $endusers = $this->endUserRepository->findAll();

        return $endusers;
    }

    public function getEndUserByFirstName(string $firstName): ?EndUser
    {
        $endUser = $this->endUserRepository->findOneBy(['firstName' => $firstName]);

        return $endUser;
    }

}