<?php

namespace App\Controller;

use App\Service\FractalService;
use App\Service\UserService;
use App\Transformers\JsonUserTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** @var UserService */
    private $userService;

    /** @var  */
    private $fractalService;

    /**
     * @param UserService $userService
     * @param FractalService $fractalService
     */
    public function __construct(UserService $userService, FractalService $fractalService)
    {
        $this->userService = $userService;
        $this->fractalService = $fractalService;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php'
        ]);
    }

    /** @Route("/user/all", name="all_users") */
    public function getAllEndUsers(): Response
    {
        $allUsers = $this->userService->getAllEndUsers();
        $resource = new Collection($allUsers, new JsonUserTransformer(), 'user');

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }

    /**
     * @Route("/user/{id}", name="user_by_id", requirements={"page"="\d+"})
     * @param int $id
     * @return Response
     */
    public function getUserById($id)
    {
        $user = $this->userService->getUserById($id);

        $resource = new Item($user, new JsonUserTransformer(), 'user');

        return new Response($this->fractalService->getFractal()->parseIncludes('job')
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/user/{email}", name="user_by_username")
     * @param string $email
     * @return Response
     */
    public function getEndUserByEmail(string $email): Response
    {
        $user = $this->userService->getUserByEmail($email);
        $resource = new Item($user, new JsonUserTransformer(), 'user');

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

}
