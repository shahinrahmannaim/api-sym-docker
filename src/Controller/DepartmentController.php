<?php

// src/Controller/DepartmentController.php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/department')]
class DepartmentController extends AbstractController
{
    #[Route('/department', name: 'department')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $departments = $entityManager->getRepository(Department::class)->findAll();

        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/create', name: 'department_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'department_edit')]
    public function edit(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/edit.html.twig', [
            'form' => $form->createView(),
            'department' => $department,
        ]);
    }

    #[Route('/delete/{id}', name: 'department_delete')]
    public function delete(Department $department, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($department);
        $entityManager->flush();

        return $this->redirectToRoute('department_index');
    }
}


