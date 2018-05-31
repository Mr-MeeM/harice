<?php

namespace Systeo\VenteBundle\Controller;

use Systeo\VenteBundle\Entity\PieceExport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Systeo\VenteBundle\Entity\PieceExportLigne;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Piece controller.
 *
 * @Route("piece-export")
 */
class PieceExportController extends Controller {

    /**
     * Finds and displays a piece entity.
     *
     * @Route("/{testurl", name="testurl")
     */
    public function testttAction() {
        
    }
    /**
     * Finds and displays a piece entity.
     *
     * @Route("/{id}/html2pdf", name="html2pdf")
     */
    public function imprimerAction(Piece $piece) {
        $em = $this->getDoctrine()->getManager();

        $html = $this->renderView('SysteoVenteBundle:piece-export:imprimer.html.twig', array(
            'piece' => $piece,
            'config' => $em->getRepository('SysteoConfigBundle:Config')->findOneById(1),
            'server' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']
        ));

//on appelle le service html2pdf
        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'fr', true, 'UTF-8');
//real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('real');
//writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
//Output envoit le document PDF au navigateur internet
        return new Response(
                $html2pdf->Output('facture_export.pdf'), 150, array('Content-Type' => 'application/pdf')
        );
    }
    /**
     * Lists all piece entities.
     *
     * @Route("/", name="piece_export_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {

        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('piece_export_index', $url);
            }
        } elseif (empty($request->query->all())) {
            return $this->redirectToRoute('piece_export_index', ['type' => 'Facture']);
        }



        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $pieces = $paginator->paginate(
                $em->getRepository('SysteoVenteBundle:PieceExport')->MyFindAll($request->query->all()), /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 25/* limit per page */
        );

        $totaux = $em->getRepository('SysteoVenteBundle:PieceExport')->getSumOperations($request->query->all());

        return $this->render('SysteoVenteBundle:piece-export:index.html.twig', array(
                    'pieces' => $pieces,
                    'totaux' => $totaux,
        ));
    }

    /**
     * Creates a new piece entity.
     *
     * @Route("/new", name="piece_export_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $piece = new PieceExport();

        if (count($piece->getPieceExportLignes()) === 0) {
            $piece->addPieceExportLigne(new PieceExportLigne());
        }

        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);

        $form = $this->createForm('Systeo\VenteBundle\Form\PieceExportType', $piece, [
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

            return $this->redirectToRoute('piece_export_show', array('id' => $piece->getId()));
        }

        return $this->render('SysteoVenteBundle:piece-export:new.html.twig', array(
                    'piece' => $piece,
                    'form' => $form->createView(),
                    'numero' => $this->getLastNumero($type),
                    'tier' => $this->getTier($request),
                    'timbre' => $conf->getDroitTimbre(),
                    'ckeditor' => $ckeditor->createView()
        ));
    }

    /**
     * get solde des pieces commerciales  
     *
     * @Route("/affecter-export-local", name="piece_export_affecter_local")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function affecterAction(Request $request) {

        $type = "affecter";

        if ($request->query->get("type") === 'desaffecter') {
            $type = "desaffecter";
        }

        if ($type === "affecter") {
            return $this->affecter($request->query->get('vue'), $request->query->get('piece_id'), $request->query->get('piece_export_id'));
        } else {
            return $this->desaffecter($request->query->get('vue'), $request->query->get('piece_export_id'));
        }

        return new Response('ok');
    }

    /**
     * get solde des pieces commerciales  
     *
     * @Route("/solde-total", name="piece_export_solde_ajax")
     * @Method({"GET","POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function soldeAction(Request $request) {

        if ($request->isMethod('POST')) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('piece_export_solde_ajax', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $pieces = $em->getRepository('SysteoVenteBundle:PieceExport')->MyFindAll($request->query->all(), true);

        $totalSolde = 0;

        foreach ($pieces as $piece):
            $totalSolde += $piece->getSolde();
        endforeach;

        return new \Symfony\Component\HttpFoundation\Response(number_format($totalSolde, 2, '.', ' '));
    }


    /**
     * Finds and displays a piece entity.
     *
     * @Route("/{id}/imprimer", name="piece_export_imprimer_one")
     * @Security("has_role('ROLE_USER')")
     */
    public function imprimerOne1Action(PieceExport $piece) {

        $em = $this->getDoctrine()->getManager();

        $html = $this->renderView('SysteoVenteBundle:piece-export:imprimer1.html.twig', array(
            'piece' => $piece,
            'config' => $em->getRepository('SysteoConfigBundle:Config')->findOneById(1),
            'server' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']
        ));

        return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $piece->getType() . '-' . $piece->getNumero() . '-export.pdf"'
                )
        );
    }

    /**
     * Finds and displays a piece entity.
     *
     * @Route("/{id}", name="piece_export_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(PieceExport $piece) {

        $deleteForm = $this->createDeleteForm($piece);

        $base_tva = [];

        foreach ($piece->getPieceExportLignes() as $ligne):
            if (!array_key_exists($ligne->getTauxTva(), $base_tva)) {
                $base_tva[$ligne->getTauxTva()] = $ligne->getTotalHt() * $ligne->getTauxTva() / 100;
            } else {
                $base_tva[$ligne->getTauxTva()] = $base_tva[$ligne->getTauxTva()] + $ligne->getTotalHt() * $ligne->getTauxTva() / 100;
            }
        endforeach;

        return $this->render('SysteoVenteBundle:piece-export:show.html.twig', array(
                    'piece' => $piece,
                    'delete_form' => $deleteForm->createView(),
                    'base_tva' => $base_tva
        ));
    }

    /**
     * Displays a form to edit an existing piece entity.
     *
     * @Route("/{id}/edit", name="piece_export_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, PieceExport $piece) {
        if (count($piece->getPieceExportLignes()) === 0) {
            $piece->addPieceExportLigne(new PieceExportLigne());
        }

        $originalLignes = new ArrayCollection();

        foreach ($piece->getPieceExportLignes() as $ligne):
            $originalLignes->add($ligne);
        endforeach;

        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);

        $edit_form = $this->createForm('Systeo\VenteBundle\Form\PieceExportType', $piece, [
            'entity_manager' => $em
        ]);

        $ckeditor = $this->createForm('Systeo\VenteBundle\Form\DesignationType');

        $edit_form->handleRequest($request);



        if ($edit_form->isSubmitted() && $edit_form->isValid()) {

            foreach ($originalLignes as $ligne) {
                if (false === $piece->getPieceExportLignes()->contains($ligne)) {
                    $em->remove($ligne);
                }
            }

            $em->persist($piece);
            $em->flush();

            $this->addFlash('success', $this->getMessageFlash("edit", $piece->getType()));

            return $this->redirectToRoute('piece_export_show', array('id' => $piece->getId()));
        }

        return $this->render('SysteoVenteBundle:piece-export:edit.html.twig', array(
                    'piece' => $piece,
                    'edit_form' => $edit_form->createView(),
                    'numero' => $this->getLastNumero($piece->getType()),
                    'tier' => $this->getTier($request),
                    'timbre' => $conf->getDroitTimbre(),
                    'ckeditor' => $ckeditor->createView()
        ));
    }

    /**
     * Deletes a piece entity.
     *
     * @Route("/{id}", name="piece_export_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, PieceExport $piece) {
        $form = $this->createDeleteForm($piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = $piece->getType();

            $em = $this->getDoctrine()->getManager();
            $em->remove($piece);
            $em->flush();

            $this->addFlash('success', $this->getMessageFlash("edit", $type));
        }

        return $this->redirectToRoute('piece_export_index');
    }

    /**
     * Creates a form to delete a piece entity.
     *
     * @param Piece $piece The piece entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PieceExport $piece) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('piece_export_delete', array('id' => $piece->getId())))
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
        $data = $data['piece_export_search'];

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

        $piece = $em->getRepository("SysteoVenteBundle:PieceExport")->findOneBy(['type' => $type], ['date' => 'DESC', 'numero' => 'DESC']);

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

    /**
     * 
     * @param string $vue
     * @param int $piece_id
     * @param int $piece_export_id
     * @return boolean
     */
    private function affecter($vue, $piece_id, $piece_export_id) {
        $em = $this->getDoctrine()->getManager();

        if (empty($vue) || empty($piece_id) || empty($piece_export_id)) {
            return $this->redirectToRoute('piece_export_index', array('type' => 'Facture'));
        }


        $pieceExport = $em->getRepository("SysteoVenteBundle:PieceExport")->findOneById($piece_export_id);
        $piece = $em->getRepository("SysteoVenteBundle:Piece")->findOneById($piece_id);

        if (is_null($pieceExport) || is_null($piece)) {
            return $this->redirectToRoute('piece_export_index', array('type' => 'Facture'));
        }


        $pieceExport->setPiece($piece);
        $em->persist($pieceExport);
        $em->flush();

        $this->addFlash('success', 'Affectation effectuée avec succès');

        if ($vue === "detail") {
            return $this->redirectToRoute('piece_export_show', array('id' => $pieceExport->getId()));
        }

        return $this->redirectToRoute('piece_export_index', array('type' => $piece->getType()));
    }

    /**
     * 
     * @param string $vue
     * @param int $piece_export_id
     * @return boolean
     */
    private function desaffecter($vue, $piece_export_id) {
        $em = $this->getDoctrine()->getManager();

        if (empty($vue) || empty($piece_export_id)) {
            return $this->redirectToRoute('piece_export_index', array('type' => 'Facture'));
        }

        $pieceExport = $em->getRepository("SysteoVenteBundle:PieceExport")->findOneById($piece_export_id);

        if (is_null($pieceExport)) {
            return $this->redirectToRoute('piece_export_index', array('type' => 'Facture'));
        }

        $pieceExport->setPiece(null);
        $em->persist($pieceExport);
        $em->flush();

        $this->addFlash('success', 'Desaffectation effectuée avec succès');

        if ($vue === "detail") {
            return $this->redirectToRoute('piece_export_show', array('id' => $pieceExport->getId()));
        }

        return $this->redirectToRoute('piece_export_index', array('type' => $pieceExport->getType()));
    }

}
