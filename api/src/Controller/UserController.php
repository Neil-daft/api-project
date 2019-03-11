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
    const INCLUDES_JOB = 'jobs';
    const STRING_USERS = 'users';

    /** @var UserService */
    private $userService;

    /** @var FractalService */
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
    
    /** @Route("/users", name="all_users", methods={"GET"}) */
    public function getAllEndUsers(): Response
    {
        $allUsers = $this->userService->getAllEndUsers();
        $resource = new Collection($allUsers, new JsonUserTransformer(), self::STRING_USERS);

        return $this->buildResponse($resource);
    }

    /**
     * @Route("/users/{id}", name="user_by_id", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function getUserById($id)
    {
        $user = $this->userService->getUserById($id);
        $resource = new Item($user, new JsonUserTransformer(), self::STRING_USERS);

        return $this->buildResponseWithIncludes($resource, self::INCLUDES_JOB);
    }

    /**
     * @Route("/search/users", name="search_by_email")
     * @param Request $request
     * @return Response
     */
    public function searchUsersByEmail(Request $request): Response
    {
        $email = $request->query->get('email');
        $user = $this->userService->searchUsers($email);

        if ($user === null) {
            $resource = new Item(new NullEntity(), new JsonErrorTransformer());

            return new Response($this->fractalService->getFractal()
                ->createData($resource)
                ->toJson(),
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'application/json']
            );
        }
        $resource = new Item($user, new JsonUserTransformer(), self::STRING_USERS);

        return $this->buildResponseWithIncludes($resource, self::INCLUDES_JOB);
    }

    /**
     * @Route("/users/{id}/jobs", name="users_jobs")
     * @param int $id
     * @return Response
     */
    public function getUsersJobs($id)
    {
        $user = $this->userService->getUserById($id);
        $userJobs = $user->getJob();

        $resource = new Collection($userJobs, new JsonJobTransformer(), self::INCLUDES_JOB);

        return $this->buildResponse($resource);
    }

    /**
     * @Route("/users", name="create_user", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createUser(Request $request)
    {
        $user = $this->userService->createUser($request);
        $resource = new Item($user, new JsonUserTransformer(), self::STRING_USERS);

        return $this->buildResponse($resource);
    }

    /**
     * @param Item|Collection $resource
     * @return Response
     */
    private function buildResponse($resource): Response
    {
        return new Response($this->fractalService->getFractal()
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @param string $include
     * @param Item|Collection $resource
     * @return Response
     */
    private function buildResponseWithIncludes($resource, $include): Response
    {
        return new Response($this->fractalService->getFractal()->parseIncludes($include)
            ->createData($resource)
            ->toJson(),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
