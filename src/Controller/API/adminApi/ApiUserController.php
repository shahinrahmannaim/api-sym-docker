<?php
// src/Controller/API/adminApi/ApiUserController.php
namespace App\Controller\API\adminApi;

use App\Entity\Department;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin')] 
#[IsGranted('ROLE_ADMIN')] 
class ApiUserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/users', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $userData = [];
        foreach ($users as $user) {
            $department = $user->getDepartment();
            $roles = $user->getRoles();
            
            if (empty($roles)) {
                $roles = ['ROLE_USER'];
            }

            $userData[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'department' => $department ? [
                    'id' => $department->getId(),
                    'name' => $department->getName(),
                ] : null,
                'roles' => !empty($roles) ? $roles : null,
            ];
        }

        return new JsonResponse($userData, Response::HTTP_OK);
    }

    #[Route('/user/create', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $departmentId = $data['department_id'] ?? null;
        $roles = $data['roles'] ?? ['ROLE_USER']; // Default role if not provided

        // Validate required fields
        if (empty($username) || empty($email) || empty($password) || empty($departmentId)) {
            return $this->json(['error' => 'All fields are required'], Response::HTTP_BAD_REQUEST);
        }

        // Check for existing username or email
        if ($this->userRepository->findOneBy(['username' => $username])) {
            return $this->json(['error' => 'Username already exists'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->userRepository->findOneBy(['email' => $email])) {
            return $this->json(['error' => 'Email already exists'], Response::HTTP_BAD_REQUEST);
        }

        // Create the new user
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($passwordHasher->hashPassword($user, $password));
        $user->setRoles($roles);
        $user->setVerified(false); // Set user as unverified initially

        // Set the department
        $department = $this->entityManager->getRepository(Department::class)->find($departmentId);
        if ($department) {
            $user->setDepartment($department);
        } else {
            return $this->json(['error' => 'Invalid department ID'], Response::HTTP_BAD_REQUEST);
        }

        // Persist and flush the new user
        $this->entityManager->persist($user);
        try {
            $this->entityManager->flush();

            // Send the confirmation email
            $this->sendConfirmationEmail($user->getEmail());

            return $this->json(['message' => 'User created successfully. Confirmation email sent.'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to create user: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function sendConfirmationEmail(string $email): void
    {
        $confirmationLink = 'http://localhost:8000/email/confirm?email=' . urlencode($email);
        $emailMessage = (new Email())
            ->from('no-reply@yourdomain.com')
            ->to($email)
            ->subject('Email Confirmation')
            ->html('<p>Please confirm your registration by clicking <a href="' . $confirmationLink . '">here</a>.</p>');

        $this->mailer->send($emailMessage);
    }

    #[Route('/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $department = $user->getDepartment();
        $roles = $user->getRoles();

        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'department' => $department ? [
                'id' => $department->getId(),
                'name' => $department->getName(),
            ] : null,
            'roles' => !empty($roles) ? $roles : ['ROLE_USER'], // Default role if empty
        ];

        return new JsonResponse($userData, Response::HTTP_OK);
    }

    #[Route('/user/edit/{id}', name: 'edit_user', methods: ['PUT'])]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }
    
        $data = json_decode($request->getContent(), true);
    
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $departmentId = $data['department_id'] ?? null; // Get department_id from request
        $roles = $data['roles'] ?? null; // Get roles from request
    
        // Validate the received data
        if (empty($username) || empty($email)) {
            return $this->json(['error' => 'Username and email are required'], Response::HTTP_BAD_REQUEST);
        }
    
        // Check for existing username/email (excluding current user)
        $existingUser = $this->userRepository->findOneBy(['username' => $username]);
        if ($existingUser && $existingUser->getId() !== $user->getId()) {
            return $this->json(['error' => 'Username already exists'], Response::HTTP_BAD_REQUEST);
        }
    
        $existingEmail = $this->userRepository->findOneBy(['email' => $email]);
        if ($existingEmail && $existingEmail->getId() !== $user->getId()) {
            return $this->json(['error' => 'Email already exists'], Response::HTTP_BAD_REQUEST);
        }
    
        // Update user data
        $user->setUsername($username);
        $user->setEmail($email);
    
        if (!empty($password)) {
            $user->setPassword($passwordHasher->hashPassword($user, $password));
        }
    
        // Update roles if provided
        if (!empty($roles)) {
            $user->setRoles($roles);
        }
    
        // Update department if provided
        if ($departmentId) {
            $department = $this->entityManager->getRepository(Department::class)->find($departmentId);
            if ($department) {
                $user->setDepartment($department);
            } else {
                return $this->json(['error' => 'Invalid department ID'], Response::HTTP_BAD_REQUEST);
            }
        }
    
        $this->entityManager->flush();
    
        return $this->json(['message' => 'User updated successfully'], Response::HTTP_OK);
    }
    
    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User deleted successfully.'], Response::HTTP_OK);
    }
}


