<?php

namespace Systeo\TierBundle\Controller;

use Systeo\TierBundle\Entity\Tier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Tier controller.
 *
 * @Route("tier")
 */
class TierController extends Controller
{
    /**
     * Lists all tier entities.
     *
     * @Route("/", name="tier_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $tier = new Tier();
        $form = $this->createForm('Systeo\TierBundle\Form\TierSearchType', $tier);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('tier_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $tiers = $paginator->paginate(
                $em->getRepository('SysteoTierBundle:Tier')->MyFindAll(
                    $request->query->all(),$this->getUser()), 
                    $request->query->getInt('page', 1), 
                    20
        );
        
        if($request->isXmlHttpRequest()){
            return $this->render('SysteoTierBundle:tier:index-ajax.html.twig', array(
                'tiers' => $tiers,
                'form' => $form->createView(),
            ));
        }
        
        return $this->render('SysteoTierBundle:tier:index.html.twig', array(
            'tiers' => $tiers,
            'form' => $form->createView(),
            'types'=>$tier->getTypeValues()
        ));
    }

    /**
     * Creates a new tier entity.
     *
     * @Route("/new", name="tier_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $tier = new Tier();
        $form = $this->createForm('Systeo\TierBundle\Form\TierType', $tier);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($tier);
            $em->flush();
            
            $this->addFlash('success', 'Nouveau tier ajouté avec succès.');

            return $this->redirectToRoute('tier_show', array('id' => $tier->getId()));
        }

        return $this->render('SysteoTierBundle:tier:new.html.twig', array(
            'tier' => $tier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tier entity.
     *
     * @Route("/{id}", name="tier_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Tier $tier)
    {
        $deleteForm = $this->createDeleteForm($tier);

        return $this->render('SysteoTierBundle:tier:show.html.twig', array(
            'tier' => $tier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tier entity.
     *
     * @Route("/{id}/edit", name="tier_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Tier $tier)
    {
        $deleteForm = $this->createDeleteForm($tier);
        $editForm = $this->createForm('Systeo\TierBundle\Form\TierType', $tier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'Modification du tier effectuée avec succès.');

            return $this->redirectToRoute('tier_edit', array('id' => $tier->getId()));
        }

        return $this->render('SysteoTierBundle:tier:edit.html.twig', array(
            'tier' => $tier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tier entity.
     *
     * @Route("/{id}", name="tier_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Tier $tier)
    {
        $form = $this->createDeleteForm($tier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tier);
            $em->flush();
            
            $this->addFlash('success', 'Tier supprimé avec succès.');
        }

        return $this->redirectToRoute('tier_index');
    }

    /**
     * Creates a form to delete a tier entity.
     *
     * @param Tier $tier The tier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tier $tier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tier_delete', array('id' => $tier->getId())))
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
            if (isset($data['systeo_tier']['name']) && !empty($data['systeo_tier']['name'])) {
                $url['name'] = $data['systeo_tier']['name'];
            }
            
            if (isset($data['tier_search']['name']) && !empty($data['tier_search']['name'])) {
                $url['name'] = $data['tier_search']['name'];
            }
        }

        return $url;
    }
}
