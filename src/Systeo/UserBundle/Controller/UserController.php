<?php

namespace Systeo\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Systeo\UserBundle\Entity\User;
use Systeo\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller {

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {
        $user = new User();
        $form = $this->createForm('Systeo\UserBundle\Form\UserSearchType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('user_index', $url);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $users = $paginator->paginate(
                $em->getRepository('SysteoUserBundle:User')->MyFindAll($request->query->all()), /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 5/* limit per page */
        );

        return $this->render('SysteoUserBundle:user:index.html.twig', array(
                    'users' => $users,
                    'parametre' => $parametre = $this->parametreUrl($request->query),
                    'form' => $form->createView()
        ));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $user = new User();
        $form = $this->createForm('Systeo\UserBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->all();

            $user->setPassword($this->hashPassword($data['user']['password'], $user));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Nouvel utilisateur ajouté avec succès.');

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('SysteoUserBundle:user:new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }
    
    
    /**
     * create admin user
     *
     * @Route("/verifications", name="user_verification")
     * @Method("GET")
     */
    public function verification(){
        
        $this->createAdmin();
        $this->createConf();
        $this->createCaisse();
        
        $paths = [
            '/files'=>is_writable($this->getParameter('hidden-files')),
            '/web/files/Parametres'=>is_writable($this->getParameter('files').'/Parametres'),
            '/web/tmp'=>is_writable($this->getParameter('tmp-files')),
        ];
        
        
        return $this->render('SysteoUserBundle:user:verification.html.twig', array(
            'paths'=>$paths
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(User $user) {
        $deleteForm = $this->createDeleteForm($user);
        
        return $this->render('SysteoUserBundle:user:show.html.twig', array(
                    'user' => $user,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, User $user) {
        $editForm = $this->createForm('Systeo\UserBundle\Form\UserEditType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $data = $request->request->all();

            if (!empty($data['user_edit']['passwordEdit'])) {
                $user->setPassword($this->hashPassword($data['user_edit']['passwordEdit'], $user));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'La mise à jour de l\'utilisateur a été effectuée.');

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('SysteoUserBundle:user:edit.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
        ));
    }
    

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, User $user) {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{username}/edit-password", name="user_password")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function modifPasswordAction(Request $request, User $user) {

        $connected_user = $this->getUser();

        if ($user->getId() != $connected_user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        $passwordForm = $this->createForm('Systeo\UserBundle\Form\UserPasswordType', $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $data = $request->request->all();

            $user->setPassword($this->hashPassword($data['user_password']['password'], $user));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès.');
        }

        return $this->render('SysteoUserBundle:user:password.html.twig', array(
                    'user' => $user,
                    'password_form' => $passwordForm->createView(),
        ));
    }
    
    
    /**
     * 
     * @return type
     */
    private function createAdmin(){
        $em = $this->getDoctrine()->getManager();
        
        if(count($em->getRepository('SysteoUserBundle:User')->findAll())){
            return;
        }
        
        $user = new User();
        
        $user->setActive(true);
        $user->setEmail('contact@systeo.biz');
        $user->setFirstName('admin');
        $user->setLastName('admin');
        $user->setUsername('admin');
        $user->setPassword($this->hashPassword('admin', $user));
        $user->setRoles(['Admin'=>'ROLE_ADMIN']);
        
        $em->persist($user);
        $em->flush();
        
    }
    
    /**
     * 
     * @return type
     */
    private function createConf(){
        $em = $this->getDoctrine()->getManager();
        
        if(count($em->getRepository('SysteoConfigBundle:Config')->findAll())){
            return;
        }
        
        $config = new \Systeo\ConfigBundle\Entity\Config();
        
        $config->setAdresse('Rte de Tunis Km 10');
        $config->setCompanyName('SYSTEO');
        $config->setCouleur1('#FF0000');
        $config->setDroitTimbre(0.5);
        $config->setEmail("contact@systeo.biz");
        $config->setFax('00216 74 400 080');
        $config->setTauxTva('0;6;18');
        $config->setTel('00216 74 415 649');
        $config->setWeb('www.systeo.biz');
        
        $em->persist($config);
        $em->flush();
        
    }
    
     /**
     * 
     * @return type
     */
    private function createCaisse(){
        $em = $this->getDoctrine()->getManager();
        
        if(count($em->getRepository('SysteoBanqueBundle:BanqueCompte')->findAll())){
            return;
        }
        
        $compte = new \Systeo\BanqueBundle\Entity\BanqueCompte();
        
        $compte->setBanque('Caisse');
        $compte->setName('Caisse');
        $compte->setRib('01');
        
        $em->persist($compte);
        $em->flush();
        
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * 
     * @param STRING
     * @return STRING
     */
    private function hashPassword($paswordClear, $user) {

        $encoder = $this->container->get('security.password_encoder');
        return $encoder->encodePassword($user, $paswordClear);
    }

    /**
     * 
     * @param array $data $request->query->all()
     * @return array URL parameters
     */
    private function buildSearchUrl($data) {
        $url = [];
        foreach ($data as $k => $v) {
            if (isset($data['user_search']['search']) && !empty($data['user_search']['search'])) {
                $url['search'] = $data['user_search']['search'];
            }

            if (isset($data['user_search']['active']) && $data['user_search']['active'] != '') {
                $url['active'] = $data['user_search']['active'];
            }
        }

        return $url;
    }

    private function parametreUrl($paramUrl) {
        $parametre = [];
        $parametre["search"] = $paramUrl->get('search');
        $parametre["active"] = $paramUrl->get('active');
        return $parametre;
    }

}
