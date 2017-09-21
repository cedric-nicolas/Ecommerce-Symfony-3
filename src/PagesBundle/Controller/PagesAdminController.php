<?php

namespace PagesBundle\Controller;

use PagesBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Page controller.
 *
 */
class PagesAdminController extends Controller
{
    /**
     * Lists all page entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('PagesBundle:Pages')->findAll();


        return $this->render('PagesBundle:Administration:pages/layout/index.html.twig', array(
            'pages' => $pages,
        ));
    }


    public function softDelAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');
        $pages = $em->getRepository('PagesBundle:Pages')->findByRemove();


        return $this->render('PagesBundle:Administration:pages/layout/softDel.html.twig', array(
            'pages' => $pages,
        ));
    }

    public function restoreAction($id){
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');
        $page = $em->getRepository('PagesBundle:Pages')->find($id);
        $page->setDeletedAt(null);
        $em->persist($page);
        $em->flush();

        return $this->redirectToRoute('adminPages_softDel');
    }

    /**
     * Creates a new page entity.
     *
     */
    public function newAction(Request $request)
    {
        $page = new Pages();
        $form = $this->createForm('PagesBundle\Form\PagesType', $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush($page);

            return $this->redirectToRoute('adminPages_show', array('id' => $page->getId()));
        }

        return $this->render('PagesBundle:Administration:pages/layout/new.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a page entity.
     *
     */
    public function showAction(Pages $page)
    {
        $deleteForm = $this->createDeleteForm($page);

        return $this->render('PagesBundle:Administration:pages/layout/show.html.twig', array(
            'page' => $page,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing page entity.
     *
     */
    public function editAction(Request $request, Pages $page)
    {
        $deleteForm = $this->createDeleteForm($page);
        $editForm = $this->createForm('PagesBundle\Form\PagesType', $page);
        $editForm->handleRequest($request);

        /*
        // Récupérer entity avec Loggable
        $em = $this->getDoctrine()->getManager();
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($page);

        // Revenir à la version 1
        $gedmo->revert($page, 1);
        $em->persist($page);
        $em->flush();
        */


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adminPages_edit', array('id' => $page->getId()));
        }

        return $this->render('PagesBundle:Administration:pages/layout/edit.html.twig', array(
            'page' => $page,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a page entity.
     *
     */
    public function deleteAction(Request $request, Pages $page)
    {
        $form = $this->createDeleteForm($page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
        }

        return $this->redirectToRoute('adminPages_index');
    }

    /**
     * Creates a form to delete a page entity.
     *
     * @param Pages $page The page entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pages $page)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminPages_delete', array('id' => $page->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
