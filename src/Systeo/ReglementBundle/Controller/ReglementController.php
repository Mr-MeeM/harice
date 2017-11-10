<?php

namespace Systeo\ReglementBundle\Controller;

use Systeo\ReglementBundle\Entity\Reglement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Reglement controller.
 *
 * @Route("reglement")
 */
class ReglementController extends Controller
{
    /**
     * Lists all reglement entities.
     *
     * @Route("/", name="reglement_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
      
        
        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('reglement_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $reglements = $paginator->paginate(
                $em->getRepository('SysteoReglementBundle:Reglement')->MyFindAllOut($request->query->all()), /* query NOT result */ 
                $request->query->getInt('page', 1)/* page number */, 
                25/* limit per page */
        );
        
        $reg = new Reglement();
        
        $totaux = $em->getRepository('SysteoReglementBundle:Reglement')->getSumOperationsOut($request->query->all());
        
        return $this->render('SysteoReglementBundle:reglement:index.html.twig', array(
            'reglements' => $reglements,
            'totaux'=>$totaux,
            'types'=>$reg->getTypeValues()
        ));
        

    }
    
    /**
     * Creates a new reglement entity.
     *
     * @Route("/new-ajax", name="reglement_new_ajax")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAjaxAction(Request $request)
    {
        $reglement = new Reglement();
        $form = $this->createForm('Systeo\ReglementBundle\Form\ReglementAjaxType', $reglement);
        $form->handleRequest($request);
        
       
        $depense = $piece = null;
        $type = $entite =  $montant = '';
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->query->get('type') === 'depense' && $request->query->get('entite')!=""){
            
            $depense = $em->getRepository('SysteoDepenseBundle:Depense')->findOneById($request->query->get('entite'));
            
            if(is_null($depense)){
                return new \Symfony\Component\HttpFoundation\Response(false);
            }
            
            $reglement->setDepense($depense);
            $reglement->setDirection('out');
            
            $montant = $depense->getSolde();
            $type = 'depense';
            $entite = $depense->getId();
            
        }elseif($request->query->get('type') === 'piece' && $request->query->get('entite')!=""){
            
            $piece = $em->getRepository('SysteoVenteBundle:Piece')->findOneById($request->query->get('entite'));
            
            if(is_null($piece)){
                return new \Symfony\Component\HttpFoundation\Response(false);
            }
            
            $reglement->setPiece($piece);
            $reglement->setDirection('in');
            
            $montant = $piece->getSolde();
            $type = 'piece';
            $entite = $piece->getId();
            
        }else{
            return new \Symfony\Component\HttpFoundation\Response(false);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($reglement);
            $em->flush();
            
            $this->addFlash('success', 'Nouveau réglement ajouté avec succès.');
            
            return new \Symfony\Component\HttpFoundation\Response('ok');
        }

        return $this->render('SysteoReglementBundle:reglement:new-ajax.html.twig', array(
            'reglement' => $reglement,
            'form' => $form->createView(),
            'montant'=>$montant,
            'type'=>$type,
            'entite'=>$entite
        ));
    }

    /**
     * Creates a new reglement entity.
     *
     * @Route("/new", name="reglement_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $reglement = new Reglement();
        $form = $this->createForm('Systeo\ReglementBundle\Form\ReglementType', $reglement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reglement);
            $em->flush();
            
            $this->addFlash('success', 'Nouveau réglement ajouté avec succès.');

            return $this->redirectToRoute('reglement_show', array('id' => $reglement->getId()));
        }

        return $this->render('SysteoReglementBundle:reglement:new.html.twig', array(
            'reglement' => $reglement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reglement entity.
     *
     * @Route("/{id}", name="reglement_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Reglement $reglement)
    {
        $deleteForm = $this->createDeleteForm($reglement);

        return $this->render('SysteoReglementBundle:reglement:show.html.twig', array(
            'reglement' => $reglement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reglement entity.
     *
     * @Route("/{id}/edit", name="reglement_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Reglement $reglement)
    {
        $deleteForm = $this->createDeleteForm($reglement);
        $editForm = $this->createForm('Systeo\ReglementBundle\Form\ReglementType', $reglement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $reglement = $this->recaculateSoldes($reglement);
            
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'Réglement modifié avec succès.');

            return $this->redirectToRoute('reglement_edit', array('id' => $reglement->getId()));
        }

        return $this->render('SysteoReglementBundle:reglement:edit.html.twig', array(
            'reglement' => $reglement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    
    /**
     * Displays a form to edit an existing reglement entity.
     *
     * @Route("/{id}/edit-ajax", name="reglement_edit_ajax")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAjaxAction(Request $request, Reglement $reglement)
    {
      
        $editForm = $this->createForm('Systeo\ReglementBundle\Form\ReglementAjaxType', $reglement);
        $editForm->handleRequest($request);
        
       

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $reglement = $this->recaculateSoldes($reglement);
            
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'Réglement modifié avec succès.');
            
            return new \Symfony\Component\HttpFoundation\Response('ok');
            
            /*if($request->query->get('type') === 'depense' && $request->query->get('entite')!=""){
                return $this->redirectToRoute('depense_show', array('id' => $request->query->get('entite')));
            }elseif($request->query->get('type') === 'piece' && $request->query->get('entite')!=""){
                return $this->redirectToRoute('piece_show', array('id' => $request->query->get('entite')));
            }
            
            return $this->redirectToRoute('reglement_edit', array('id' => $reglement->getId()));*/
        }

        return $this->render('SysteoReglementBundle:reglement:edit-ajax.html.twig', array(
            'reglement' => $reglement,
            'edit_form' => $editForm->createView(),
            'type'=>$request->query->get('type'),
            'entite'=>$request->query->get('entite')
        ));
    }
    
    /**
     * Displays a form to edit an existing reglement entity.
     *
     * @Route("/{id}/desaffecter-operation-bancaire-depense", name="reglement_depense_desaffecter_operation_bancaire")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function desaffecterOperationBancaireDepenseAction(Reglement $reglement)
    {
        $operationBancaire = $reglement->getBanqueOperation();
        $operationBancaire->setSoldeReglementDebit($operationBancaire->getSoldeReglementDebit() + $reglement->getMontant());
        
        $em = $this->getDoctrine()->getManager();
        
        $em->persist($operationBancaire);
        $em->flush();
        
        $reglement->setBanqueOperation(null);
        $em->flush();
        
        $this->addFlash('success', 'Opération bancaire désaffectée avec succès.');
        
        return $this->redirectToRoute('depense_show', array('id' => $reglement->getDepense()->getId()));
        
    }
    
    /**
     * Displays a form to edit an existing reglement entity.
     *
     * @Route("/{id}/desaffecter-operation-bancaire-piece", name="reglement_piece_desaffecter_operation_bancaire")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function desaffecterOperationBancairePieceAction(Reglement $reglement)
    {
        $operationBancaire = $reglement->getBanqueOperation();
        $operationBancaire->setSoldeReglementCredit($operationBancaire->getSoldeReglementCredit() + $reglement->getMontant());
        
        $em = $this->getDoctrine()->getManager();
        
        $em->persist($operationBancaire);
        $em->flush();
        
        $reglement->setBanqueOperation(null);
        $em->flush();
        
        $this->addFlash('success', 'Opération bancaire désaffectée avec succès.');
        
        return $this->redirectToRoute('piece_show', array('id' => $reglement->getPiece()->getId()));
        
    }

    /**
     * Deletes a reglement entity.
     *
     * @Route("/{id}", name="reglement_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Reglement $reglement)
    {
        $form = $this->createDeleteForm($reglement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reglement);
            $em->flush();
        }

        return $this->redirectToRoute('reglement_index');
    }

    /**
     * Creates a form to delete a reglement entity.
     *
     * @param Reglement $reglement The reglement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reglement $reglement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reglement_delete', array('id' => $reglement->getId())))
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
        $data = $data['reglement_search'];
        
        
        foreach ($data as $k => $v) {
            if($this->checkField($data, 'name')){ $url['name'] = $data['name']; }
            
            if($this->checkField($data, 'tier')){ $url['tier'] = $data['tier']; }
            if($this->checkField($data, 'depense')){ $url['depense'] = $data['depense']; }
            if($this->checkField($data, 'type')){ $url['type'] = $data['type']; }
            
            if($this->checkField($data, 'montant')){ 
                $url['montant'] = $data['montant']; 
                if($this->checkField($data, 'montant_comparateur')){ $url['montant_comparateur'] = $data['montant_comparateur']; }
            }
            
            if($this->checkField($data, 'date_debut')){ $url['date_debut'] = $this->getDate($data['date_debut']); }
            if($this->checkField($data, 'date_fin')){ $url['date_fin'] = $this->getDate($data['date_fin']); }
        }

        return $url;
    }
    
    private function checkField($data,$field){
        if (isset($data[$field]) && !empty($data[$field])) {
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param type $date
     * @return type
     */
    private function getDate($date){
        $date = explode('/',$date);
        
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
    
    
    private function recaculateSoldes(Reglement $reglement){
        
        if($reglement->getDirection() === 'in'){
            if(!is_null($reglement->getPiece())){
                $reglement->getPiece()->setSolde($reglement->getPiece()->getCalculatedSolde());
            }
            if(!is_null($reglement->getBanqueOperation())){
                $reglement->getBanqueOperation()->setSoldeReglementCredit($reglement->getBanqueOperation()->getSoldeReglement());
            }
            
        }elseif($reglement->getDirection() === 'out'){
            if(!is_null($reglement->getDepense())){
                $reglement->getDepense()->setSolde($reglement->getDepense()->getCalculatedSolde());
            }
            if(!is_null($reglement->getBanqueOperation())){
                $reglement->getBanqueOperation()->setSoldeReglementDebit($reglement->getBanqueOperation()->getSoldeReglement());
            }
        }
        
        return $reglement;
        
    }
}
