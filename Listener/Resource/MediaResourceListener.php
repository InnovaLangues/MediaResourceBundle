<?php

namespace Innova\MediaResourceBundle\Listener\Resource;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\CopyResourceEvent;
use Claroline\CoreBundle\Event\CustomActionResourceEvent;



use Innova\MediaResourceBundle\Entity\MediaResource;
use Claroline\CoreBundle\Entity\Resource\ResourceNode;

/**
 * Media Resource Event Listener
 * Used to integrate Path to Claroline resource manager
 */
class MediaResourceListener extends ContainerAware
{
    /**
     * Fired when a new ResourceNode of type Path is opened
     * @param  \Claroline\CoreBundle\Event\OpenResourceEvent $event
     * @throws \Exception
     */
    public function onAdministrate(CustomActionResourceEvent $event)
    {
        
		$mediaResource = $event->getResource();
		$route = $this->container
		->get('router')
		->generate(
			'innova_media_resource_administrate',
				array (
				'mediaResourceId' => $mediaResource->getId(),
				)
		);
		$event->setResponse(new RedirectResponse($route));
		$event->stopPropagation();
    }
    
    public function onOpen(OpenResourceEvent $event)
	{
		$mediaResource = $event->getResource();
		$route = $this->container
		->get('router')
		->generate(
			'innova_media_resource_open',
				array (
				'mediaResourceId' => $mediaResource->getId(),
				)
		);
		$event->setResponse(new RedirectResponse($route));
		$event->stopPropagation();
	}

   
}
