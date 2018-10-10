<?php

namespace App\Controller;

use App\Service\FractalService;
use App\Service\UserService;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EndUserController extends AbstractController
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
     * @Route("/end/user", name="end_user")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EndUserController.php',
        ]);
    }

    /** @Route("/end/user/all", name="all_end_users") */
    public function getAllEndUsers(): Response
    {
        $allEndUsers = $this->userService->getAllEndUsers();
        $resource = new Collection($allEndUsers, new UserTransformer());

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }

    /**
     * @Route("/end/user/{firstName}", name="end_user_by_first_name")
     * @param string $firstName
     * @return Response
     */
    public function getEndUserByFirstName(string $firstName): Response
    {
        $endUser = $this->userService->getEndUserByFirstName($firstName);
        $resource = new Item($endUser, new UserTransformer());

        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
