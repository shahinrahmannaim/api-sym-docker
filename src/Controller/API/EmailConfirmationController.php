<?php
// src/Controller/API/EmailConfirmationController.php
namespace App\Controller\API;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailConfirmationController extends AbstractController
{
    #[Route('/email/confirm', name: 'api_confirm', methods: ['GET'])]
    public function confirmEmail(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $email = $request->query->get('email');

        if (!$email) {
            return $this->json(['error' => 'No email provided'], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Update the user to confirm their email
        if ($user->isVerified()) {
            return $this->json(['message' => 'Email already confirmed'], Response::HTTP_OK);
        }

        $user->setVerified(true); // Set user as verified
        $entityManager->flush();

        return $this->json(['message' => 'Email confirmed successfully'], Response::HTTP_OK);
    }
}
