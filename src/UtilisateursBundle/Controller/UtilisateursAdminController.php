<?php

namespace UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UtilisateursAdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $this->getUser() récupere l'user courant
        $utilisateurs = $em->getRepository('UtilisateursBundle:Utilisateurs')->findAll();

        return $this->render('UtilisateursBundle:Administration:utilisateurs/layout/index.html.twig', array('utilisateurs' => $utilisateurs));
    }


    public function utilisateurAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository('UtilisateursBundle:Utilisateurs')->find($id);


        $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');

        if($route == 'adminAdressesUtilisateurs'){
            return $this->render('UtilisateursBundle:Administration:utilisateurs/layout/adresses.html.twig', array('utilisateur' => $utilisateur));
        } elseif ($route == 'adminFacturesUtilisateurs'){
            return $this->render('UtilisateursBundle:Administration:utilisateurs/layout/factures.html.twig', array('utilisateur' => $utilisateur));
        } else {
            throw $this->createNotFoundException('La vue n\'existe pas');
        }



    }

}
