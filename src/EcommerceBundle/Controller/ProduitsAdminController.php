<?php

namespace EcommerceBundle\Controller;

use EcommerceBundle\Entity\Media;
use EcommerceBundle\Entity\Produits;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 */
class ProduitsAdminController extends Controller
{
    /**
     * Lists all produit entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('EcommerceBundle:Produits')->findAll();

        return $this->render('EcommerceBundle:Administration:produits/layout/index.html.twig', array(
            'produits' => $produits,
        ));
    }


    /**
     * Creates a new produit entity.
     *
     */
    public function newAction(Request $request)
    {
        $produit = new Produits();
        // Création du formulaire ProduitsType
        $form = $this->createForm('EcommerceBundle\Form\ProduitsType', $produit);
        // Traitement des données recues du formulaire
        $form->handleRequest($request);

        // Si le formulaire est transmis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush($produit);

            return $this->redirectToRoute('admin_produits_show', array('id' => $produit->getId()));
        }

        return $this->render('EcommerceBundle:Administration:produits/layout/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a produit entity.
     *
     */
    public function showAction(Produits $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('EcommerceBundle:Administration:produits/layout/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     */
    public function editAction(Request $request, Produits $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('EcommerceBundle\Form\ProduitsType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_produits_edit', array('id' => $produit->getId()));
        }

        return $this->render('EcommerceBundle:Administration:produits/layout/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a produit entity.
     *
     */
    public function deleteAction(Request $request, Produits $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('admin_produits_index');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produits $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produits $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_produits_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
