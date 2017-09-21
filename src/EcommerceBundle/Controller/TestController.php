<?php

namespace EcommerceBundle\Controller;

use EcommerceBundle\EcommerceBundle;
use EcommerceBundle\Form\TestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{


    public function testFormulaireAction(Request $request){

        $form = $this->createForm(TestType::class);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);

            echo '<pre>';
            print_r($form->getData());
            echo '</pre>';
            echo $form['utilisateurs']->getData()->id . '<br>';

            $form = $this->createForm(TestType::class, array('email' => 'cece@dutro.fr'));

        }

        return $this->render('EcommerceBundle:Default:test.html.twig', array('form' => $form->createView()));
    }






    /*public function ajoutAction()
    {

        $em = $this->getDoctrine()->getManager();

        $produit = new Produits();
        $produit->setCategorie('Legume');
        $produit->setDescription('La tomate se mange');
        $produit->setDisponible(1);
        $produit->setImage('https://static.mediapart.fr/etmagine/default/files/media_437483/Tomates.jpg?width=254&height=206&width_format=pixel&height_format=pixel');
        $produit->setNom('Tomate');
        $produit->setPrix('0.99');
        $produit->setTva('20%');

        $em->persist($produit);

        $produit2 = new Produits();
        $produit2->setCategorie('Legume');
        $produit2->setDescription('Le haricot se mange');
        $produit2->setDisponible(1);
        $produit2->setImage('http://img.deco.fr/029E017005098819-c1-photo-oYToxOntzOjE6InciO2k6NjcwO30%3D-haricot.jpg');
        $produit2->setNom('Haricot');
        $produit2->setPrix('0.97');
        $produit2->setTva('20%');

        $em->persist($produit2);

        $em->flush();

        $produits = $em->getRepository('EcommerceBundle:Produits')->findAll();



        return $this->render('EcommerceBundle:Default:test.html.twig', array('produits' => $produits));
    }*/

}
