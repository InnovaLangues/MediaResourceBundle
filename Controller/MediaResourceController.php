<?php

namespace Innova\MediaResourceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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
        if (false === $this->container->get('security.context')->isGranted('OPEN', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        $audioPath = $this->get('innova_media_resource.manager.media_resource_media')->getAudioMediaUrl($mr);
        // use of specific method to order regions correctly
        $regions = $this->get('innova_media_resource.manager.media_resource_region')->findByAndOrder($mr);
        return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array(
                                            '_resource' => $mr, 
                                            'edit' => false, 
                                            'regions' => $regions, 
                                            'workspace' => $workspace,
                                            'audioPath' => $audioPath
                                        )
                            );
    }

    /**
     * display a media resource as admin
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="innova_media_resource_administrate")
     * @Method("GET")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function administrateAction(Workspace $workspace, MediaResource $mr) {

        if (false === $this->container->get('security.context')->isGranted('ADMINISTRATE', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        $audioPath = $this->get('innova_media_resource.manager.media_resource_media')->getAudioMediaUrl($mr);
        // use of specific method to order regions correctly
        $regions = $this->get('innova_media_resource.manager.media_resource_region')->findByAndOrder($mr);
        return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array(
                                            '_resource' => $mr, 
                                            'edit' => true, 
                                            'regions' => $regions, 
                                            'workspace' => $workspace,
                                            'audioPath' => $audioPath
                                        )
                            );
    }

    /**
     * AJAX
     * save after editing a media resource (adding and/or configuring regions) 
     * @Route("/save/{id}", requirements={"id" = "\d+"}, name="media_resource_save")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     *  @Method({"POST"})
     */
    public function saveAction(MediaResource $mr) {
        if ($this->getRequest()->isMethod('POST')) {
            $request = $this->container->get('request');
            $data = $request->request->all();
            if (count($data) > 0) {
                $regionManager = $this->get('innova_media_resource.manager.media_resource_region');
                $mediaResource = $regionManager->handleMediaResourceRegions($mr, $data);
                if ($mediaResource) {
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
