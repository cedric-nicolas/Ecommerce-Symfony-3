<?php

namespace UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UtilisateursController extends Controller
{

    public function villesAction(Request $request, $cp){

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $villeCopePostal = $em->getRepository('UtilisateursBundle:Villes')->FindBy(array('villeCodePostal' => $cp));

            if($villeCopePostal){
                $villes = array();
                foreach ($villeCopePostal as $ville){
                    $villes[] = $ville->getVilleNom();
                }
            } else {
                $ville = null;
            }

            // Retourner un valeur en Json
            $response = new JsonResponse();

            return $response->setData(array('ville' => $villes));
        } else {
            throw new Exception('Erreur');
        }

    }

    public function facturesAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $this->getUser() récupere l'user courant
        $factures = $em->getRepository('EcommerceBundle:Commandes')->byFacture($this->getUser());

        return $this->render('UtilisateursBundle:Default:layout/facture.html.twig', array('factures' => $factures));
    }


    public function facturesPDFAction($id, Request $request){

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        // getUser() = current user
        $facture = $em->getRepository('EcommerceBundle:Commandes')->findOneBy(array('utilisateur' => $this->getUser(),
                                                                                     'valider' => 1,
                                                                                     'id' => $id));
        if(!$facture){
            $session->getFlashBag()->add('error', 'Une erreur est survenue');
            return $this->redirect($this->generateUrl('factures'));
        }

        $this->container->get('setNewFacture')->facture($facture)->Output('Facture.pdf');

        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');

        return $response;

    }

}
