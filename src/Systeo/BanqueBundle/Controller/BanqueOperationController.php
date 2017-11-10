<?php

namespace Systeo\BanqueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Systeo\BanqueBundle\Entity\BanqueOperation;
use Systeo\BanqueBundle\Form\BanqueOperationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Systeo\BanqueBundle\Entity\BanqueCompte;
use Systeo\ReglementBundle\Entity\Reglement;

/**
 * BanqueOperation controller.
 *
 * @Route("/banqueoperation")
 */
class BanqueOperationController extends Controller
{
    /**
     * Lists all BanqueOperation entities.
     *
     * @Route("/", name="banqueoperation_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        
        
        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('banqueoperation_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $banqueOperations = $paginator->paginate(
                $em->getRepository('SysteoBanqueBundle:BanqueOperation')->MyFindAll($request->query->all()), /* query NOT result */ 
                $request->query->getInt('page', 1)/* page number */, 
                25/* limit per page */
        );
        
        $comptes = $em->getRepository('SysteoBanqueBundle:BanqueCompte')->findAll();
        
        $totaux = $em->getRepository('SysteoBanqueBundle:BanqueOperation')->getSumOperations($request->query->all());
        
        if($request->isXmlHttpRequest()){
            return $this->render('SysteoBanqueBundle:banqueoperation:index-ajax.html.twig', array(
                        'banqueOperations' => $banqueOperations,
                        'comptes'=>$comptes,
                        'origin_vue'=>$request->query->get('origin_vue'),
                        'origin_vue_id'=>$request->query->get('origin_vue_id'),
                        'origin_type'=>$request->query->get('origin_type'),
                        'origin_type_id'=>$request->query->get('origin_type_id'),
                        'params'=>$request->query->all(),
            ));
        }
        
        
        return $this->render('SysteoBanqueBundle:banqueoperation:index.html.twig', array(
                    'banqueOperations' => $banqueOperations,
                    'totaux'=>$totaux,
                    'comptes'=>$comptes,
                    'params'=> !empty($request->query->all())?json_encode($request->query->all()):''
        ));
    }

    /**
     * Creates a new BanqueOperation entity.
     *
     * @Route("/new", name="banqueoperation_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $banqueOperation = new BanqueOperation();
        $form = $this->createForm('Systeo\BanqueBundle\Form\BanqueOperationType', $banqueOperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($banqueOperation);
            $em->flush();
            
            $this->addFlash('success', 'Nouvelle opération bancaire ajoutée avec succès.');

            return $this->redirectToRoute('banqueoperation_show', array('id' => $banqueOperation->getId()));
        }

        return $this->render('SysteoBanqueBundle:banqueoperation:new.html.twig', array(
            'banqueOperation' => $banqueOperation,
            'form' => $form->createView(),
        ));
    }
    
     /**
     * Importer format CSV des opérations bancaires
     *
     * @Route("/importer", name="banqueoperation_importer")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function importerAction()
    {
        $fileForm = $this->createForm('Systeo\FileBundle\Form\SimpleFileType');

        return $this->render('SysteoBanqueBundle:banqueoperation:importer.html.twig', array(
            'file_form' => $fileForm->createView(),
        ));
    }
    
    /**
     * Importer format CSV des opérations bancaires
     *
     * @Route("/display-csv/{file}", name="banqueoperation_display_save_csv")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function displaySaveCsvAction(Request $request,$file = null)
    {
        
        $fichier = $this->getParameter("tmp-files").'/'.$file;
        
        if(!$file || !file_exists($fichier)){
            $this->addFlash('danger', 'Fichier d\'importation inexsitant ou inaccessible.');
            return $this->redirectToRoute('banqueoperation_index');
        }
        
        $handle = fopen($fichier,'r');
        
        $operations = array();
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            
            $operations[] = [
                'i_date'=>$this->getDate($data[0]),
                'i_date_valeur'=>$this->getDate($data[1]),
                'date'=>$data[0],
                'date_valeur'=>$data[1],
                'i_libelle'=>$this->getlibelle($data[2]),
                'i_debit'=>$this->deleteWhiteSpacesNumbers($data[3]),
                'i_credit'=>$this->deleteWhiteSpacesNumbers($data[4]),
                'i_solde'=>$this->deleteWhiteSpacesNumbers($data[5]),
            ];
        }
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $operations = $request->request->all();
            
            $compte = $em->getRepository("SysteoBanqueBundle:BanqueCompte")->findOneById($operations['compte']);
            
            foreach($operations['data'] as $op):
                $this->saveOperation($op, $compte);
            endforeach;
            
            unlink($fichier);
            
            $this->addFlash('success', 'Importation effectuée avec succès.');
            return $this->redirectToRoute('banqueoperation_index');
            
        }
        
        
        $comptes = $em->getRepository("SysteoBanqueBundle:BanqueCompte")->findAll();

        return $this->render('SysteoBanqueBundle:banqueoperation:display-save-csv.html.twig', array(
            'data' => $operations,
            'comptes'=>$comptes
        ));
    }
    
    /**
     * get solde des oprations bancaires
     *
     * @Route("/solde-total", name="banqueoperation_solde_ajax")
     * @Method({"GET","POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function soldeAction(Request $request)
    {
        
        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('banqueoperation_solde_ajax', $url);
            }
        }
        
        $em = $this->getDoctrine()->getManager();

        $banqueOperations = $em->getRepository('SysteoBanqueBundle:BanqueOperation')->MyFindAll($request->query->all(),true);
        
        $totalSolde = 0;
        
        foreach($banqueOperations as $bo):
            $totalSolde += $bo->getSoldeReglement();
        endforeach;
        
        return new \Symfony\Component\HttpFoundation\Response(number_format($totalSolde,3,'.',' '));
    }

    /**
     * Finds and displays a BanqueOperation entity.
     *
     * @Route("/{id}", name="banqueoperation_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(BanqueOperation $banqueOperation)
    {
        $deleteForm = $this->createDeleteForm($banqueOperation);

        return $this->render('SysteoBanqueBundle:banqueoperation:show.html.twig', array(
            'banqueOperation' => $banqueOperation,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
   

    /**
     * Displays a form to edit an existing BanqueOperation entity.
     *
     * @Route("/{id}/edit", name="banqueoperation_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, BanqueOperation $banqueOperation)
    {
        $deleteForm = $this->createDeleteForm($banqueOperation);
        $editForm = $this->createForm('Systeo\BanqueBundle\Form\BanqueOperationType', $banqueOperation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            if((int)$banqueOperation->getDebit()!==0){
                $banqueOperation->setSoldeReglementDebit($banqueOperation->getSoldeReglement());
            }else{
                $banqueOperation->setSoldeReglementCredit($banqueOperation->getSoldeReglement());
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($banqueOperation);
            $em->flush();
            
            $this->addFlash('success', 'Opération bancaire modifiée avec succès.');

            return $this->redirectToRoute('banqueoperation_edit', array('id' => $banqueOperation->getId()));
        }

        return $this->render('SysteoBanqueBundle:banqueoperation:edit.html.twig', array(
            'banqueOperation' => $banqueOperation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Finds and displays a BanqueOperation entity.
     *
     * @Route("/{id}/affecter", name="banqueoperation_affecter")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function affecterAction(Request $request, BanqueOperation $banqueOperation)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        
        if($request->query->get('origin_type')==='reglement' && $request->query->get('origin_type_id') !== ""){
            $reglement = $em->getRepository('SysteoReglementBundle:Reglement')->findOneById($request->query->get('origin_type_id'));
            
            if($reglement->getDirection() === 'in'){
                $banqueOperation->setSoldeReglementCredit($banqueOperation->getSoldeReglement() - $reglement->getMontant());
            }elseif($reglement->getDirection() === 'out'){
                $banqueOperation->setSoldeReglementDebit($banqueOperation->getSoldeReglement() - $reglement->getMontant());
            }
            
            $reglement->setBanqueOperation($banqueOperation);
            $em->persist($reglement);
            $em->flush();
            
            $this->addFlash('success', 'Opération bancaire affectée avec succès.');
        }
        
        if($request->query->get('origin_vue')==='depense' && $request->query->get('origin_vue_id') !== ""){
            
            if($request->query->get('origin_type')==='' && $request->query->get('origin_type_id') === ""){
                
                $depense = $em->getRepository("SysteoDepenseBundle:Depense")->findOneById($request->query->get('origin_vue_id'));
                
                if(!is_null($depense)){
                    
                    $reglement = new Reglement();
                    
                    $reglement->setBanqueOperation($banqueOperation);
                    $reglement->setDate($banqueOperation->getDate());
                    $reglement->setDepense($depense);
                    $reglement->setDirection('out');
                    $reglement->setMontant($banqueOperation->getDebit());
                    $reglement->setName($banqueOperation->getName());
                    $reglement->setTier($depense->getTier());
                    $reglement->setType($banqueOperation->getTypeThroughName());
                    
                    $em->persist($reglement);
                    $em->flush();
                    
                    $this->addFlash('success', 'Nouveau réglement créé avec succès.');
                    
                }
                
            }
            
            return $this->redirectToRoute('depense_show', array('id' => $request->query->get('origin_vue_id')));
            
            
        }elseif($request->query->get('origin_vue')==='piece' && $request->query->get('origin_vue_id') !== ""){
            
            if($request->query->get('origin_type')==='' && $request->query->get('origin_type_id') === ""){
                
                $piece = $em->getRepository("SysteoVenteBundle:Piece")->findOneById($request->query->get('origin_vue_id'));
                
                if(!is_null($piece)){
                    
                    $reglement = new Reglement();
                    
                    $reglement->setBanqueOperation($banqueOperation);
                    $reglement->setDate($banqueOperation->getDate());
                    $reglement->setPiece($piece);
                    $reglement->setDirection('in');
                    $reglement->setMontant($banqueOperation->getCredit());
                    $reglement->setName($banqueOperation->getName());
                    $reglement->setTier($piece->getTier());
                    $reglement->setType($banqueOperation->getTypeThroughName());
                    
                    $em->persist($reglement);
                    $em->flush();
                    
                    $this->addFlash('success', 'Nouveau réglement créé avec succès.');
                    
                }
                
            }
            
            return $this->redirectToRoute('piece_show', array('id' => $request->query->get('origin_vue_id')));
        }
        
        return $this->redirectToRoute('banqueoperation_index');
    }

    /**
     * Deletes a BanqueOperation entity.
     *
     * @Route("/{id}", name="banqueoperation_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, BanqueOperation $banqueOperation)
    {
        $form = $this->createDeleteForm($banqueOperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $banqueOperation_id = $banqueOperation->getId();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->getRepository('SysteoReglementBundle:Reglement')->removeRelatedEntity('banqueOperation',$banqueOperation_id); 
            
            $em->remove($banqueOperation);
            $em->flush();
            
            $this->addFlash('success', 'Opération bancaire supprimée avec succès.');
        }

        return $this->redirectToRoute('banqueoperation_index');
    }
    
    

    /**
     * Creates a form to delete a BanqueOperation entity.
     *
     * @param BanqueOperation $banqueOperation The BanqueOperation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BanqueOperation $banqueOperation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('banqueoperation_delete', array('id' => $banqueOperation->getId())))
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
        $data = $data['op_search'];
        
        foreach ($data as $k => $v) {
            if($this->checkField($data, 'name')){ $url['name'] = $data['name']; }
            if($this->checkField($data, 'compte')){ $url['compte'] = $data['compte']; }
            if($this->checkField($data, 'display')){ $url['display'] = $data['display']; }
            
            if($this->checkFieldNumerique($data, 'debit')){ 
                $url['debit'] = $data['debit']; 
                if($this->checkField($data, 'debit_comparateur')){ $url['debit_comparateur'] = $data['debit_comparateur']; }
            }
            
            if($this->checkFieldNumerique($data, 'solde_debit')){ 
                $url['solde_debit'] = $data['solde_debit']; 
                if($this->checkField($data, 'solde_debit_comparateur')){ $url['solde_debit_comparateur'] = $data['solde_debit_comparateur']; }
            }
            
            if($this->checkFieldNumerique($data, 'credit')){ 
                $url['credit'] = $data['credit']; 
                if($this->checkField($data, 'credit_comparateur')){ $url['credit_comparateur'] = $data['credit_comparateur']; }
            }
            
            if($this->checkFieldNumerique($data, 'solde_credit')){ 
                $url['solde_credit'] = $data['solde_credit']; 
                if($this->checkField($data, 'solde_credit_comparateur')){ $url['solde_credit_comparateur'] = $data['solde_credit_comparateur']; }
            }
            
            if($this->checkField($data, 'valeur_debut')){ $url['valeur_debut'] = $this->getDate($data['valeur_debut']); }
            if($this->checkField($data, 'valeur_fin')){ $url['valeur_fin'] = $this->getDate($data['valeur_fin']); }
            if($this->checkField($data, 'date_debut')){ $url['date_debut'] = $this->getDate($data['date_debut']); }
            if($this->checkField($data, 'date_fin')){ $url['date_fin'] = $this->getDate($data['date_fin']); }
            
            if($this->checkField($data, 'origin_vue')){ $url['origin_vue'] = $data['origin_vue']; }
            if($this->checkField($data, 'origin_vue_id')){ $url['origin_vue_id'] = $data['origin_vue_id']; }
            if($this->checkField($data, 'origin_type')){ $url['origin_type'] = $data['origin_type']; }
            if($this->checkField($data, 'origin_type_id')){ $url['origin_type_id'] = $data['origin_type_id']; }
        }

        return $url;
    }
    
    /**
     * 
     * @param type $data
     * @param type $field
     * @return boolean
     */
    private function checkField($data,$field){
        if (isset($data[$field]) && $data[$field] !== "") {
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param type $data
     * @param type $field
     * @return boolean
     */
    private function checkFieldNumerique($data,$field){
        if (isset($data[$field]) && $data[$field] !== "") {
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
    
    /**
     * 
     * @param type $string
     * @return type
     */
    private function deleteWhiteSpacesNumbers($string){
        $string = htmlentities($string, null, 'utf-8');
        $string = str_replace("&nbsp;", "", $string);
        
        return $string;
    }
    
    /**
     * 
     * @param type $string
     * @return type
     */
    private function deleteWhiteSpaces($string){
        $string = htmlentities($string, null, 'utf-8');
        $string = str_replace("&nbsp;", " ", $string);
        
        return $string;
    }
    
    /**
     * 
     * @param type $string
     * @return string
     */
    private function getlibelle($string){
        
        $string = $this->deleteWhiteSpaces($string);

        $tab = explode(' ',$string);
       
        $result = '';
        
        foreach($tab as $value):
            if($value != ""){
                $result .= $value.' ';
            }
            
        endforeach;
        
        return $result;
        
    }
    
    private function saveOperation($data, BanqueCompte $compte){
        
        $em = $this->getDoctrine()->getManager();
        
        $operation = new BanqueOperation();
        
        $credit = empty($data['credit'])?null:$data['credit'];
        $debit = empty($data['debit'])?null:$data['debit'];
        $solde = empty($data['solde'])?null:$data['solde'];
        
        $operation->setBanqueCompte($compte);
        $operation->setCredit($credit);
        $operation->setDebit($debit);
        $operation->setSolde($solde);
        $operation->setName($data['libelle']);
        
        $date = new \DateTime();
        
        $operation->setDate($date->createFromFormat('Y-m-d', $data['date']));
        $operation->setDateValeur($date->createFromFormat('Y-m-d', $data['date_valeur']));
        
        $em->persist($operation);
        $em->flush();
    }
}
