<?php

namespace App\Controller;

use App\Entity\NullEntity;
use App\Service\FractalService;
use App\Service\UserService;
use App\Transformers\JsonErrorTransformer;
use App\Transformers\JsonJobTransformer;
use App\Transformers\JsonUserTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** @var UserService */
    private $userService;

    /** @var */
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

    /** @Route("/users/all", name="all_users") */
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
     * @Route("/users/{id}", name="user_by_id", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function getUserById($id)
    {
        $user = $this->userService->getUserById($id);
        $resource = new Item($user, new JsonUserTransformer(), 'users');

        return new Response($this->fractalService->getFractal()->parseIncludes('jobs')
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/search/users", name="search_by_email")
     * @param Request $request
     * @return Response
     */
    public function getEndUserByEmail(Request $request): Response
    {
        $email = $request->query->get('email');
        $user = $this->userService->getUserByEmail($email);

        if (null === $user) {
            $resource = new Item(new NullEntity(), new JsonErrorTransformer());

            return new Response($this->fractalService->getFractal()
                ->createData($resource)
                ->toJson(),
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'application/json']
            );
        }
        $resource = new Item($user, new JsonUserTransformer(), 'users');

        return new Response($this->fractalService->getFractal()->parseIncludes('jobs')
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/users/{id}/jobs")
     * @param int $id
     * @return Response
     */
    public function getUsersJobs($id)
    {
        $user = $this->userService->getUserById($id);
        $userJobs = $user->getJob();

        $resource = new Collection($userJobs, new JsonJobTransformer(), 'jobs');
        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
