<?php

namespace App\Controller\Admin;



use App\Entity\User;
use App\Form\RecipeType;
use App\Form\UserFormType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Json;


#[Route('/admin/users', name:'admin_user.')]
#[IsGranted('ROLE_ADMIN')]

class UserController extends AbstractController
{
   
    #[Route('/', name: 'index')]
    public function index(Request $request, UserRepository $repository ): Response
    {
      $page = $request->query->getInt( 'page', 1);
      
      $users = $repository->paginateRecipes($page);
  
      return
      $this->render ('admin/user/userlist.html.twig', [
        'section' => 'user',
        'recipes' => $users
      ]);
        
    }
    #[Route('/create', name:'create')]
    public function create( Request $request, EntityManagerInterface $em){
      $user = new User();
      $form = $this->createForm(UserFormType::class,$user);
      $form->handleRequest($request);  
      
      if($form->isSubmitted() && $form->isValid()){
      
        $em->persist($user);
        $em->flush();
        $this->addFlash('success','La utilisateur a bien été crée');
        return $this->redirectToRoute('admin_user.index'); 
      }
      
      return $this->render('admin/user/create.html.twig',[
        
        'user'=>$user
      ]);
    }
   
    
    #[Route('edit/{id}', name:'edit', methods:['GET','POST'],requirements:['id'=>Requirement::DIGITS])]
    
    public function edit(User $user , Request $request, EntityManagerInterface $em){
     
      $form = $this->createForm(RecipeType::class,$user);
      $form->handleRequest($request);  
      
      if($form->isSubmitted() && $form->isValid()) {
          $em->flush();
        $this->addFlash('success','La user a bien ete modifie');
          return $this->redirectToRoute('admin.recipe.index');
    }      
      return $this->render('admin/user/edit.html.twig',[
        'user'=>$user,
        'form'=>$form
      ]);
    }
   

    #[Route('/{id}', name:'delete', methods:['DELETE'],requirements:['id'=>Requirement::DIGITS])]

    public function delete(User $user, EntityManagerInterface $em){
      
      $em->remove($user);
      $em->flush();

      $this->addFlash('success','the user has been deleted !!!');
 
      return $this->redirectToRoute('admin_user.index');

      
    
      
    }

    
}
