<?php

namespace EcommerceBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;


class getReference {

    public function __construct($securityContext, $entityManager){

        $this->securityContext = $securityContext;
        $this->em = $entityManager;

    }

    public function reference(){

        $reference = $this->em->getRepository('EcommerceBundle:Commandes')->findOneBy(array('valider' => 1),
                                                                                      array('id' => 'DESC'),
                                                                                      1,1);

        if(!$reference){
            return 1;
        } else {
            return $reference->getReference() +1;
        }

    }

}