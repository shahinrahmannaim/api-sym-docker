<?php
namespace App\Controller\API;

use App\Entity\Department;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;

class ApiSignupController extends AbstractController
{
    #[Route('api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $departmentId = $data['department_id'] ?? null; // Get department_id from request

        // Validate the received data
        if (empty($username) || empty($email) || empty($password)) {
            return $this->json(['error' => 'All fields are required'], Response::HTTP_BAD_REQUEST);
        }

        // Check if the username or email already exists
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($existingUser) {
            return $this->json(['error' => 'Username already exists'], Response::HTTP_BAD_REQUEST);
        }

        $existingEmail = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingEmail) {
            return $this->json(['error' => 'Email already exists'], Response::HTTP_BAD_REQUEST);
        }

        // Create and persist the new user
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        // Set the default role to ROLE_USER
      

        // Set the department if provided
        if ($departmentId) {
            $department = $entityManager->getRepository(Department::class)->find($departmentId);
            if ($department) {
                $user->setDepartment($department);
            } else {
                return $this->json(['error' => 'Invalid department ID'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return $this->json(['error' => 'Department ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }
}
