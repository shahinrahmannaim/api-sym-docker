<?php

namespace App\Controller\API\adminApi;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]

class CategoryApiController extends AbstractController
{
    #[Route('/categories', name: 'api_category_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        
        // Transform categories into an array of data
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(), 
                // Add other properties if needed
            ];
        }

        return $this->json($data);
    }

    #[Route('/category/create', name: 'api_category_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = new Category();
        $category->setName($data['name']); 

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json([
            'id' => $category->getId(),
            'name' => $category->getName(), 
            'message' => 'Category created successfully.',
        ], Response::HTTP_CREATED);
    }

    #[Route('/category/{id}', name: 'api_category_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json([
            'id' => $category->getId(),
            'name' => $category->getName(), 
        ]);
    }

    #[Route('/category/edit/{id}', name: 'api_category_edit', methods: ['PUT'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $category->setName($data['name']); 
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Category updated successfully.',
            'category' => [
                'id' => $category->getId(),
                'name' => $category->getName(), 
            ],
        ]);
    }

    #[Route('/category/delete/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json([
            'message' => 'Category deleted successfully.',
        ], Response::HTTP_NO_CONTENT);
    }
}
