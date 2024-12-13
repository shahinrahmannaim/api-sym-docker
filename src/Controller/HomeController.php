<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request,RecipeRepository $repository, EntityManagerInterface $em ,UserPasswordHasherInterface $hasher, Security $security): Response
    {
        // $user = new User();
        // $user->setEmail('shahin@gmail.fr')
        // ->setUsername('shahin')
        // ->setPassword($hasher->hashPassword($user, '0000'))
        // ->setRoles(['ROLE_ADMIN']);
        // $em->persist($user);
        // $em->flush();
        
        $page = $request->query->getInt( 'page', 1);
      
      $recipes = $repository->paginateRecipes($page);
  
      return
      $this->render ('home/index.html.twig', [
      'recipes' => $recipes
      ]);
        
      
    }

    
}
