<?php

// App/Service/SessionIdleHandler.php
namespace App\Service;

use App\Controller\AccountController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionIdleHandler
{
    protected $session;
    protected $tokenStorage;
    protected $router;
    protected $maxIdleTime;
    protected $manager;
    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router,
        $maxIdleTime = 0,
        ObjectManager $manager
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->maxIdleTime = $maxIdleTime;
        $this->manager = $manager;
    }
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }
        if ($this->maxIdleTime > 0) {
            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();
            if ($lapse > $this->maxIdleTime && null !== $this->tokenStorage->getToken()) {
                $user = $this->tokenStorage->getToken()->getUser();
                $role = $user->getRoles();

                $user->setConnected(0);
                $this->manager->flush($user);
                $this->tokenStorage->setToken(null);
                
                
                if(empty($role)){
                    $event->setResponse(new RedirectResponse($this->router->generate('account_logout')));
                }else{
                    $event->setResponse(new RedirectResponse($this->router->generate('admin_account_logout')));
                }
            }
        }
    }
}

?>