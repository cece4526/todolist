<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_list')]
    public function index(userService $userService): Response
    {
        return $this->render('Users/list.html.twig',['users'=> $userService->getPaginatedUsers()]);
    }
}
