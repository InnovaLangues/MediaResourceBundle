<?php

namespace Innova\MediaResourceBundle\Listener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Claroline\CoreBundle\Event\DisplayToolEvent;

class MediaResourceListener
{
    /**
     * Current request
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $request;

    /**
     * Symfony Kernel
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    private $httpKernel;

    /**
     * Class constructor
     */
    public function __construct(
        RequestStack $requestStack,
        HttpKernelInterface $httpKernel)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->httpKernel = $httpKernel;
    }

    /**
     * List paths of the Workspace on Tool open
     * @param \Claroline\CoreBundle\Event\DisplayToolEvent $event
     */
    public function onWorkspaceOpen(DisplayToolEvent $event)
    {
        $subRequest = $this->request->duplicate(array(), null, array (
            //innova_media_resource.controller.home
            '_controller' => 'innova_media_resource.controller.home:indexAction',
            'workspaceId' => $event->getWorkspace()->getId(),
        ));

        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        $event->setContent($response->getContent());
        $event->stopPropagation();
    }
}
