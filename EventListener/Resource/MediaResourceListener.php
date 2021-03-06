<?php

namespace Innova\MediaResourceBundle\EventListener\Resource;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\CopyResourceEvent;
use Claroline\CoreBundle\Event\CustomActionResourceEvent;
use Innova\MediaResourceBundle\Entity\MediaResource;

/**
 * Media Resource Event Listener
 * Used to integrate Path to Claroline resource manager
 */
class MediaResourceListener extends ContainerAware {

    /**
     * Fired when a new ResourceNode of type Path is opened
     * @param  \Claroline\CoreBundle\Event\CustomActionResourceEvent $event
     * @throws \Exception
     */
    public function onAdministrate(CustomActionResourceEvent $event) {
        $mediaResource = $event->getResource();
        $route = $this->container
                ->get('router')
                ->generate('innova_media_resource_administrate', array(
            'id' => $mediaResource->getId(),
            'workspaceId' => $mediaResource->getWorkspace()->getId()
                )
        );
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

    /**
     * Fired when a ResourceNode of type MediaResource is opened
     * @param \Claroline\CoreBundle\Event\OpenResourceEvent $event
     * @throws \Exception
     */
    public function onOpen(OpenResourceEvent $event) {
        $mediaResource = $event->getResource();
        $route = $this->container
                ->get('router')
                ->generate('innova_media_resource_open', array(
            'id' => $mediaResource->getId(),
            'workspaceId' => $mediaResource->getWorkspace()->getId()
                )
        );
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

    /**
     * 
     * @param CreateResourceEvent $event
     * @throws \Exception
     */
    public function onCreate(CreateResourceEvent $event) {
        // Create form
        $form = $this->container->get('form.factory')->create('media_resource', new MediaResource());
        // Try to process form
        $request = $this->container->get('request');
        $form->submit($request);
        if ($form->isValid()) {
            $mediaResource = $form->getData();           
            $file = $form['file']->getData();
            
            $this->container->get('innova_media_resource.manager.media_resource')->handleMediaResourceMedia($file, $mediaResource);
            // Send new MediaResource to dispatcher through event object
            $event->setResources(array($mediaResource));
        } else {
            $content = $this->container->get('templating')->render(
                    'ClarolineCoreBundle:Resource:createForm.html.twig', array(
                'form' => $form->createView(),
                'resourceType' => 'innova_media_resource'
            ));
            $event->setErrorFormContent($content);
        }
        $event->stopPropagation();
        return;
    }

    /**
     * 
     * @param CreateFormResourceEvent $event
     */
    public function onCreateForm(CreateFormResourceEvent $event) {
        // Create form
        $form = $this->container->get('form.factory')->create('media_resource', new MediaResource());
        $content = $this->container->get('templating')->render(
                'ClarolineCoreBundle:Resource:createForm.html.twig', array(
            'form' => $form->createView(),
            'resourceType' => 'innova_media_resource'
        ));
        $event->setResponseContent($content);
        $event->stopPropagation();
    }

    public function onDelete(DeleteResourceEvent $event) {

        $mediaResource = $event->getResource();
        $manager = $this->container->get('innova_media_resource.manager.media_resource');
        $manager->delete($mediaResource);

        $event->stopPropagation();
    }

    /**
     * Fired when a ResourceNode of type MediaResource is duplicated
     * @param \Claroline\CoreBundle\Event\CopyResourceEvent $event
     * @throws \Exception
     */
    public function onCopy(CopyResourceEvent $event) {
        
        $toCopy = $event->getResource();        
        $new = new MediaResource();
        $new->setName($toCopy->getName());

        // duplicate media resource media(s) (=file(s))
        $medias = $toCopy->getMedias();
        foreach ($medias as $media) {
            $this->container->get('innova_media_resource.manager.media_resource')->copyMedia($new, $media);
        }

        // duplicate regions and region config
        $regions = $toCopy->getRegions();
        foreach ($regions as $region) {
            $this->container->get('innova_media_resource.manager.media_resource_region')->copyRegion($new, $region);
        }
        $event->setCopy($new);
        $event->stopPropagation();
    }
}
