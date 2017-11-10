<?php

namespace Systeo\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Systeo\UserBundle\Entity\Team;
use Systeo\UserBundle\Form\TeamType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Team controller.
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * Lists all Team entities.
     *
     * @Route("/", name="team_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $teams = $paginator->paginate(
                $em->getRepository('SysteoUserBundle:Team')->FindAll(), /* query NOT result */ 
                $request->query->getInt('page', 1)/* page number */,
                5/* limit per page */
        );
        
        return $this->render('SysteoUserBundle:team:index.html.twig', array(
            'teams' => $teams,
        ));
        
    }

    /**
     * Creates a new Team entity.
     *
     * @Route("/new", name="team_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $team = new Team();
        $form = $this->createForm('Systeo\UserBundle\Form\TeamType', $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            
            $this->addFlash('success', 'Nouvelle team ajoutée avec succès.');

            return $this->redirectToRoute('team_show', array('id' => $team->getId()));
        }

        return $this->render('SysteoUserBundle:team:new.html.twig', array(
            'team' => $team,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Team entity.
     *
     * @Route("/{id}", name="team_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);

        return $this->render('SysteoUserBundle:team:show.html.twig', array(
            'team' => $team,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);
        $editForm = $this->createForm('Systeo\UserBundle\Form\TeamType', $team);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            
            $this->addFlash('success', 'Modification de la team effectué avec succès.');
            
            return $this->redirectToRoute('team_edit', array('id' => $team->getId()));
        }

        return $this->render('SysteoUserBundle:team:edit.html.twig', array(
            'team' => $team,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Team entity.
     *
     * @Route("/{id}", name="team_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Team $team)
    {
        $form = $this->createDeleteForm($team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();
            
            $this->addFlash('success', 'Team supprimée avec succès.');
        }

        return $this->redirectToRoute('team_index');
    }

    /**
     * Creates a form to delete a Team entity.
     *
     * @param Team $team The Team entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Team $team)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('team_delete', array('id' => $team->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
