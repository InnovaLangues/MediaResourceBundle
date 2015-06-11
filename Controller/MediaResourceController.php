<?php

namespace Innova\MediaResourceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Claroline\CoreBundle\Entity\Workspace\Workspace;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        // default view is AutoPause !
        return $this->render('InnovaMediaResourceBundle:MediaResource:details.pause.html.twig', array(
                    '_resource' => $mr,
                    'regions' => $regions,
                    'workspace' => $workspace,
                    'audioPath' => $audioPath
                        )
        );
    }

    /**
     * Media resource player other views
     * @Route("/mode/{id}", requirements={"id" = "\d+"}, name="media_resource_change_view") 
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     * @Method("POST")
     */
    public function changeViewAction(Workspace $workspace, MediaResource $mr) {
        if (false === $this->container->get('security.context')->isGranted('OPEN', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        if ($this->getRequest()->isMethod('POST')) {

            $audioPath = $this->get('innova_media_resource.manager.media_resource_media')->getAudioMediaUrl($mr);
            // use of specific method to order regions correctly
            $regions = $this->get('innova_media_resource.manager.media_resource_region')->findByAndOrder($mr);

            $active = $this->getRequest()->get('active');
            $live = $this->getRequest()->get('live');

            if ($active) {

                return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array(
                            '_resource' => $mr,
                            'edit' => false,
                            'regions' => $regions,
                            'workspace' => $workspace,
                            'audioPath' => $audioPath,
                            'playMode' => 'active'
                                )
                );
            } else if ($live) {
                return $this->render('InnovaMediaResourceBundle:MediaResource:details.live.html.twig', array(
                            '_resource' => $mr,
                            'regions' => $regions,
                            'workspace' => $workspace,
                            'audioPath' => $audioPath
                                )
                );
            } else {

                $url = $this->generateUrl('innova_media_resource_open', array('id' => $mr->getId(), 'workspaceId' => $workspace->getId()));
                return $this->redirect($url);
            }
        }
    }

    /**
     * administrate a media resource
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
                    'audioPath' => $audioPath,
                    'playMode' => 'active'
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

    /**
     * Serve a ressource file that is not in the web folder
     * @Route(
     *     "/get/media/{id}",
     *     name="innova_get_mediaresource_resource_file"
     * )
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     * @Method({"GET"})
     */
    public function serveMediaResourceFile(MediaResource $mr) {

        $filePath = $this->get('innova_media_resource.manager.media_resource_media')->getAudioMediaUrlForAjax($mr);

        /*
          $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($filePath);
          $response->trustXSendfileTypeHeader();
          $response->setContentDisposition(
          ResponseHeaderBag::DISPOSITION_INLINE, basename($filePath), iconv('UTF-8', 'ASCII//TRANSLIT', basename($filePath))
          );
          return $response;
         */


        /*
          $stream = fopen($filePath, 'r');
          $response = new \Symfony\Component\HttpFoundation\Response(stream_get_contents($stream), 200, array(
          'Content-Type' => 'application/octet-stream',
          'Content-Length' => sizeof($filePath),
          'Content-Disposition', 'attachment; filename="' . basename($filePath) . '"'
          ));

          return $response;
         */
        /*
          $response = new \Symfony\Component\HttpFoundation\Response();
          $d = $response->headers->makeDisposition(
          ResponseHeaderBag::DISPOSITION_INLINE, basename($filePath)
          );
          $response->headers->set('Content-Disposition', $d);
          $finfo = new \finfo(FILEINFO_MIME_TYPE);
          $type = $finfo->file($filePath);
          $response->headers->set('Content-type', $type);
          $response->sendHeaders();
          $response->setContent(file_get_contents($filePath));
          return $response;
         */
        /*
          $fp = fopen($filePath, 'r');
          $content = fread($fp, filesize($filePath));
          $content = addslashes($content);
          fclose($fp);

          $response = new \Symfony\Component\HttpFoundation\Response();
          $response->headers->set('Content-Type', 'application/octet-stream');
          $response->setContent($content);
          return $response;
         */


        $response = new \Symfony\Component\HttpFoundation\Response();
        $file = file_get_contents($filePath);
        $data = base64_encode($file);
        $response->setContent($data);
        return $response;



        /* $response = new \Symfony\Component\HttpFoundation\JsonResponse();
          $response->headers->set('Content-Type', 'application/json');
          $response->setData(json_encode(utf8_encode(file_get_contents($filePath)), JSON_UNESCAPED_UNICODE));
          //$response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

          return $response; */


        /*
          $fp = fopen($filePath, 'r');
          $content = fread($fp, filesize($filePath));
          $response = new \Symfony\Component\HttpFoundation\JsonResponse(json_encode(utf8_encode($content), JSON_UNESCAPED_UNICODE));
          //$response->headers->set('Content-Type', 'application/json');
          //$response->setData(json_encode($content,JSON_UNESCAPED_UNICODE));
          //$response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

          return $response;
         */




        /* $file = file($filePath);
          //$data = base64_encode($file);
          $response = new \Symfony\Component\HttpFoundation\JsonResponse();
          $response->setData(json_encode($file));
          return $response; */



        /*
          $fp = fopen($filePath, 'r');
          $content = fread($fp, filesize($filePath));
          //$content = addslashes($content);
          fclose($fp);

          $response = new \Symfony\Component\HttpFoundation\Response();
          $response->headers->set('Content-Type', 'application/json');
          $response->setContent($content);
          //$response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

          return $response;
         */





        /*
          $response = new Response();
          $d = $response->headers->makeDisposition(
          ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filePath
          );
          $response->headers->set('Content-Disposition', $d);
          $finfo = new finfo(FILEINFO_MIME_TYPE);
          $type = $finfo->file($filePath);
          //$response->headers->set('Content-type', $type);
          $response->sendHeaders();
          //$response->setContent(fopen($filePath, 'r'));

          $file = fopen($filePath, 'r');
          //$response->setContent(fread($file,filesize($filePath)));
          $response->setContent($file);
          fclose($file);
          return $response;

         */
    }

}
