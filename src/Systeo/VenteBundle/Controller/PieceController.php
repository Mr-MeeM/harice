<?php

namespace Systeo\VenteBundle\Controller;

use Systeo\VenteBundle\Entity\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Systeo\VenteBundle\Entity\PieceLigne;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Piece controller.
 *
 * @Route("piece")
 */
class PieceController extends Controller {

    /**
     * Lists all piece entities.
     *
     * @Route("/", name="piece_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {

        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('piece_index', $url);
            }
        } elseif (empty($request->query->all())) {
            return $this->redirectToRoute('piece_index', ['type' => 'Facture']);
        }



        
        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);
        

        $paginator = $this->get('knp_paginator');

        $pieces = $paginator->paginate(
                $em->getRepository('SysteoVenteBundle:Piece')->MyFindAll($request->query->all()), /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 25/* limit per page */
        );

        $totaux = $em->getRepository('SysteoVenteBundle:Piece')->getSumOperations($request->query->all());

        return $this->render('SysteoVenteBundle:piece:index.html.twig', array(
                    'pieces' => $pieces,
                    'totaux' => $totaux,
                    'tauxFodec' => $conf->getFodec(),
        ));
    }

    /**
     * Lists all piece entities.
     *
     * @Route("/affectation-export", name="piece_affectation_export")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function affectationAction(Request $request) {

        $tier_id = $request->query->get('tier');
        $piece_export_id = $request->query->get('piece_export_id');
        $type = $request->query->get('type');
        $vue = $request->query->get('vue');

        if (empty($tier_id) || empty($piece_export_id) || empty($type) || empty($vue)) {
            return new Response('ok');
        }

        $em = $this->getDoctrine()->getManager();

        $pieces = $em->getRepository('SysteoVenteBundle:Piece')->findBy(['tier' => $tier_id, 'type' => $type], ['date' => 'DESC'], 10);

        return $this->render('SysteoVenteBundle:piece:index-affectation.html.twig', array(
                    'pieces' => $pieces,
                    'vue' => $vue,
                    'type' => $type,
                    'piece_export_id' => $piece_export_id
        ));
    }
    

    /**
     * Creates a new piece entity.
     *
     * @Route("/new", name="piece_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $piece = new Piece();

        if (count($piece->getPieceLignes()) === 0) {
            $piece->addPieceLigne(new PieceLigne());
        }

        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);

        $form = $this->createForm('Systeo\VenteBundle\Form\PieceType', $piece, [
            'entity_manager' => $em
        ]);

        $ckeditor = $this->createForm('Systeo\VenteBundle\Form\DesignationType');
        
        $form->handleRequest($request);

        $type = $this->getPieceType($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $piece->setType($type);

            $em->persist($piece);
            $em->flush();

            $this->addFlash('success', $this->getMessageFlash("new", $type));

            return $this->redirectToRoute('piece_show', array('id' => $piece->getId()));
        }

        return $this->render('SysteoVenteBundle:piece:new.html.twig', array(
                    'piece' => $piece,
                    'form' => $form->createView(),
                    'numero' => $this->getLastNumero($type),
                    'tier' => $this->getTier($request),
                    'timbre' => $conf->getDroitTimbre(),
                    'tauxFodec' => $conf->getFodec(),
                    'ckeditor' => $ckeditor->createView()
        ));
    }

  

    /**
     * Finds and displays a piece entity.
     *
     * @Route("/{id}/imprimer", name="piece_imprimer_one")
     */
    public function imprimerOne1Action(Piece $piece) {
//        $base_tva = [];
//
//        $em = $this->getDoctrine()->getManager();
//
//        foreach ($piece->getPieceLignes() as $ligne):
//            if (!array_key_exists($ligne->getTauxTva(), $base_tva)) {
//                $base_tva[$ligne->getTauxTva()] = $ligne->getTotalHt() * $ligne->getTauxTva() / 100;
//            } else {
//                $base_tva[$ligne->getTauxTva()] = $base_tva[$ligne->getTauxTva()] + $ligne->getTotalHt() * $ligne->getTauxTva() / 100;
//            }
//        endforeach;
//
//        $html = $this->renderView('SysteoVenteBundle:piece:imprimer.html.twig', array(
//            'piece' => $piece,
//            'base_tva' => $base_tva,
//            'montant_en_toute_lettre' => $this->getMontantEnTouteLettre($piece->getMontantTtc()),
//            'config' => $em->getRepository('SysteoConfigBundle:Config')->findOneById(1),
//            'server'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']
//        ));
//
//        return new Response(
//                $this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
//            'Content-Type' => 'application/pdf',
//            'Content-Disposition' => 'inline; filename="'.$piece->getType().'-'.$piece->getNumero().'.pdf"'
//                )
//        );
      
        $snappy=$this->get('knp_snappy.pdf');
        $fileName="first_pdf";
        $webSiteUrl=" http://127.0.0.1/harice/web/app_dev.php/piece/1";
        
        return new Response($snappy->getOutputFromHtml($webSiteUrl),
                200,
                array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'.pdf"'
                )
                );
    }

    /**
     * Finds and displays a piece entity.
     *
     * @Route("/{id}", name="piece_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Piece $piece) {

        $deleteForm = $this->createDeleteForm($piece);

        $base_tva = [];

        foreach ($piece->getPieceLignes() as $ligne):
            if (!array_key_exists($ligne->getTauxTva(), $base_tva)) {
                $base_tva[$ligne->getTauxTva()] = $ligne->getTotalHt() * $ligne->getTauxTva() / 100;
            } else {
                $base_tva[$ligne->getTauxTva()] = $base_tva[$ligne->getTauxTva()] + $ligne->getTotalHt() * $ligne->getTauxTva() / 100;
            }
        endforeach;

        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);
        
        return $this->render('SysteoVenteBundle:piece:show.html.twig', array(
                    'piece' => $piece,
                    'delete_form' => $deleteForm->createView(),
                    'base_tva' => $base_tva,
                    'tauxFodec' => $conf->getFodec(),
        ));
    }

    /**
     * Displays a form to edit an existing piece entity.
     *
     * @Route("/{id}/edit", name="piece_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Piece $piece) {
        if (count($piece->getPieceLignes()) === 0) {
            $piece->addPieceLigne(new PieceLigne());
        }

        $originalLignes = new ArrayCollection();

        foreach ($piece->getPieceLignes() as $ligne):
            $originalLignes->add($ligne);
        endforeach;

        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);

        $edit_form = $this->createForm('Systeo\VenteBundle\Form\PieceType', $piece, [
            'entity_manager' => $em
        ]);

        $ckeditor = $this->createForm('Systeo\VenteBundle\Form\DesignationType');

        $edit_form->handleRequest($request);



        if ($edit_form->isSubmitted() && $edit_form->isValid()) {

            foreach ($originalLignes as $ligne) {
                if (false === $piece->getPieceLignes()->contains($ligne)) {
                    $em->remove($ligne);
                }
            }
            
            $piece->setSolde($piece->getCalculatedSolde());

            $em->persist($piece);
            $em->flush();

            $this->addFlash('success', $this->getMessageFlash("edit", $piece->getType()));

            return $this->redirectToRoute('piece_show', array('id' => $piece->getId()));
        }

        return $this->render('SysteoVenteBundle:piece:edit.html.twig', array(
                    'piece' => $piece,
                    'edit_form' => $edit_form->createView(),
                    'numero' => $this->getLastNumero($piece->getType()),
                    'tier' => $this->getTier($request),
                    'timbre' => $conf->getDroitTimbre(),
                    'tauxFodec' => $conf->getFodec(),
                    'ckeditor' => $ckeditor->createView()
        ));
    }

    /**
     * Deletes a piece entity.
     *
     * @Route("/{id}", name="piece_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Piece $piece) {
        $form = $this->createDeleteForm($piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = $piece->getType();
            
            $piece_id = $piece->getId();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->getRepository('SysteoReglementBundle:Reglement')->removeRelatedEntity('piece',$piece_id); 
            
            $em->remove($piece);
            $em->flush();

            $this->addFlash('success', $this->getMessageFlash("edit", $type));
        }

        return $this->redirectToRoute('piece_index');
    }

    /**
     * Creates a form to delete a piece entity.
     *
     * @param Piece $piece The piece entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Piece $piece) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('piece_delete', array('id' => $piece->getId())))
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
        $data = $data['piece_search'];

        foreach ($data as $k => $v) {

            if ($this->checkField($data, 'tier')) {
                $url['tier'] = $data['tier'];
            }

            if ($this->checkField($data, 'type')) {
                $url['type'] = $data['type'];
            }

            if ($this->checkField($data, 'montantHt')) {
                $url['montantHt'] = $data['montantHt'];
                if ($this->checkField($data, 'montantHt_comparateur')) {
                    $url['montantHt_comparateur'] = $data['montantHt_comparateur'];
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
            
            if ($this->checkField($data, 'solde')) {
                $url['solde'] = $data['solde'];
                if ($this->checkField($data, 'solde_comparateur')) {
                    $url['solde_comparateur'] = $data['solde_comparateur'];
                }
            }

            if ($this->checkField($data, 'date_debut')) {
                $url['date_debut'] = $this->getDate($data['date_debut']);
            }
            if ($this->checkField($data, 'date_fin')) {
                $url['date_fin'] = $this->getDate($data['date_fin']);
            }
            

            if ($this->checkField($data, 'montantFodec')) {
                $url['montantFodec'] = $data['montantFodec'];
                if ($this->checkField($data, 'montantFodec_comparateur')) {
                    $url['montantFodec_comparateur'] = $data['montantFodec_comparateur'];
                }
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
     * @param STRING $type
     * @return int
     */
    private function getLastNumero($type) {

        $em = $this->getDoctrine()->getManager();

        $piece = $em->getRepository("SysteoVenteBundle:Piece")->findOneBy(['type' => $type], ['date' => 'DESC', 'numero' => 'DESC']);

        if (is_null($piece)) {
            return 1;
        }

        $date_debut = new \DateTime(date('Y') . '-01-01');
        $date_fin = new \DateTime(date('Y') . '-12-31');

        if ($piece->getDate() >= $date_debut && $piece->getDate() <= $date_fin) {
            return $piece->getNumero() + 1;
        }

        return 1;
    }

    /**
     * 
     * @param Request $request
     * @return string
     */
    private function getPieceType(Request $request) {
        $type = 'Facture';

        if ($request->query->get('type') === "Devis") {
            $type = 'Devis';
        } elseif ($request->query->get('type') === "Avoir") {
            $type = 'Avoir';
        }

        return $type;
    }

    private function getTier(Request $request) {

        if ($request->query->get('tier')) {

            $em = $this->getDoctrine()->getManager();
            return $em->getRepository("SysteoTierBundle:Tier")->findOneById($request->query->get('tier'));
        }

        return null;
    }

    /**
     * 
     * @param STRING $action
     * @param STRING $type
     */
    private function getMessageFlash($action, $type) {

        $message = [];

        $message['new']['Facture'] = 'Nouvelle facture créée avec succès';
        $message['new']['Devis'] = 'Nouveau devis créé avec succès';
        $message['new']['Avoir'] = 'Nouvelle facture d\'avoir créée avec succès';

        $message['edit']['Facture'] = 'Facture modifiée avec succès';
        $message['edit']['Devis'] = 'Devis modifié avec succès';
        $message['edit']['Avoir'] = 'Dacture d\'avoir modifiée avec succès';

        $message['delete']['Facture'] = 'Facture supprimée avec succès';
        $message['delete']['Devis'] = 'Devis supprimé avec succès';
        $message['delete']['Avoir'] = 'Dacture d\'avoir supprimée avec succès';

        if (isset($message[$action][$type])) {
            return $message[$action][$type];
        }

        return null;
    }

    private function getMontantEnTouteLettre($montant) {

        $montant_en_toute_lettre = '';

        $a = Array("", "Un", "Deux", "Trois", "Quatre", "Cinq", "Six", "Sept", "Huit", "Neuf", "Dix", "Onze", "Douze", "Treize", "Quatorze", "Quinze", "Seize", "Dix Sept",
            "Dix Huit", "Dix Neuf", "Vingts", "Vingt et Un", "Vingt Deux", "Vingt Trois", "Vingt Quatre", "Vingt Cinq", "Vingt Six", "Vingt Sept", "Vingt Huit", "Vingt Neuf", "Trente", "Trente et Un", "Trente Deux", "Trente Trois", "Trente Quatre", "Trente Cinq", "Trente Six", "Trente Sept", "Trente Huit", "Trente Neuf", "Quarante", "Quarante et Un", "Quarante Deux", "Quarante Trois", "Quarante Quatre", "Quarante Cinq", "Quarante Six", "Quarante Sept", "Quarante Huit", "Quarante Neuf", "Cinquante", "Cinquante et Un", "Cinquante Deux", "Cinquante Trois", "Cinquante Quatre", "Cinquante Cinq", "Cinquante Six", "Cinquante Sept", "Cinquante Huit", "Cinquante Neuf", "Soixante", "Soixante et Un", "Soixante Deux", "Soixante Trois", "Soixante Quatre", "Soixante Cinq", "Soixante Six", "Soixante Sept", "Soixante Huit", "Soixante Neuf", "Soixante Dix", "Soixante et Onze", "Soixante Douze", "Soixante Treize", "Soixante Quatorze", "Soixante Quinze", "Soixante Seize", "Soixante Dix Sept", "Soixante Dix Huit", "Soixante Dix Neuf", "Quatre-Vingts", "Quatre-Vingt Un", "Quatre-Vingt Deux", "Quatre-Vingt Trois", "Quatre-Vingt Quatre", "Quatre-Vingt Cinq", "Quatre-Vingt Six", "Quatre-Vingt Sept", "Quatre-Vingt Huit", "Quatre-Vingt Neuf", "Quatre-Vingt Dix", "Quatre-Vingt Onze", "Quatre-Vingt Douze", "Quatre-Vingt Treize", "Quatre-Vingt Quatorze", "Quatre-Vingt Quinze", "Quatre-Vingt Seize", "Quatre-Vingt Dix Sept", "Quatre-Vingt Dix Huit", "Quatre-Vingt Dix Neuf");

        $b = $montant;

        if (strlen($b) <> 13) {
            $cctt = 13 - strlen($b);

            for ($i = 1; $i <= $cctt; $i++) {
                $b = '0' . $b;
            }
        }

        $variable = substr($b, 0, 3);

        $lettre = '';

        if (substr($variable, 0, 1) == '1') {
            $centaine = ' Cent';
        } elseif (substr($variable, 0, 1) == '0') {
            $centaine = '';
        } else {
            $centaine = $a[substr($variable, 0, 1)] . ' Cents';
        }
        if (substr($variable, 1, 1) <> '0') {
            $lettre = $centaine . ' ' . $a[substr($variable, 1, 2)];
        } else {
            $lettre = $centaine . ' ' . $a[substr($variable, 2, 2)];
        }
        if ($variable == '000') {
            $lettre = '';
            $din = '1';
        } elseif ($variable == '001') {
            $lettre .= ' Million';
            $din = '2';
        } else {
            $lettre .= ' Millions';
            $din = '2';
        }

        $variable = substr($b, 3, 3);

        $lettre1 = '';
        if (substr($variable, 0, 1) == '1') {
            $centaine = ' Cent';
        } elseif (substr($variable, 0, 1) == '0') {
            $centaine = '';
        } else {
            $centaine = $a[substr($variable, 0, 1)] . ' Cents';
        }
        if (substr($variable, 1, 1) <> '0') {
            $lettre1 = $centaine . ' ' . $a[substr($variable, 1, 2)];
        } elseif (substr($variable, 2, 2) <> '1') {
            $lettre1 = $centaine . ' ' . $a[substr($variable, 2, 2)];
        }
        if ($variable == '000') {
            $lettre1 = '';
            $din = '1';
        } elseif ($variable == '001') {
            $lettre1 .= ' Mille';
            $din = '2';
        } else {
            $lettre1 .= ' Milles';
            $din = '2';
        }

        $variable = substr($b, 6, 3);
        $lettre2 = '';
        if (substr($variable, 0, 1) == '1') {
            $centaine = ' Cent';
        } elseif (substr($variable, 0, 1) == '0') {
            $centaine = '';
        } else {
            $centaine = $a[substr($variable, 0, 1)] . ' Cents';
        }
        if (substr($variable, 1, 1) <> '0') {
            $lettre2 = $centaine . ' ' . $a[substr($variable, 1, 2)];
        } else {
            $lettre2 = $centaine . ' ' . $a[substr($variable, 2, 2)];
        }
        if (($variable == '000') && ($din == '1')) {
            $lettre2 = '';
        } if (($variable == '000') && ($din == '2')) {
            $lettre2 = ' Dinars';
        } elseif (($variable == '001') && ($din == '1')) {
            $lettre2 .= ' Dinar';
        } else {
            $lettre2 .= ' Dinars';
        }

        $variable = substr($b, 10, 3);
        $lettre3 = '';
        if ($variable == '000') {
            $lettre3 = '';
        } elseif ($variable == '001') {
            $lettre3 .= ' 001 Millime';
        } else {
            $lettre3 .= substr($b, 10, 3) . ' Millimes';
        }

        $montant_en_toute_lettre = $lettre . ' ' . $lettre1 . ' ' . $lettre2 . ' ' . $lettre3;

        return $montant_en_toute_lettre;
    }

}
