<?php

namespace PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Pages1Controller extends Controller
{

    public function menuAction(){
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('PagesBundle:Pages')->findAll();

        return $this->render('PagesBundle:Default:pages/modulesUsed/menu.html.twig', array('pages' => $pages));
    }

    public function pageAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('PagesBundle:Pages')->findOneBySlug($slug);
        // ou findOneBy(array('slug' => $slug));

        if(!$page){
            throw  $this->createNotFoundException("La page n'existe pas");
        }

        return $this->render('PagesBundle:Default:pages/layout/pages.html.twig', array('page' => $page));
    }
}
