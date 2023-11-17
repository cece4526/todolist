<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskService 
{
    private RequestStack $requestStack;
    private TaskRepository $taskRepo;
    private PaginatorInterface $paginator;

    public function __construct(RequestStack $requestStack, TaskRepository $taskRepo, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->taskRepo = $taskRepo;
        $this->paginator = $paginator;
    }

    public function getPaginatedTasks(?User $user = null)
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);
        if ($page < 1) {
            throw new BadRequestHttpException('Le numéro de page doit être supérieur ou égal à 1.');
        }

        $tasksQuery = $this->taskRepo->findForPagination($user);

        return $this->paginator->paginate($tasksQuery, $page, $limit);
    }
}
