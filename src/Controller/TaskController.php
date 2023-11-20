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
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('Tasks/list.html.twig', ['tasks' => $taskService->getPaginatedTasks()]);
    }

    #[Route('/finished', name: 'task_list_finished')]
    public function finishedTaskList(TaskService $taskService): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('Tasks/list.html.twig', ['tasks' => $taskService->getPaginatedTasks()]);
    }

    #[Route('/new', name: 'task_create', methods: ['GET', 'POST'])]
    public function new(Request $request, TaskRepository $taskRepository, EntityManagerInterface $em): Response
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
            $this->addFlash('danger', sprintf('Veuillez vous identifier'));
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
    public function edit(Request $request, TaskRepository $taskRepository, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $taskId = $request->attributes->get('id');
        $task = $taskRepository->findTaskWithUser($taskId);

        if ($task->getUser() !== null){
            if ( $this->getUser()->getId() !== $task->getUser()->getId()) {
                $this->addFlash('danger', sprintf('La tâche ' .$task->getTitle() .'ne pe pas etre modifier par vous'));
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        }
        if ($task->getUser() === null && $this->getUser()->getRoles()[0] === "ROLE_USER") {
            $this->addFlash('danger', sprintf('La tâche ' .$task->getTitle() .'ne pe pas etre modifier par vous'));
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        // $this->denyAccessUnlessGranted('task_EDIT', $task);
        //I create my form for edit task
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser();
        //the form request is processed
        $form->handleRequest($request);
        // dd($task->getUser()->getId());
        if ($user === null) {
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

    #[Route('/toggle/{id}', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        if ($this->getUser()->getId() === $task->getUser()->getId() || $this->getUser()->getRoles()[0] == "ROLE_ADMIN") {

            if ($task->isIsDone() === true) {
                $this->addFlash('danger', sprintf('La tâche %s n\'a pas été marquée comme faite.', $task->getTitle()));
                $task->setIsDone(!$task->isIsDone());
                $em->flush();
                return $this->redirectToRoute('task_list');
            }
            $task->setIsDone(!$task->isIsDone());
            $em->flush();

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

            return $this->redirectToRoute('task_list');
        }
        

        return $this->redirectToRoute('task_list');
    }

    #[Route('/delete/{id}', name: 'task_delete')]
    public function delete(Task $task, TaskRepository $taskRepository): Response
    {
        // $this->denyAccessUnlessGranted('TRICK_DELETE', $trick);

        if ($this->getUser()->getId() === $task->getUser()->getId() || $this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
            $taskRepository->remove($task, true);
            $this->addFlash('success', sprintf('La tâche a bien eté supprimée'));
        }

        return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
    }
}
