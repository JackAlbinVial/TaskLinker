<?php
namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/projects', name: 'project_index')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findByArchive(false);

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/project/add', name: 'project_add', methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $projectManager, Request $request): Response
    {
        $project     = new Project();
        $formProject = $this->createForm(ProjectType::class, $project);

        $formProject->handleRequest($request);
        if ($formProject->isSubmitted() && $formProject->isValid()) {
            $project = $formProject->getData();

            $projectManager->persist($project);
            $projectManager->flush();

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('project/project-add.html.twig', [
            'formProject' => $formProject,
        ]);
    }

    #[Route('/project/{id}', name: 'project_show')]
    public function show(ProjectRepository $projectRepository, int $id): Response
    {
        $project = $projectRepository->find($id);

        if (! $project) {
            throw $this->createNotFoundException('Project not found');
        }

        return $this->render('project/project.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(ProjectRepository $projectRepository, EntityManagerInterface $projectManager, Request $request, int $id): Response
    {
        $project = $projectRepository->find($id);

        if (! $project) {
            return $this->redirectToRoute('project_index');
        }

        $formProject = $this->createForm(ProjectType::class, $project);
        $formProject->handleRequest($request);

        if ($formProject->isSubmitted() && $formProject->isValid()) {
            $projectManager->flush();

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('project/project-edit.html.twig', [
            'formProject' => $formProject,
        ]);
    }

    #[Route('/project/{id}/delete', name: 'project_delete')]
    public function delete(EntityManagerInterface $projectManager, int $id): Response
    {
        $project = $projectManager->find(Project::class, $id);

        if (! $project) {
            return $this->redirectToRoute('project_index');
        }

        foreach ($project->getTasks() as $task) {
            $projectManager->remove($task);
        }

        $projectManager->remove($project);
        $projectManager->flush();

        return $this->redirectToRoute('project_index');
    }

}
