<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/tasks')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_list')]
    public function index(TaskService $taskService): Response
    {
        return $this->render('Tasks/list.html.twig',['tasks'=> $taskService->getPaginatedTasks()]);
    }

    #[Route('/new', name: 'task_create', methods: ['GET', 'POST'])]
    public function new(Request $request, TaskRepository $taskRepository,EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        //I create my form for new task
        $task = new Task();
    // $this->denyAccessUnlessGranted('TASK_CREATE', $task);
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser();
        // dd($form, $request);
        //the form request is processed
        $form->handleRequest($request);

        if ($user === null) {
            
            $this->addFlash('danger', 'Veuillez vous connecter pour ajouter un task');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new DateTimeImmutable();
            $task->setCreatedAt($now);
            $task->setTitle(strtoupper($task->getTitle()));
            $task->setUser($user);
            $em->persist($task);
            $em->flush();

            $taskRepository->save($task, true);
            $this->addFlash(
                'success',
                'La tâche a bien été enregistré'
            );

            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/create.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }

    #[Route('/edition/{id}', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskRepository $taskRepository,EntityManagerInterface $em): Response
    {
        $task = new Task;
        $taskId = $request->attributes->get('id');
        $task = $taskRepository->findTaskWithUserAndCategory($taskId);


        // $this->denyAccessUnlessGranted('task_EDIT', $task);
        //I create my form for edit task
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);

        if ($user === null) {
            
            $this->addFlash('danger', 'Veuillez vous connecter pour modifier une tâche');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        //I check if I have a form and that it is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new DateTimeImmutable();
            $task->setUpdateAt($now);
            $task->setTitle(strtoupper($task->getTitle()));
            $task->setUser($user);
            
            $em->persist($task);
            $em->flush();

            $taskRepository->save($task, true);
            $this->addFlash(
                'success',
                'La tâche a bien été enregistré'
            );

            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        $task->setIsDone(!$task->isIsDone());
        $em->flush($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/delete/{id}', name: 'task_delete')]
    public function delete( Task $task, TaskRepository $taskRepository): Response
    {
        // $this->denyAccessUnlessGranted('TRICK_DELETE', $trick);
        $taskRepository->remove($task, true);
        $this->addFlash('success', sprintf('La tâche a bien eté supprimée'));
        return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
    }
}
