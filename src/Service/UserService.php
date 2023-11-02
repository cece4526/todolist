<?php

namespace App\Service;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService 
{
    private RequestStack $requestStack;
    private UserRepository $userRepo;
    private PaginatorInterface $paginator;

    public function __construct(RequestStack $requestStack, UserRepository $userRepo, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->userRepo = $userRepo;
        $this->paginator = $paginator;
    }

    public function getPaginatedUsers()
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);
        if ($page < 1) {
            throw new BadRequestHttpException('Le numéro de page doit être supérieur ou égal à 1.');
        }

        $usersQuery = $this->userRepo->findForPagination();

        return $this->paginator->paginate($usersQuery, $page, $limit);
    }
}
