<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @Route("/users", name="get_users")
     */
    public function getUsers(): JsonResponse
    {
        // Cambiar el método llamado a fetchUsers(, que es el correcto en ApiService
        $users = $this->apiService->fetchUsers();

        return new JsonResponse($users);
    }
}

