<?php

namespace Innova\MediaResourceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Form\Type\MediaResourceType;

/**
 * Class MediaResourceController
 * 
 */
class MediaResourceController extends Controller {

    /**
     * Media resource Manager
     * @var \Innova\MediaResourceBundle\Manager\MediaResourceManager
     */
    protected $mediaResourceManager;

   
    
     /**
	* Display a media resource
	*
	* 
	* @Route(
	* "/{mediaResourceId}",
	* name="innova_media_resource_open",
	* )
	* @Method("GET")
	*/
     public function openAction() {
       
    }
    
    /**
	* Administrate a media resource
	*
	* 
	* @Route(
	* "/{mediaResourceId}",
	* name="innova_media_resource_administrate",
	* )
	* @Method("GET")
	*/
     public function administrateAction() {
       
    }
    

    /**
     * http://localhost/patrick/ENPA/web/app_dev.php/innova_media_resource/
     * @Route("/", name="media_resource_list")
     */
    public function listAction() {
        $manager = $this->get('innova_media_resource.manager.media_resource');
        $mediaResources = $manager->getAll();
        return $this->render('InnovaMediaResourceBundle:MediaResource:list.html.twig', array('resources' => $mediaResources));
    }

    /**
     * @Route("/add", name="media_resource_add")
     * @Method({"GET", "POST"})
     */
    public function addAction() {

        $mr = new MediaResource();
        $form = $this->createForm(new MediaResourceType(), $mr);
        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                // get the main file
                $mainUploadedFile = $this->getRequest()->files->get('file');
                $manager = $this->get('innova_media_resource.manager.media_resource');
                try {
                    // this method also persist and flush the exercise
                    $manager->handleMediaResourceMedia($mainUploadedFile, $mr);
                    // flashbag
                    $this->get('session')->getFlashBag()->set('success', "La ressource média a bien été créée.");
                } catch (Exception $e) {
                    $this->get('session')->getFlashBag()->set('error', $e->getMessage());
                }

                return $this->redirect($this->generateUrl('media_resource_list'));
            }
        }
        return $this->render('InnovaMediaResourceBundle:MediaResource:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/details/{id}/{edit}", requirements={"id" = "\d+"}, defaults={"edit" = false}, name="media_resource_details")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function detailsAction(MediaResource $mr) {

        $request = $this->container->get('request');
        $editParam = $request->get('edit');
        $edit = !$editParam ? false : true;

        // use of specific method to order regions correctly
        $regions = $this->get('innova_media_resource.manager.media_resource_region')->findByAndOrder($mr);
        if ($mr->getId()) {
            return $this->render('InnovaMediaResourceBundle:MediaResource:details.html.twig', array('resource' => $mr, 'edit' => $edit, 'regions' => $regions));
        } else {
            $this->get('session')->getFlashBag()->set('error', "Aucun media trouvé.");
            return $this->redirect($this->generateUrl('media_resource_list'));
        }
    }

    /**
     * @Route("/save/{id}", requirements={"id" = "\d+"}, name="media_resource_save")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     *  @Method({"POST"})
     */
    public function saveAction(MediaResource $mr) {
        if ($this->getRequest()->isMethod('POST')) {
            $request = $this->container->get('request');            
            $data = $request->request->all();            
            if(count($data) > 0){
                $title = $data['title'];
                $mr->setName($title);
                // $this->get('innova_media_resource.manager.media_resource')->updateMediaResourceName($mr, $title);
                
                $regionManager = $this->get('innova_media_resource.manager.media_resource_region');
                $mr = $regionManager->handleMediaResourceRegions($mr, $data);
                if($mr){
                    return new \Symfony\Component\HttpFoundation\Response('La ressource a bien été mise à jour.', 200);
                }
                else{
                    return new \Symfony\Component\HttpFoundation\Response('Une erreur s\'est produite lors de la mise à jour de la ressource.', 500);
                }
            }
        }
    }

    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="media_resource_delete")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function deleteAction(MediaResource $mr) {
        die('delete');
    }

    /**
     * AJAX
     * @Route("/region/add", name="region_add")
     */
    public function addRegionAction() {

        // must return a json object with region id
    }

    /**
     * AJAX
     * @Route("/region/add", name="region_delete")
     */
    public function deleteRegionAction() {
        
    }

    /**
     *  @Route("/region/config", name="region_config")
     */
    public function configRegionAction() {
        
    }

}
