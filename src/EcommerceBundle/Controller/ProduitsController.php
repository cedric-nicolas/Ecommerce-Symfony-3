<?php

namespace EcommerceBundle\Controller;

use EcommerceBundle\Entity\Categories;
use EcommerceBundle\Form\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProduitsController extends Controller
{

    /*public function categorieAction($categorie){
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->byCategorie($categorie);
        $categorie = $em->getRepository('EcommerceBundle:Categories')->find($categorie);
        if (!$categorie){
            throw $this->createNotFoundException("La catégorie n'existe pas");
        }
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig', array('produits' => $produits));
    }*/

    // Param converter passé en parametre avec l'import récup objet catégorie
    public function produitsAction(Request $request, Categories $categorie = null){

        // Récupérer la session
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if($categorie != null){
            $findProduits = $em->getRepository('EcommerceBundle:Produits')->byCategorie($categorie);
        } else {
            $findProduits = $em->getRepository('EcommerceBundle:Produits')->findBy(array('disponible' => 1));
        }


        if($session->has('panier')){
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }

        $produits = $this->get('knp_paginator')->paginate($findProduits, $request->query->get('page', 1)/*page number*/, 2/*limit per page*/);

        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig', array('produits' => $produits, 'panier' => $panier));
    }


    public function presentationAction(Request $request, $id){
        $session = $request->getSession();

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);

        if($session->has('panier')){
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }

        return $this->render('EcommerceBundle:Default:produits/layout/presentation.html.twig', array('produit' => $produit, 'panier' => $panier));
    }


    public function rechercheAction(){

        $form = $this->createForm(RechercheType::class);

        return $this->render('EcommerceBundle:Default:recherche/modulesUsed/recherche.html.twig', array('form' => $form->createView()));
    }


    public function rechercheTraitementAction(Request $request){

        $form = $this->createForm(RechercheType::class);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $chaine = $form['recherche']->getData();

            $em = $this->getDoctrine()->getManager();
            $produits = $em->getRepository('EcommerceBundle:Produits')->recherche($chaine);
        } else {
            $this->createNotFoundException("La page n'existe pas.");
        }

        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig', array('produits' => $produits));

    }


}
