<?php

namespace App\Controller;

use App\Manager\AuthManager;
use App\Views\UserView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @var AuthManager
     */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    #[Route('/auth/login', name: 'app_auth_login')]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(),true);
        $request->request->replace($data);
        $userDetails = $this->authManager->login($request);

        return new JsonResponse($userDetails, 200);
    }

    #[Route('/auth/register', name: 'app_auth_register')]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(),true);
        $request->request->replace($data);
        $userDetails = $this->authManager->register($request);

        return new JsonResponse($userDetails, 201);
    }

    public static function getSubscribedServices(): array
    {
        return [];
        // TODO: Implement getSubscribedServices() method.
    }
}
