<?php

namespace Systeo\BanqueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Systeo\BanqueBundle\Entity\BanqueCompte;
use Systeo\BanqueBundle\Form\BanqueCompteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * BanqueCompte controller.
 *
 * @Route("/banquecompte")
 */
class BanqueCompteController extends Controller
{
    /**
     * Lists all BanqueCompte entities.
     *
     * @Route("/", name="banquecompte_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $paginator = $this->get('knp_paginator');

        $banqueComptes = $paginator->paginate(
                $em->getRepository('SysteoBanqueBundle:BanqueCompte')->findAll(), /* query NOT result */ 
                $request->query->getInt('page', 1),/* page number */
                10/* limit per page */
        );

        return $this->render('SysteoBanqueBundle:banquecompte:index.html.twig', array(
            'banqueComptes' => $banqueComptes,
        ));
    }

    /**
     * Creates a new BanqueCompte entity.
     *
     * @Route("/new", name="banquecompte_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $banqueCompte = new BanqueCompte();
        $form = $this->createForm('Systeo\BanqueBundle\Form\BanqueCompteType', $banqueCompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($banqueCompte);
            $em->flush();
            
            $this->addFlash('success', 'Nouveau compte bancaire ajouté avec succès.');

            return $this->redirectToRoute('banquecompte_show', array('id' => $banqueCompte->getId()));
        }

        return $this->render('SysteoBanqueBundle:banquecompte:new.html.twig', array(
            'banqueCompte' => $banqueCompte,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a BanqueCompte entity.
     *
     * @Route("/{id}", name="banquecompte_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(BanqueCompte $banqueCompte)
    {
        $deleteForm = $this->createDeleteForm($banqueCompte);

        return $this->render('SysteoBanqueBundle:banquecompte:show.html.twig', array(
            'banqueCompte' => $banqueCompte,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing BanqueCompte entity.
     *
     * @Route("/{id}/edit", name="banquecompte_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, BanqueCompte $banqueCompte)
    {
        $deleteForm = $this->createDeleteForm($banqueCompte);
        $editForm = $this->createForm('Systeo\BanqueBundle\Form\BanqueCompteType', $banqueCompte);
        $editForm->handleRequest($request);
        
        if($banqueCompte->getId() === 1){
            $this->addFlash('danger', 'Vous n\'avez pas le droit de modifier le compte caisse.');
            return $this->redirectToRoute('banquecompte_index');
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($banqueCompte);
            $em->flush();
            
            $this->addFlash('success', 'Compte bancaire modifié avec succès.');

            return $this->redirectToRoute('banquecompte_edit', array('id' => $banqueCompte->getId()));
        }

        return $this->render('SysteoBanqueBundle:banquecompte:edit.html.twig', array(
            'banqueCompte' => $banqueCompte,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a BanqueCompte entity.
     *
     * @Route("/{id}", name="banquecompte_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, BanqueCompte $banqueCompte)
    {
        $form = $this->createDeleteForm($banqueCompte);
        $form->handleRequest($request);
        
        if($banqueCompte->getId() === 1){
            $this->addFlash('danger', 'Vous n\'avez pas le droit de supprimer le compte caisse.');
            return $this->redirectToRoute('banquecompte_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($banqueCompte);
            $em->flush();
            
            $this->addFlash('success', 'Compte bancaire supprimé avec succès.');
        }

        return $this->redirectToRoute('banquecompte_index');
    }

    /**
     * Creates a form to delete a BanqueCompte entity.
     *
     * @param BanqueCompte $banqueCompte The BanqueCompte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BanqueCompte $banqueCompte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('banquecompte_delete', array('id' => $banqueCompte->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
