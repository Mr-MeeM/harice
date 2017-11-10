<?php

namespace Systeo\ConfigBundle\Controller;

use Systeo\ConfigBundle\Entity\Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Config controller.
 *
 * @Route("config")
 */
class ConfigController extends Controller
{
    

    /**
     * Displays a form to edit an existing config entity.
     *
     * @Route("/{id}/edit", name="config_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Config $config)
    {
        $old_logo = $config->getLogo();
        
        
        $editForm = $this->createForm('Systeo\ConfigBundle\Form\ConfigType', $config);
        $fileForm = $this->createForm('Systeo\FileBundle\Form\SimpleFileType');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            if($config->getLogo() && $old_logo !== $config->getLogo()){
                $tmp_img = $this->getParameter('tmp-files').'/'.$config->getLogo();
                $folder = $this->getParameter('files').'/Parametres';
                
                $final_image = $folder.'/'.$config->getLogo();
                
                if(!is_dir($folder)){
                   if (false === @mkdir($folder, 0777, true) && !is_dir($folder)) {
                        $this->addFlash('danger', 'Impossible d\'accéder au répertoire paramètres.');
                        return $this->redirectToRoute('config_edit', array('id' => $config->getId()));
                   }
                }
                
                if(!rename($tmp_img, $final_image)){
                    $this->addFlash('danger', 'Déplacement du logo a echoué.');
                    return $this->redirectToRoute('config_edit', array('id' => $config->getId()));
                }
                
            }
            
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'La mise à jour de votre configuration a été effectuée avec succès.');

            return $this->redirectToRoute('config_edit', array('id' => $config->getId()));
        }

        return $this->render('SysteoConfigBundle:config:edit.html.twig', array(
            'config' => $config,
            'edit_form' => $editForm->createView(),
            'file_form'=>$fileForm->createView()
        ));
    }
    
    
}
