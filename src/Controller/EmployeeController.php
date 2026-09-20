<?php
namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{
    #[Route('/employees', name: 'employee_index')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employe/employes.html.twig', [
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/employee/{id}/edit', name: 'employee_edit', methods: ['GET', 'POST'])]
    public function edit(EmployeeRepository $employeeRepository, EntityManagerInterface $employeeManager, Request $request, int $id): Response
    {
        $employee = $employeeRepository->find($id);

        if (! $employee) {
            return $this->redirectToRoute('employee_index');
        }

        $formEmployee = $this->createForm(EmployeeType::class, $employee);
        $formEmployee->handleRequest($request);

        if ($formEmployee->isSubmitted() && $formEmployee->isValid()) {
            $employeeManager->flush();

            return $this->redirectToRoute('employee_index');
        }

        return $this->render('employe/employe.html.twig', [
            'formEmployee' => $formEmployee,
            'employee'     => $employee,
        ]);
    }

    #[Route('/employee/{id}/delete', name: 'employee_delete')]
    public function delete(EntityManagerInterface $employeeManager, int $id): Response
    {
        $employee = $employeeManager->find(Employee::class, $id);

        if (! $employee) {
            return $this->redirectToRoute('employee_index');
        }

        foreach ($employee->getTasks() as $task) {
            $task->setEmployees(null);
        }

        $employeeManager->remove($employee);
        $employeeManager->flush();

        return $this->redirectToRoute('employee_index');
    }
}
