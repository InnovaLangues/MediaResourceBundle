<?php


namespace Innova\MediaResourceBundle\Form\Handler;
use Innova\MediaResourceBundle\Manager\MediaResourceManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @DI\Service("innova.form.handler.media_resource_handler")
 */
class MediaResourceHandler

{
	/**
     * Current data of the form
     * @var \Innova\MediaResourceBundle\Entity\MediaResource
     */
    protected $data;

    /**
     * Form to handle
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * Current request
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Activity manager
     * @var \Innova\MediaResourceBundle\Manager\MediaResourceManager
     */
    protected $mediaResourceManager;

    /**
     * Class constructor
     * @param \Innova\MediaResourceBundle\Manager\MediaResourceManager $activityManager
     */
    public function __construct(ActivityManager $mediaResourceManager)
    {
        $this->mediaResourceManager = $mediaResourceManager;
    }

    /**
     * Set current request
     * @param  \Symfony\Component\HttpFoundation\Request           $request
     * @return \Innova\MediaResourceBundle\Form\Handler\MediaResourceHandler
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get current data of the form
     * @return \Innova\MediaResourceBundle\Entity\MediaResource
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set current form
     * @param  \Symfony\Component\Form\FormInterface               $form
     * @return\Innova\MediaResourceBundle\Form\Handler\MediaResourceHandler
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Process current form
     * @return boolean
     */
    public function process()
    {
        $success = false;

        if ($this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT') {
            // Correct HTTP method => try to process form
            $this->form->submit($this->request);
            
            if ($this->form->isValid()) {
                // Form is valid => create or update the activity
                $this->data = $this->form->getData();
                
                if ($this->request->getMethod() == 'POST') {
                    // Create activity
                    $success = $this->create();
                } else {
                    // Edit existing activity
                    $success = $this->edit();
                }
            }
        }

        return $success;
    }

    public function create()
    {
        $this->mediaResourceManager->create($this->data);

        return true;
    }

    public function edit()
    {
        $this->mediaResourceManager->edit($this->data);

        return true;
    }
}
