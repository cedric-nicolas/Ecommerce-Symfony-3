<?php

namespace EcommerceBundle\Controller;

use EcommerceBundle\Entity\UtilisateursAdresses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EcommerceBundle\Form\UtilisateursAdressesType;
use Symfony\Component\Translation\Translator;

class PanierController extends Controller
{

    public function menuAction(Request $request){

        $session = $request->getSession();

        if(!$session->has('panier')){
            $articles = 0;
        } else {
            $articles = count($session->get('panier'));


        }

        return $this->render('EcommerceBundle:Default:panier/modulesUsed/panier.html.twig', array('articles' => $articles));

    }

    public function ajouterAction($id, Request $request){

        $session = $request->getSession();

        // has comme un isset() en PHP, typique de Symfony
        if(!$session->has('panier')){
            $session->set('panier', array());
        }

        // Récupération de la session
        $panier = $session->get('panier');

        // $panier[ID DU PRODUIT] => QTE
        if(array_key_exists($id, $panier)){
            if($request->query->get('qte') != null){
                $panier[$id] = $request->query->get('qte');
                $session->getFlashBag()->add('success', 'Quantité modifiée avec succès');

            }
        } else {
            if($request->query->get('qte') != null){
                $panier[$id] = $request->query->get('qte');
            } else {
                $panier[$id] = 1;

                $session->getFlashBag()->add('success', 'Article ajouté avec succès');
            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }

    public function supprimerAction($id, Request $request){

        $session = $request->getSession();
        $panier = $session->get('panier');

        if(array_key_exists($id, $panier)){
            unset($panier[$id]);
            $session->set('panier', $panier);
            $session->getFlashBag()->add('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('panier');

    }

    public function panierAction(Request $request)
    {
        $session = $request->getSession();

        // Détruire les infos en session :
        //$session->remove('panier'); die();

        if(!$session->has('panier')){
            $session->set('panier', array());
        }

        //var_dump($session->get('panier'));

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        return $this->render('EcommerceBundle:Default:panier/layout/panier.html.twig', array('produits' => $produits,
                                                                                                'panier' => $session->get('panier')));
    }


    public function adresseSuppressionAction($id){

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($id);

        if($this->get('security.token_storage')->getToken()->getUser() != $entity->getUtilisateur() || !$entity){
            return $this->redirect($this->generateUrl('livraison'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('livraison'));    }



    public function livraisonAction(Request $request){

        //$request->setLocale('en_EN');

        // Translation
        $message = $this->get('translator')->trans('text.hey');

        $em = $this->getDoctrine()->getManager();

        // Récupérer l'utilisateur courant
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();

        // Relier le form à la vue
        $entity = new UtilisateursAdresses();
        $form = $this->createForm(UtilisateursAdressesType::class, $entity, array(
            'entity_manager' => $this->get('doctrine.orm.entity_manager')));

        // Traitement du formulaire
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $entity->setUtilisateur($utilisateur);
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('livraison'));
            }
        }

        return $this->render('EcommerceBundle:Default:panier/layout/livraison.html.twig', array('utilisateur' => $utilisateur,
                                                                                                'form' => $form->createView(),
                                                                                                'message' => $message));
    }

    // Pas d'action quand ça ne rends pas de vue
    public function setLivraisonOnSession(Request $request){
        $session = $request->getSession();

        if(!$session->has('adresse')){
            $session->set('adresse', array());
            $adresse = $session->get('adressse');
        }

        if($request->request->get('livraison') != null && $request->request->get('facturation') != null){
            $adresse['livraison'] = $request->request->get('livraison');
            $adresse['facturation'] = $request->request->get('facturation');
        } else {
            return $this->redirect($this->generateUrl('validation'));
        }

        $session->set('adresse', $adresse);
        return $this->redirect($this->generateUrl('validation'));

    }

    public function validationAction(Request $request){

        if($request->getMethod() == 'POST'){
            $this->setLivraisonOnSession($request);
        }
        //echo '<pre>';
        //print_r($session->get('commande'));
        //echo '</pre>';

        // ACCES MULTIDIMENTIONAL OBJECTS
        //var_dump($session->all());
        //$commande = $session->get('commande');
        //var_dump($commande->getCommande()['livraison']['ville']); die();

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        // forward() Importe une méthode d'une autre classe
        $prepareCommande = $this->forward('EcommerceBundle:Commandes:prepareCommande');
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($prepareCommande->getContent());

        /*
        $session = $request->getSession();
        $adresse = $session->get('adresse');

        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));
        $livraison = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['livraison']);
        $facturation = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['facturation']);
        */

        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig', array('commande' => $commande));

    }
}
