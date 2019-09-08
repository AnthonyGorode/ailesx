<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Service\Paginator;
use App\Service\PaginatorDql;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * Permet à l'administrateur d'ajouter un utilisateur
     * 
     * @Route("/admin/register", name="admin_account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, RoleRepository $repoRole){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $roles = $repoRole->findAll();

        $form->handleRequest($request); 
        
        if($form->isSubmitted() && $form->isValid()){
            $recupRole = $request->request->get('_role');
            $role = $repoRole->findOneBy(['title' => $recupRole ]);

            $userName = $user->getLastName();
            $concat = $userName .' '. $user->getFirstName();
            $hash = $encoder->encodePassword($user, $user->getHash());
            if(!empty($role)){
                $user->setHash($hash)
                     ->addUserRole($role);
            }else{
                $user->setHash($hash);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le compte de " .$concat. " a bien été créé !"
            );
            
            return $this->redirectToRoute("admin_users_index",[
                'concat' => $userName
            ]);
        }

        return $this->render('admin/user/registration.html.twig',[
            'form' => $form->createView(),
            'roles' => $roles
        ]);
    }

    /**
     * Permet d'effacer un utilisateur
     * 
     * @Route("/admin/users/{id}/delete", name="admin_user_delete")
     *
     * @return void
     */
    public function deleteUser(User $user, ObjectManager $manager){
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur <strong>{$user->getFullName()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_users_index');
    }

    /**
     * Permet d'afficher tous les utilisateurs
     * 
     * @Route("/admin/users/{column?u.id}/{order?ASC}/{concat?0}/{page<\d+>?1}", name="admin_users_index")
     */
    public function index(UserRepository $repoUser, $column, $order, $concat, $page, PaginatorDql $pagination, Request $request)
    {
        // Si il y a une recherche effectué je place la recherche dans la variable concat
        if(!empty($request->query->get('concact'))){
            $concat = $request->query->get('concact');
        }

        $pagination->setEntityClass(User::class)
                   ->setColumn($column)
                   ->setOrder($order)
                   ->setConcat($concat)
                   ->setPage($page);

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination,
            'concatenation' => $concat
        ]);
    }


}
