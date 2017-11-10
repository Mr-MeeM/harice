<?php

namespace Systeo\FileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Systeo\FileBundle\Entity\File;
use Systeo\FileBundle\Form\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * File controller.
 *
 * @Route("/file")
 */
class FileController extends Controller {

    private $images_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'tiff'];
    private $authorized_extension = ['ods','odt','txt','pdf','xlsx','xls','docx','doc','jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'tiff'];
    private $url_params = [
        'entity' => null,
        'entity_id' => null,
        'gallery_photo' => false,
        'liste_doc' => false,
        'type' => null
    ];

    /**
     * Lists all File entities.
     *
     * @Route("/", name="file_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {
        $file = new File();
        $form = $this->createForm('Systeo\FileBundle\Form\FileSearchType', $file);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('file_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $files = $paginator->paginate(
                $em->getRepository('SysteoFileBundle:File')->MyFindAll($request->query->all(), $this->getUser()), /* query NOT result */ 
                $request->query->getInt('page', 1)/* page number */, 
                25/* limit per page */
        );

        return $this->render('SysteoFileBundle:file:index.html.twig', array(
                    'files' => $files,
                    'parametre' => $parametre = $this->parametreUrl($request->query),
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new File entity.
     *
     * @Route("/new", name="file_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $file = new File();

        $form = $this->createForm('Systeo\FileBundle\Form\FileType', $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $doc = $file->getFileName();
            
            if(!in_array(strtolower($doc->guessExtension()),$this->authorized_extension)){
                return;
            }

            $fileName = md5(uniqid()) . '.' . $doc->guessExtension();

            $path = $this->createPath($file->getEntity(), $file->getEntityId());

            if ($path) {
                $doc->move(
                        $path, $fileName
                );
            }
            $file->setFileName($fileName);
            $file->setExtension(strtolower($doc->guessExtension()));

            $is_image = false;
            if (in_array(strtolower($doc->guessExtension()), $this->images_extensions)) {
                $is_image = true;
            }

            $file->setImage($is_image);

            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            return $this->redirectToRoute('file_show', array('id' => $file->getId()));
        }

        return $this->render('SysteoFileBundle:file:new.html.twig', array(
                    'file' => $file,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Loading files ; liste files & photo gallery
     *
     * @Route("/ajaxloadfiles", name="ajaxloadfiles")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function filesAction(Request $request) {

        $this->getAllUrlParams($request->query->all());

        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('SysteoFileBundle:File')->SearchByEntity($this->url_params['entity'], $this->url_params['entity_id']);
        $files_view = $this->fileOrganize($files, $this->url_params['type']);

        return $this->render('SysteoFileBundle:file:files.html.twig', array(
                    'type' => $this->url_params['type'],
                    'files' => $files_view,
                    'public'=>true
        ));
    }
    
    /**
     * Loading files ; liste files & photo gallery
     *
     * @Route("/ajax-load-files", name="ajaxloadhiddenfiles")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function filesHiddenAction(Request $request) {

        $this->getAllUrlParams($request->query->all());
        
        $em = $this->getDoctrine()->getManager();

        $files = $em->getRepository('SysteoFileBundle:File')->SearchByEntity($this->url_params['entity'], $this->url_params['entity_id']);
        $files_view = $this->fileOrganize($files, $this->url_params['type'], false);

        return $this->render('SysteoFileBundle:file:files.html.twig', array(
                    'type' => $this->url_params['type'],
                    'files' => $files_view,
                    'public'=>false
        ));
    }

    /**
     * Creates a new File entity with ajax.
     *
     * @Route("/ajaxnew", name="addajaxfile")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function addajaxAction(Request $request) {
        $file = new File();
        $form = $this->createForm('Systeo\FileBundle\Form\FileType', $file);
        $form->handleRequest($request);

        $this->getAllUrlParams($request->query->all());

        //var_dump($files_view);

        if ($form->isSubmitted() && $form->isValid()) {

            $doc = $file->getFileName();
            $extension = $doc->guessExtension();
            $name = $doc->getClientOriginalName();

            $fileName = md5(uniqid()) . '.' . $extension;

            $path = $this->createPath($file->getEntity(), $file->getEntityId());


            if ($path) {
                $doc->move(
                        $path, $fileName
                );
            }

            $file->setFileName($fileName);
            $file->setName($name);

            $file->setExtension(strtolower($extension));

            $is_image = false;
            if (in_array(strtolower($extension), $this->images_extensions)) {
                $is_image = true;
            }

            $file->setImage($is_image);

            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();
            
            if (in_array(strtolower($extension), $this->authorized_extension)) {
                if (in_array(strtolower($extension), $this->images_extensions)) {
                    return new Response('photo');
                } else {
                    return new Response('doc');
                }
            }else{
                unlink($path.'/'.$fileName);
                return new Response('erreur-format');
            }
            
        }

        return $this->render('SysteoFileBundle:file:addajax.html.twig', array(
                    'file' => $file,
                    'form' => $form->createView(),
                    'params' => $this->url_params
        ));
    }

    /**
     * Creates a new File entity with ajax.
     *
     * @Route("/ajax-add-simple-file", name="simple_file_ajax_add")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function simpleAjaxFileAction(Request $request) {

        $file = new File();
        $form = $this->createForm('Systeo\FileBundle\Form\SimpleFileType', $file);
        $form->handleRequest($request);

        $this->getAllUrlParams($request->query->all());

        $response = [
            'success' => false,
            'file_name' => '',
            'error_type' => '',
            'is_image' => false,
            'extension' => '',
            'original_name'=>''
        ];

        if ($form->isSubmitted() && $form->isValid()) {

            $doc = $file->getFileName();
            $extension = $doc->guessExtension();
            $name = $doc->getClientOriginalName();

            $fileName = $file->getEntityId() . $file->getEntity() . md5(uniqid()) . '.' . $extension;

            $path = $this->getParameter('tmp-files');


            if ($doc->move($path . '/', $fileName)) {
                $response['success'] = true;
                $response['file_name'] = $fileName;
                $response['extension'] = $extension;
                $response['original_name'] = $this->getLongName($name);
            }

            $file->setExtension(strtolower($extension));
            
            if (in_array(strtolower($extension), $this->authorized_extension)) {
                 if (in_array(strtolower($extension), $this->images_extensions)) {

                    $response['is_image'] = true;
                    $params = getimagesize($path . '/' . $fileName);

                    $response['ratio'] = min([1, round($params[1] / $params[0], 2)]);
                }
            }else{
                unlink($path.'/'.$fileName);
                $response['success'] = false;
            }
           



            return new Response(json_encode($response));
        }
        
        
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{id}/edit", name="file_edit_ajax")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, File $file) {
        $deleteForm = $this->createDeleteForm($file);
        $editForm = $this->createForm('Systeo\FileBundle\Form\FileShowType', $file);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            $this->addFlash('success', 'Le fichier a été mis à jour avec succès.');
        }

        return $this->render('SysteoFileBundle:file:edit.html.twig', array(
                    'file' => $file,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/ajax/{id}", name="file_delete_ajax")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteajaxAction(Request $request, File $file) {
        $public = true;
        if($request->query->get('public')!=''){
            $public = false;
        }
        $path = $this->checkIfFileExists($file,$public);

        if ($path && $public) {
            unlink($this->getParameter('files') . '/' . $path);
        }elseif ($path && !$public) {
            unlink($this->getParameter('hidden-files') . '/' . $path);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();

        return new Response("ok");
    }

    /**
     * Finds and displays a File entity.
     *
     * @Route("/{id}", name="file_show")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Request $request, File $file) {
        $deleteForm = $this->createDeleteForm($file);
        $form = $this->createForm('Systeo\FileBundle\Form\FileShowType', $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            $this->addFlash('success', 'Le fichier a été mis à jour avec succès.');

            return $this->redirectToRoute('file_show', array('id' => $file->getId()));
        }

        return $this->render('SysteoFileBundle:file:show.html.twig', array(
                    'file' => $file,
                    'delete_form' => $deleteForm->createView(),
                    'form' => $form->createView()
        ));
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/{id}", name="file_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, File $file) {
        $form = $this->createDeleteForm($file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $path = $this->checkIfFileExists($file);

            if ($path) {
                unlink($this->getParameter('files') . '/' . $path);
            }

            $em->remove($file);
            $em->flush();

            $this->addFlash('success', 'Le fichier a été supprimé avec succès.');
        }

        return $this->redirectToRoute('file_index');
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/open-tmp-file/{fichier}", name="file_open_tmp")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function openFileTmp($fichier = null) {

        $fileName = $path = $this->getParameter('tmp-files') . '/' . $fichier;

        $response = new BinaryFileResponse($fileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);

        return $response;
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/open-file/{fichier}", name="file_open")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function openFile($fichier) {

        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('SysteoFileBundle:File')->findOneBy(['fileName' => $fichier]);

        $fileName = $this->getParameter('hidden-files') . '/' . $file->getEntity() . '/' . $file->getEntityId() . '/' . $file->getFileName();

        $response = new BinaryFileResponse($fileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);

        return $response;
    }
    
    /**
     * Deletes a File entity.
     *
     * @Route("/open-file/{type}/{id}/{fichier}", name="file_open_specific")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function openFileSpecific($type=null,$id=null,$fichier=null) {
        
        if(empty($type) || empty($id) || empty($fichier)){
            return;
        }
        
        if($type == 'ec'){
            $fileName = $this->getParameter('files').'/'.'EspaceCommun'.'/'.$id.'/'.$fichier;
        }elseif($type == 'tel'){
            $fileName = $this->getParameter('telechargement-files').'/'.$id.'/'.$fichier;
        }

        $response = new BinaryFileResponse($fileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);

        return $response;
    }

    /**
     * Creates a form to delete a File entity.
     *
     * @param File $file The File entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(File $file) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('file_delete', array('id' => $file->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    private function createPath($entity, $entity_id) {
        
        $path = $this->getParameter('files');
        
        if($this->url_params['public']){
            $path = $this->getParameter('hidden-files');
        }

        if (!empty($entity)) {
            $path .=  '/' . $entity;

            if (!is_dir($path)) {
                if (false === @mkdir($path, 0777, true) && !is_dir($path)) {
                    return false;
                }
            }

            if (!empty($entity_id)) {

                $path .= '/' . $entity_id;

                if (!is_dir($path)) {
                    if (false === @mkdir($path, 0777, true) && !is_dir($path)) {
                        return false;
                    }
                }
            }

        }
        return $path;
    }

    /**
     * 
     * @param type $files
     * @return array
     */
    private function fileOrganize($files, $type, $public = true) {
        $organized = [];

        foreach ($files as $f) {

            $path = $this->checkIfFileExists($f,$public);

            if ($path) {
                $path_parts = pathinfo($path);
                if($public){
                    if ($type === 'photo' && in_array(strtolower($path_parts['extension']), $this->images_extensions)) {
                        $organized[] = [
                            'id' => $f->getId(),
                            'filename' => $f->getFileName(),
                            'name' => $f->getName(),
                            'path' => $path
                        ];
                    } elseif ($type === 'doc' && !in_array(strtolower($path_parts['extension']), $this->images_extensions)) {
                        $organized[] = [
                            'id' => $f->getId(),
                            'filename' => $f->getFileName(),
                            'name' => $f->getName(),
                            'path' => $path
                        ];
                    }
                }else{
                    $organized[] = [
                        'id' => $f->getId(),
                        'filename' => $f->getFileName(),
                        'name' => $f->getName(),
                        'path' => $path
                    ];
                }
                
            }
        }
        return $organized;
    }

    /**
     * 
     * @param File $file
     * @return boolean|string
     */
    private function checkIfFileExists(File $file,$public = true) {

        $path = '';

        if ($file->getPath()) {
            $path = $file->getPath() . '/' . $file->getFileName();
        } else {
            $path = $file->getEntity() . '/' . $file->getEntityId() . '/' . $file->getFileName();
        }
        
        if ($public &&  file_exists($this->getParameter('files') . '/' . $path)) {
            return $path;
        }elseif (!$public &&  file_exists($this->getParameter('hidden-files') . '/' . $path)) {
            return $path;
        }

        return false;
    }

    /**
     * 
     * @param ARRAY $data 
     */
    private function getAllUrlParams($data) {
        
        $this->url_params['public'] = true;

        if (isset($data['entity'])) {
            $this->url_params['entity'] = $data['entity'];
        }

        if (isset($data['entity_id'])) {
            $this->url_params['entity_id'] = $data['entity_id'];
        }

        if (isset($data['gallery_photo']) && $data['gallery_photo'] === '1') {
            $this->url_params['gallery_photo'] = true;
        }

        if (isset($data['liste_doc']) && $data['liste_doc'] === '1') {
            $this->url_params['liste_doc'] = true;
        }

        if (isset($data['type'])) {
            $this->url_params['type'] = $data['type'];
        }

        if (isset($data['target_field'])) {
            $this->url_params['target_field'] = $data['target_field'];
        }
        
        if (isset($data['public'])) {
            $this->url_params['public'] = $data['public'];
        }
        
    }

    /**
     * 
     * @param array $data $request->query->all()
     * @return array URL parameters
     */
    private function buildSearchUrl($data) {
        $url = [];
        foreach ($data as $k => $v) {
            if (isset($data['file_search']['search']) && !empty($data['file_search']['search'])) {
                $url['search'] = $data['file_search']['search'];
            }

            if (isset($data['file_search']['category']) && $data['file_search']['category'] != '') {
                $url['category'] = $data['file_search']['category'];
            }
        }

        return $url;
    }

    private function parametreUrl($paramUrl) {
        $parametre = [];
        $parametre["search"] = $paramUrl->get('search');
        $parametre["category"] = $paramUrl->get('active');
        return $parametre;
    }
    
    private function getLongName($name){
        $tab = explode('.', $name);
        
        $extension = $tab[count($tab)-1];
        
        $longueur = strlen($name) - strlen($extension);
        
        if(strlen($name)>25){
            $new_name = substr(substr($name, 0,$longueur), 0, 25);
        
            return $new_name.'....'.$extension;
        }
        
        return $name;
        
    }

}
