<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateUser;
use App\Response\UserResponse;
use App\Service\UserService;
use Fig\Http\Message\RequestMethodInterface as RequestMethod;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    public function __construct(protected UserService $userService)
    {
    }

    #[Route(
        path: '/api/users',
        name: 'api_users_create',
        methods: [RequestMethod::METHOD_POST],
    )]
    public function createHandler(Request $request): JsonResponse
    {
        $jsonData = $request->getPayload()->all();
        $createUserDto = new CreateUser($jsonData['name'], $jsonData['email']);

        $user = $this
            ->userService
            ->create($createUserDto)
        ;

        return $this->json(new UserResponse($user), StatusCode::STATUS_CREATED);
    }

    #[Route(
        path: '/api/users',
        name: 'api_users_list',
        methods: [RequestMethod::METHOD_GET],
    )]
    public function listHandler(Request $request): JsonResponse
    {
        $nameCriteria = $request->get('nameCriteria', null);
        $emailCriteria = $request->get('emailCriteria', null);

        $users = $this
            ->userService
            ->list($nameCriteria, $emailCriteria)
        ;

        $response = [];
        foreach ($users as $user) {
            $response[] = new UserResponse($user);
        }

        return $this->json($response);
    }

    #[Route(
        path: '/api/users/{id}',
        name: 'api_users_get',
        requirements: ['id' => '\d+'],
        methods: [RequestMethod::METHOD_GET],
    )]
    public function getHandler(int $id): JsonResponse
    {
        $user = $this
            ->userService
            ->getById($id)
        ;

        return $this->json(new UserResponse($user));
    }
}
