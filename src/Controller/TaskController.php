<?php
namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/project/{projectId}/task/add', name: 'task_add', methods: ['GET', 'POST'])]
    public function add(ProjectRepository $projectRepository, EntityManagerInterface $taskManager, Request $request, int $projectId): Response
    {
        $project = $projectRepository->find($projectId);

        if (! $project) {
            return $this->redirectToRoute('project_index');
        }

        $task = new Task();
        $task->setProject($project);
        $formTask = $this->createForm(TaskType::class, $task);

        $formTask->handleRequest($request);
        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $taskManager->persist($task);
            $taskManager->flush();

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('task/tache-add.html.twig', [
            'formTask' => $formTask,
            'project'  => $project,
        ]);
    }

    #[Route('/task/{id}', name: 'task_show', methods: ['GET'])]
    public function show(TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->find($id);

        if (! $task) {
            throw $this->createNotFoundException('Task not found');
        }

        return $this->render('task/tache.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(TaskRepository $taskRepository, EntityManagerInterface $taskManager, Request $request, int $id): Response
    {
        $task = $taskRepository->find($id);

        if (! $task) {
            return $this->redirectToRoute('project_index');
        }

        $formTask = $this->createForm(TaskType::class, $task);
        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $taskManager->flush();

            return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
        }

        return $this->render('task/tache.html.twig', [
            'formTask' => $formTask,
            'task'     => $task,
            'is_edit'  => true,
        ]);
    }
}
