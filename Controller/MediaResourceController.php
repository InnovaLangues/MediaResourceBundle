<?php

namespace Innova\MediaResourceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Claroline\CoreBundle\Entity\Workspace\Workspace;

/**
 * Class MediaResourceController
 * 
 * @Route("workspaces/{workspaceId}")
 * @ParamConverter("workspace", class="ClarolineCoreBundle:Workspace\Workspace", options={"mapping": {"workspaceId": "id"}})
 * 
 */
class MediaResourceController extends Controller {

    /**
     * display a media resource 
     * @Route("/view/{id}", requirements={"id" = "\d+"}, name="innova_media_resource_open")
     * @Method("GET")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function openAction(Workspace $workspace, MediaResource $mr) {

        // use of specific method to order regions correctly
        $regions = $this->get('innova_media_resource.manager.media_resource_region')->findByAndOrder($mr);
        if ($mr->getId()) {
            return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array('resource' => $mr, 'edit' => false, 'regions' => $regions, 'workspace' => $workspace));
        } 
        else{
            $this->get('session')->getFlashBag()->set('error', "Aucun exercice trouvé.");
            // TODO redirect to claro resource manager
        }
    }

    /**
     * display a media resource as admin
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="innova_media_resource_administrate")
     * @Method("GET")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function administrateAction(Workspace $workspace, MediaResource $mr) {
        // use of specific method to order regions correctly
        $regions = $this->get('innova_media_resource.manager.media_resource_region')->findByAndOrder($mr);
        if ($mr->getId()) {
            return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array('resource' => $mr, 'edit' => true, 'regions' => $regions, 'workspace' => $workspace));
        } else {
            $this->get('session')->getFlashBag()->set('error', "Aucun media trouvé.");
            // return $this->redirect($this->generateUrl('media_resource_list'));
            // TODO redirect to claro resource manager
        }
    }

    /**
     * 
     * @Route("/list", name="media_resource_list")
     */
    public function listAction() {
        $manager = $this->get('innova_media_resource.manager.media_resource');
        $mediaResources = $manager->getAll();
        return $this->render('InnovaMediaResourceBundle:MediaResource:list.html.twig', array('resources' => $mediaResources));
    }
    

    /**
     * AJAX
     * save after editing (adding and/or configuring regions) a media resource
     * @Route("/save/{id}", requirements={"id" = "\d+"}, name="media_resource_save")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     *  @Method({"POST"})
     */
    public function saveAction(MediaResource $mr) {
        if ($this->getRequest()->isMethod('POST')) {
            $request = $this->container->get('request');
            $data = $request->request->all();
            if (count($data) > 0) {
                $title = $data['title'];
                $mr->setName($title);
                // $this->get('innova_media_resource.manager.media_resource')->updateMediaResourceName($mr, $title);

                $regionManager = $this->get('innova_media_resource.manager.media_resource_region');
                $mr = $regionManager->handleMediaResourceRegions($mr, $data);
                if ($mr) {
                    return new \Symfony\Component\HttpFoundation\Response('La ressource a bien été mise à jour.', 200);
                } else {
                    return new \Symfony\Component\HttpFoundation\Response('Une erreur s\'est produite lors de la mise à jour de la ressource.', 500);
                }
            }
        }
    }

    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"}, name="media_resource_delete")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function deleteAction(MediaResource $mr) {
        die('delete');
    }

}
