<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class GardenerService
{
    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getAllGardeners(): array
    {
        $gardeners = $this->userRepository->findBy(['type' => 'gardener']);

        return $gardeners;
    }

    public function getOneGardenerById($id)
    {
        $gardener = $this->userRepository->find($id);

        return $gardener;
    }

}