<?php

namespace Systeo\DepenseBundle\Controller;

use Systeo\DepenseBundle\Entity\DepenseCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Depensecategory controller.
 *
 * @Route("depensecategory")
 */
class DepenseCategoryController extends Controller
{
    /**
     * Lists all depenseCategory entities.
     *
     * @Route("/", name="depensecategory_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $paginator = $this->get('knp_paginator');

        $depenseCategories = $paginator->paginate(
                $em->getRepository('SysteoDepenseBundle:DepenseCategory')->findAll(), /* query NOT result */ 
                $request->query->getInt('page', 1),/* page number */
                10/* limit per page */
        );

        return $this->render('SysteoDepenseBundle:depensecategory:index.html.twig', array(
            'depenseCategories' => $depenseCategories,
        ));
    }

    /**
     * Creates a new depenseCategory entity.
     *
     * @Route("/new", name="depensecategory_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        
        $depenseCategory = new Depensecategory();
        $form = $this->createForm('Systeo\DepenseBundle\Form\DepenseCategoryType', $depenseCategory);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($depenseCategory);
            $em->flush();
            
            $this->addFlash('success', 'Nouvelle catégorie de dépense ajoutée avec succès.');

            return $this->redirectToRoute('depensecategory_show', array('id' => $depenseCategory->getId()));
        }

        return $this->render('SysteoDepenseBundle:depensecategory:new.html.twig', array(
            'depenseCategory' => $depenseCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a depenseCategory entity.
     *
     * @Route("/{id}", name="depensecategory_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(DepenseCategory $depenseCategory)
    {
        $deleteForm = $this->createDeleteForm($depenseCategory);

        return $this->render('SysteoDepenseBundle:depensecategory:show.html.twig', array(
            'depenseCategory' => $depenseCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing depenseCategory entity.
     *
     * @Route("/{id}/edit", name="depensecategory_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, DepenseCategory $depenseCategory)
    {
        $deleteForm = $this->createDeleteForm($depenseCategory);
        $editForm = $this->createForm('Systeo\DepenseBundle\Form\DepenseCategoryType', $depenseCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'Catégorie de dépense modifiée avec succès.');

            return $this->redirectToRoute('depensecategory_edit', array('id' => $depenseCategory->getId()));
        }

        return $this->render('SysteoDepenseBundle:depensecategory:edit.html.twig', array(
            'depenseCategory' => $depenseCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a depenseCategory entity.
     *
     * @Route("/{id}", name="depensecategory_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, DepenseCategory $depenseCategory)
    {
        $form = $this->createDeleteForm($depenseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($depenseCategory);
            $em->flush();
            
            $this->addFlash('success', 'Catégorie de dépense supprimée avec succès.');
        }

        return $this->redirectToRoute('depensecategory_index');
    }

    /**
     * Creates a form to delete a depenseCategory entity.
     *
     * @param DepenseCategory $depenseCategory The depenseCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DepenseCategory $depenseCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('depensecategory_delete', array('id' => $depenseCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    

}
