<?php

namespace Systeo\FileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Systeo\FileBundle\Entity\FileCategory;
use Systeo\FileBundle\Form\FileCategoryType;

/**
 * FileCategory controller.
 *
 * @Route("/filecategory")
 */
class FileCategoryController extends Controller
{
    /**
     * Lists all FileCategory entities.
     *
     * @Route("/", name="filecategory_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $fileCategory = new FileCategory();
        $form = $this->createForm('Systeo\FileBundle\Form\FileCategorySearchType', $fileCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('filecategory_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $fileCategories = $paginator->paginate(
                $em->getRepository('SysteoFileBundle:FileCategory')->MyFindAll($request->query->all()), /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 5/* limit per page */
        );

        return $this->render('SysteoFileBundle:filecategory:index.html.twig', array(
                    'fileCategories' => $fileCategories,
                    'parametre' => $parametre = $this->parametreUrl($request->query),
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new FileCategory entity.
     *
     * @Route("/new", name="filecategory_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $fileCategory = new FileCategory();
        $form = $this->createForm('Systeo\FileBundle\Form\FileCategoryType', $fileCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fileCategory);
            $em->flush();
            
            $this->addFlash('success', 'Nouvelle catégorie de fichier ajoutée avec succès.');

            return $this->redirectToRoute('filecategory_show', array('id' => $fileCategory->getId()));
        }

        return $this->render('SysteoFileBundle:filecategory:new.html.twig', array(
            'fileCategory' => $fileCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a FileCategory entity.
     *
     * @Route("/{id}", name="filecategory_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(FileCategory $fileCategory)
    {
        $deleteForm = $this->createDeleteForm($fileCategory);

        return $this->render('SysteoFileBundle:filecategory:show.html.twig', array(
            'fileCategory' => $fileCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FileCategory entity.
     *
     * @Route("/{id}/edit", name="filecategory_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, FileCategory $fileCategory)
    {
        $deleteForm = $this->createDeleteForm($fileCategory);
        $editForm = $this->createForm('Systeo\FileBundle\Form\FileCategoryType', $fileCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fileCategory);
            $em->flush();
            
            $this->addFlash('success', 'La mise à jour de la catégorie de fichier a été effectuée.');

            return $this->redirectToRoute('filecategory_edit', array('id' => $fileCategory->getId()));
        }

        return $this->render('SysteoFileBundle:filecategory:edit.html.twig', array(
            'fileCategory' => $fileCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FileCategory entity.
     *
     * @Route("/{id}", name="filecategory_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, FileCategory $fileCategory)
    {
        $form = $this->createDeleteForm($fileCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fileCategory);
            $em->flush();
            
            $this->addFlash('success', 'Catégorie de fichier supprimée avec succès.');
        }

        return $this->redirectToRoute('filecategory_index');
    }

    /**
     * Creates a form to delete a FileCategory entity.
     *
     * @param FileCategory $fileCategory The FileCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FileCategory $fileCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('filecategory_delete', array('id' => $fileCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     * @param array $data $request->query->all()
     * @return array URL parameters
     */
    private function buildSearchUrl($data) {
        $url = [];
        foreach ($data as $k => $v) {
            if (isset($data['file_category_search']['search']) && !empty($data['file_category_search']['search'])) {
                $url['search'] = $data['file_category_search']['search'];
            }

            if (isset($data['file_category_search']['active']) && $data['file_category_search']['active'] != '') {
                $url['active'] = $data['file_category_search']['active'];
            }
        }

        return $url;
    }

    public function parametreUrl($paramUrl) {
        $parametre = [];
        $parametre["search"] = $paramUrl->get('search');
        $parametre["active"] = $paramUrl->get('active');
        return $parametre;
    }
}
