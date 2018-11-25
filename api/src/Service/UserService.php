<?php

namespace App\Service;

use App\Entity\EndUser;
use App\Entity\User;
use App\Repository\EndUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return array
     */
    public function getAllEndUsers(): array
    {
        $endusers = $this->userRepository->findAll();

        return $endusers;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getUserById($id)
    {
        $user = $this->userRepository->find($id);

        return $user;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function searchUsers($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user;
    }

    /**
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        $user = new User();
        $user->setEmail($request->query->get('email'));
        $user->setRoles($request->query->get('roles'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->query->get('password')));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
