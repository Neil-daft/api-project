<?php

namespace App\Service;

use App\Repository\GardenerRepository;
use Doctrine\ORM\EntityManagerInterface;

class GardenerService
{
    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var GardenerRepository  */
    private $gardenerRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GardenerRepository $gardenerRepository
     */
    public function __construct(EntityManagerInterface $entityManager, GardenerRepository $gardenerRepository)
    {
        $this->entityManager = $entityManager;
        $this->gardenerRepository = $gardenerRepository;
    }

    public function getAllGardeners(): array
    {
        $gardeners = $this->gardenerRepository->findAll();

        return $gardeners;
    }

    public function getOneGardenerById($id)
    {
        $gardener = $this->gardenerRepository->find($id);

        return $gardener;
    }

}