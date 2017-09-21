<?php

namespace EcommerceBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;


class RedirectionListener {

    public function __construct(ContainerInterface $container, Session $session){

        $this->session = $session;
        $this->router = $container->get('router');
        $this->securityContext = $container->get('security.authorization_checker');

    }

    // Exécute sur chaque page si on se trouve sur la page livraison ou validation
    public function onKernelRequest(GetResponseEvent $event){

        $route = $event->getRequest()->attributes->get('_route');

        // Récupere la route courante
        if($route == 'livraison' || $route == 'validation'){
            if ($this->session->has('panier')){
                if (count($this->session->get('panier')) == 0 ){
                    $event->setResponse(new RedirectResponse($this->router->generate('panier')));
                }
            }


            // Recup user courant verfie que c'est un objet pour savoir si il est connecté
            if(!$this->securityContext->isGranted('ROLE_USER')){
                $this->session->getFlashBag()->add('notification', 'Vous devez vous identifier');
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
            }
        }
    }

}