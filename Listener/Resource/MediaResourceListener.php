<?php

namespace Innova\MediaResourceBundle\Listener\Resource;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\CopyResourceEvent;


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
    public function onMediaResourceOpen(OpenResourceEvent $event)
    {
        /*$path = $event->getResource();
        if ($path->isPublished()) {
            $route = $this->container->get('router')->generate(
                'innova_path_player_index',
                array (
                    'workspaceId' => $path->getWorkspace()->getId(),
                    'pathId' => $path->getId(),
                    'stepId' => $path->getRootStep()->getId()
                )
            );
        }
        else {
            $route = $this->container->get('router')->generate(
                'claro_workspace_open_tool',
                array(
                    'workspaceId' => $path->getWorkspace()->getId(),
                    'toolName' => 'innova_path'
                )
            );

            $this->container->get('session')->getFlashBag()->add(
                'warning',
                $this->container->get('translator')->trans("path_open_not_published_error", array(), "innova_tools")
            );
        }*/
        die('MediaResourceListener');
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

   
}
