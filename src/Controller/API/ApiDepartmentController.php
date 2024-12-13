<?php 

namespace App\Controller\API;

use App\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/departments')]
class ApiDepartmentController extends AbstractController
{
    #[Route('', name: 'api_department_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $departments = $entityManager->getRepository(Department::class)->findAll();
        
        // Transform departments into an array of data
        $data = [];
        foreach ($departments as $department) {
            $data[] = [
                'id' => $department->getId(),
                'name' => $department->getName(), // Replace with actual property
                // Add other properties as needed
            ];
        }

        return $this->json($data);
    }

    #[Route('/create', name: 'api_department_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $department = new Department();
        $department->setName($data['name']); // Replace with actual setter

        $entityManager->persist($department);
        $entityManager->flush();

        return $this->json([
            'id' => $department->getId(),
            'name' => $department->getName(), // Replace with actual property
            'message' => 'Department created successfully.',
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_department_show', methods: ['GET'])]
    public function show(Department $department): JsonResponse
    {
        return $this->json([
            'id' => $department->getId(),
            'name' => $department->getName(), // Replace with actual property
            // Add other properties as needed
        ]);
    }

    #[Route('/edit/{id}', name: 'api_department_edit', methods: ['PUT'])]
    public function edit(Request $request, Department $department, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $department->setName($data['name']); // Replace with actual setter
            // Update other properties as needed
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Department updated successfully.',
            'department' => [
                'id' => $department->getId(),
                'name' => $department->getName(), // Replace with actual property
            ],
        ]);
    }

    #[Route('/delete/{id}', name: 'api_department_delete', methods: ['DELETE'])]
    public function delete(Department $department, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($department);
        $entityManager->flush();

        return $this->json([
            'message' => 'Department deleted successfully.',
        ], Response::HTTP_NO_CONTENT);
    }
}
