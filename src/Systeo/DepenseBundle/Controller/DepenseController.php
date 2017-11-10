<?php

namespace Systeo\DepenseBundle\Controller;

use Systeo\DepenseBundle\Entity\Depense;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Depense controller.
 *
 * @Route("depense")
 */
class DepenseController extends Controller {

    /**
     * Lists all depense entities.
     *
     * @Route("/", name="depense_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {
        $form = $this->createForm('Systeo\DepenseBundle\Form\DepenseSearchType');

        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('depense_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $depenses = $paginator->paginate(
                $em->getRepository('SysteoDepenseBundle:Depense')->MyFindAll($request->query->all()), /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 25/* limit per page */
        );

        $totaux = $em->getRepository('SysteoDepenseBundle:Depense')->getSumOperations($request->query->all());
 
        return $this->render('SysteoDepenseBundle:depense:index.html.twig', array(
                    'depenses' => $depenses,
                    'totaux' => $totaux,
                    'form' => $form->createView(),
                    'files' => $this->getFilesDepenses($depenses)
        ));
    }

    /**
     * Creates a new depense entity.
     *
     * @Route("/new", name="depense_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $depense = new Depense();
        $form = $this->createForm('Systeo\DepenseBundle\Form\DepenseType', $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($depense);
            $em->flush();

            $this->addFlash('success', 'Nouvelle  dépense ajoutée avec succès.');

            return $this->redirectToRoute('depense_show', array('id' => $depense->getId()));
        }

        return $this->render('SysteoDepenseBundle:depense:new.html.twig', array(
                    'depense' => $depense,
                    'form' => $form->createView(),
        ));
    }
    
    
    

    /**
     * Finds and displays a depense entity.
     *
     * @Route("/{id}", name="depense_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Depense $depense) {
        $deleteForm = $this->createDeleteForm($depense);

        return $this->render('SysteoDepenseBundle:depense:show.html.twig', array(
                    'depense' => $depense,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing depense entity.
     *
     * @Route("/{id}/edit", name="depense_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Depense $depense) {
        $deleteForm = $this->createDeleteForm($depense);
        $editForm = $this->createForm('Systeo\DepenseBundle\Form\DepenseType', $depense);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $depense->setSolde($depense->getCalculatedSolde());
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Dépense modifiée avec succès.');

            return $this->redirectToRoute('depense_edit', array('id' => $depense->getId()));
        }

        return $this->render('SysteoDepenseBundle:depense:edit.html.twig', array(
                    'depense' => $depense,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a depense entity.
     *
     * @Route("/{id}", name="depense_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Depense $depense) {
        $form = $this->createDeleteForm($depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $depense_id = $depense->getId();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->getRepository('SysteoReglementBundle:Reglement')->removeRelatedEntity('depense',$depense_id); 
            
            $em->remove($depense);
            $em->flush();
            
            $this->addFlash('success', 'Dépense supprimée avec succès.');
        }

        return $this->redirectToRoute('depense_index');
    }

    /**
     * Creates a form to delete a depense entity.
     *
     * @param Depense $depense The depense entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Depense $depense) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('depense_delete', array('id' => $depense->getId())))
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
        $data = $data['depense_search'];

        $em = $this->getDoctrine()->getManager();

        foreach ($data as $k => $v) {
            if ($this->checkField($data, 'name')) {
                $url['name'] = $data['name'];
            }

            if ($this->checkField($data, 'depenseCategory')) {
                $url['depenseCategory'] = $data['depenseCategory'];
            }

            if ($this->checkField($data, 'tier')) {
                $url['tier'] = $data['tier'];
            }

            if ($this->checkField($data, 'montantHt')) {
                $url['montantHt'] = $data['montantHt'];
                if ($this->checkField($data, 'montantHt_comparateur')) {
                    $url['montantHt_comparateur'] = $data['montantHt_comparateur'];
                }
            }
            
            if ($this->checkField($data, 'solde')) {
                $url['solde'] = $data['solde'];
                if ($this->checkField($data, 'solde_comparateur')) {
                    $url['solde_comparateur'] = $data['solde_comparateur'];
                }
            }

            if ($this->checkField($data, 'montantTva')) {
                $url['montantTva'] = $data['montantTva'];
                if ($this->checkField($data, 'montantTva_comparateur')) {
                    $url['montantTva_comparateur'] = $data['montantTva_comparateur'];
                }
            }

            if ($this->checkField($data, 'montantTtc')) {
                $url['montantTtc'] = $data['montantTtc'];
                if ($this->checkField($data, 'montantTtc_comparateur')) {
                    $url['montantTtc_comparateur'] = $data['montantTtc_comparateur'];
                }
            }

            if ($this->checkField($data, 'date_debut')) {
                $url['date_debut'] = $this->getDate($data['date_debut']);
            }
            if ($this->checkField($data, 'date_fin')) {
                $url['date_fin'] = $this->getDate($data['date_fin']);
            }
        }

        return $url;
    }

    private function checkField($data, $field) {
        if (isset($data[$field]) && $data[$field]!=="") {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param type $date
     * @return type
     */
    private function getDate($date) {
        $date = explode('/', $date);

        return $date[2] . '-' . $date[1] . '-' . $date[0];
    }

    /**
     * 
     * @param type $depenses
     * @return ARRAY
     */
    private function getFilesDepenses($depenses) {
        $tab = [];
        $folder = $this->getParameter('hidden-files') . '/Depense/';
        
        foreach ($depenses as $dep):

            $folderDep = $folder.$dep->getId();
            $tab[$dep->getId()] = [];
            
            if (is_dir($folderDep)) {

                $files = scandir($folderDep);
                
                foreach ($files as $file):
                    if ($file != "." && $file != ".." && !is_dir($file)) {
                        $tab[$dep->getId()][] = $file;
                    }
                endforeach;
            }
        endforeach;

        return $tab;
    }

}
