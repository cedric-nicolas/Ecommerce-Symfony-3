<?php

namespace EcommerceBundle\Controller;

use EcommerceBundle\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommandesController extends Controller{


    public function facture(Request $request){

        // Stockage dans le tableau commande tous ce dont on a besoin pour faire une facture
        $em = $this->getDoctrine()->getManager();
        $generator = $this->get('fos_user.util.token_generator');
        $session = $request->getSession();
        $adresse = $session->get('adresse');
        $panier = $session->get('panier');
        $commande = array();
        $totalHT = 0;
        $totalTVA = 0;

        $facturation = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['facturation']);
        $livraison = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['livraison']);
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        foreach ($produits as $produit){
            $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
            $prixTTC = ($produit->getPrix() * $panier[$produit->getId()] / $produit->getTva()->getMultiplicate());
            $totalHT += $prixHT;

            if(!isset($commande['tva']['%'.$produit->getTva()->getValeur()])){
                $commande['tva']['%'.$produit->getTva()->getValeur()] = round($prixTTC - $prixHT,2);
            } else {
                $commande['tva']['%'.$produit->getTva()->getValeur()] += round($prixTTC - $prixHT,2);
            }

            $totalTVA += round($prixTTC - $prixHT,2);

            $commande['produit'][$produit->getId()] = array('reference' => $produit->getNom(),
                                                            'quantite' => $panier[$produit->getId()],
                                                            'prixHT' => round($produit->getPrix(),2),
                                                            'prixTTC' => round($produit->getPrix() / $produit->getTva()->getMultiplicate(),2)
                                                            );


        }

        $commande['livraison'] = array('prenom' => $livraison->getPrenom(),
                                       'nom' => $livraison->getNom(),
                                       'telephone' => $livraison->getTelephone(),
                                       'adresse' => $livraison->getAdresse(),
                                       'cp' => $livraison->getCp(),
                                       'ville' => $livraison->getVille(),
                                       'pays' => $livraison->getPays(),
                                       'complement' => $livraison->getComplement()
                                        );

        $commande['facturation'] = array('prenom' => $facturation->getPrenom(),
                                        'nom' => $facturation->getNom(),
                                        'telephone' => $facturation->getTelephone(),
                                        'adresse' => $facturation->getAdresse(),
                                        'cp' => $facturation->getCp(),
                                        'ville' => $facturation->getVille(),
                                        'pays' => $facturation->getPays(),
                                        'complement' => $facturation->getComplement()
                                    );

        $commande['prixHT'] = round($totalHT,2);
        $commande['prixTTC'] = round($totalHT + $totalTVA,2);
        $commande['token'] = $generator->generateToken();;




        // Retour du tableau
        return $commande;




    }



    public function prepareCommandeAction(Request $request){
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        //$session->remove('commande');
        //var_dump($session->all()); die();


        if(!$session->has('commande')) {
            $commande = new Commandes();
        } else {
            $commande = $session->get('commande');
            //var_dump($commande->getCommande()['livraison']['ville']); die();
            $commande = $em->getRepository('EcommerceBundle:Commandes')->find($commande->getId());
        }


        $commande->setDate(new \DateTime());
        // Récupérer l'utilisateur courant
        $commande->setUtilisateur($this->get('security.token_storage')->getToken()->getUser());
        $commande->setValider(0);
        $commande->setReference(0);
        // On passe la commande avec tous ses objets
        $commande->setCommande($this->facture($request));

        if(!$session->has('commande')){
            $em->persist($commande);
            $session->set('commande', $commande);
        }

        $em->flush();

        return new Response($commande->getId());

    }

    public function validationCommandeAction($id, Request $request){


        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($id);


        if(!$commande || $commande->getValider() == 1){
            throw $this->createNotFoundException('La commande n\'existe pas');
        }

        $commande->setValider(1);
        // Utilisation du service qui gère les référence et ajouter à chaque commande +1
        $commande->setReference($this->container->get('setNewReference')->reference());
        $em->flush();

        $session = $request->getSession();
        $session->remove('adresse');
        $session->remove('panier');
        $session->remove('commande');

        // ici le mail
        $message = \Swift_Message::newInstance()
            ->setSubject('Validation de votre commande')
            ->setFrom(array('cedric.nicolas.cnr@gmail.com' => 'Cédric'))
            ->setTo($commande->getUtilisateur()->getEmailCanonical())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->renderView('EcommerceBundle:Default:swiftLayout/validationCommande.html.twig', array('utilisateur' => $commande->getUtilisateur())));

            $this->get('mailer')->send($message);

        $this->get('session')->getFlashBag()->add('success','Votre commande est validé avec succès');

        return $this->redirect($this->generateUrl('factures'));
    }

}
