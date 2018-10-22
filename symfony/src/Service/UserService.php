<?php

namespace App\Service;

use App\Entity\EndUser;
use App\Entity\User;
use App\Repository\EndUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getAllEndUsers(): array
    {
        $endusers = $this->userRepository->findAll();

        return $endusers;
    }

    public function getUserByEmail(string $email): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user;
    }

    public function getUserById($id)
    {
        $user = $this->userRepository->find($id);
        return $user;
    }
}
