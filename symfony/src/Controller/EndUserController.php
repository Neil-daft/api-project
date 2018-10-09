<?php

namespace App\Controller;

use App\Entity\EndUser;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EndUserController extends AbstractController
{
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

    /**
     * @Route("/end/user/all", name="all_end_users")
     * @return Response
     */
    public function getAllEndUsers(): Response
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());

        $allEndUsers = $this
            ->getDoctrine()
            ->getRepository(EndUser::class)
            ->findAll();

        $resource = new Collection($allEndUsers, function (EndUser $endUser) {
            return [
                'id' => (int) $endUser->getId(),
                'first_name' => $endUser->getFirstName(),
                'last_name' => $endUser->getLastName(),
                'email' => $endUser->getEmail(),
                'phone' => $endUser->getPhoneNumber()
            ];
        });

        return new Response($fractal->createData($resource)->toJson(), 200, ['content-type' => 'application/json']);
    }

    /**
     * @Route("/end/user/{firstName}", name="end_user_by_first_name")
     * @param string $firstName
     * @return Response
     */
    public function getEndUserByFirstName($firstName): Response
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());

        $endUser = $this
            ->getDoctrine()
            ->getRepository(EndUser::class)
            ->findOneBy(['firstName' => $firstName]);

        $resource = new Item($endUser, function (EndUser $endUser) {
            return [
                'id' => (int) $endUser->getId(),
                'first_name' => $endUser->getFirstName(),
                'last_name' => $endUser->getLastName(),
                'email' => $endUser->getEmail(),
                'phone' => $endUser->getPhoneNumber(),
                'links' => [
                    'self' => '/end/user/' . $endUser->getFirstName()
                ]
            ];
        });

        return new Response($fractal->createData($resource)->toJson(), 200, ['content-type' => 'application/json']);
    }
}
