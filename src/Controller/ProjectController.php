<?php
namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findByArchive(false);

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
