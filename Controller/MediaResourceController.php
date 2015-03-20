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
            return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array('_resource' => $mr, 'edit' => false, 'regions' => $regions, 'workspace' => $workspace));
        } 
        else{
            $msg = $this->get('translator')->trans('no_media_resource', array(), 'media_resource');
            $this->get('session')->getFlashBag()->set('error', $msg);
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
            return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array('_resource' => $mr, 'edit' => true, 'regions' => $regions, 'workspace' => $workspace));
        } else {
            $msg = $this->get('translator')->trans('no_media_resource', array(), 'media_resource');
            $this->get('session')->getFlashBag()->set('error', $msg);
            // return $this->redirect($this->generateUrl('media_resource_list'));
            // TODO redirect to claro resource manager
        }
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
                    $msg = $this->get('translator')->trans('resource_update_success', array(), 'media_resource');
                    return new \Symfony\Component\HttpFoundation\Response($msg, 200);
                } else {
                    $msg = $this->get('translator')->trans('resource_update_error', array(), 'media_resource');
                    return new \Symfony\Component\HttpFoundation\Response($msg, 500);
                }
            }
        }
    }   

}
