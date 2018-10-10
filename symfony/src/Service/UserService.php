<?php

namespace App\Service;

use App\Repository\EndUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EndUserRepository */
    private $endUserRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param EndUserRepository $endUserRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EndUserRepository $endUserRepository)
    {
        $this->entityManager = $entityManager;
        $this->endUserRepository = $endUserRepository;
    }

    public function getAllEndUsers(): ArrayCollection
    {
        $endusers = $this->endUserRepository->findAll();

        return new ArrayCollection($endusers);
    }

    /**
     * @return \App\Entity\EndUser|null
     */
    public function getEndUserByFirstName(string $firstName)
    {
        $endUser = $this->endUserRepository->findOneBy(['firstName' => $firstName]);

        return $endUser;
    }

}