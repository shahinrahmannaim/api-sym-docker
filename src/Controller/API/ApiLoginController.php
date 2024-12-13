<?php

namespace App\Controller\API;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiLoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(
        ManagerRegistry $doctrine, 
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        JWTTokenManagerInterface $jwtManager,
        LoggerInterface $logger
    ): JsonResponse 
    {
        $decoded = json_decode($request->getContent(), true);
        $email = $decoded['email'] ?? '';
        $plaintextPassword = $decoded['password'] ?? '';

        // Find the user by email
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $email]);

        // If user not found
        if (!$user) {
            $logger->info('Login failed: User not found');
            return $this->json(['result' => false, 'message' => 'User not found'], 404);
        }

       if (!$user->isVerified()) {
    $logger->info('Login failed: User not verified');
    return $this->json([
        'result' => false,
        'message' => 'Please confirm your email before logging in',
        'unverified' => true
    ], 403);
}


        // Validate the password
        if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            $logger->info('Login failed: Invalid password');
            return $this->json(['result' => false, 'message' => 'Invalid credentials'], 401);
        }

        // Add roles and generate JWT token
        $roles = $user->getRoles();
        $token = $jwtManager->create($user);

        $logger->info('Login successful: User verified and authenticated');
        return $this->json([
            'result' => true,
            'token' => $token,
            'roles' => $roles,
            'message' => 'Login successful'
        ]);
    }
}
