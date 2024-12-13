<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/{section}', name: 'admin', defaults: ['section' => 'dashboard'])]
    #[IsGranted('ROLE_ADMIN')] 
    public function dashboard(string $section): Response
    {
        return $this->render('admin/user/userlist.html.twig', [
            'section' => $section
        ]);
    }
}
