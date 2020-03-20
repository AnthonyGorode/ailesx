<?php

namespace App\Controller;

use App\Service\StatsAilesx;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils,StatsAilesx $statsService)
    {
        // $statsService->setGlobalVariableOnlyGroupByMySql();

        $error = $utils->getLastAuthenticationError();

        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de mettre à jour l'attribut connected de l'entité User
     * Puis appel la route logout pour se déconnecter
     * 
     * @Route("/admin/preLogout", name="admin_account_preLogout")
     *
     * @return void
     */
    public function preLogout(ObjectManager $manager){
        $user = $this->getUser();
        $user->setConnected(0);
        $manager->flush($user);

        return $this->redirectToRoute('admin_account_logout');
    }

    /**
     * Permet de se déconnecter en admin
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout(){
        

    }
}
