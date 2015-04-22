<?php

namespace Innova\MediaResourceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Entity\Playlist;
use Innova\MediaResourceBundle\Form\Type\PlaylistType;
use Innova\MediaResourceBundle\Entity\PlaylistRegion;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handle MediaRessource Playlist management
 * 
 * 
 *
 */
class PlaylistController extends Controller {

    /**
     * display playlists available for current MediaRessource
     * @Route("/list/{id}", requirements={"id" = "\d+"}, name="innova_playlists")
     * @Method("GET")
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function listAction(MediaResource $mr) {
        if (false === $this->container->get('security.context')->isGranted('OPEN', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        // find all available playlists for the current MediaResource
        $playlists = $mr->getPLaylists();
        return $this->render('InnovaMediaResourceBundle:Playlist:list.html.twig', array(
                    '_resource' => $mr,
                    'playlists' => $playlists
                        )
        );
    }

    /**
     * Show the form for new playlist creation
     * @Route("/{id}/add", requirements={"id" = "\d+"}, name="innova_playlist_add")
     * @Method({"GET", "POST"})
     * @ParamConverter("MediaResource", class="InnovaMediaResourceBundle:MediaResource")
     */
    public function addAction(MediaResource $mr, Request $request) {
        if (false === $this->container->get('security.context')->isGranted('OPEN', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        $playlist = new Playlist();
        $playlist->setMediaResource($mr);
        $form = $this->createForm(new PlaylistType($mr), $playlist, array(
            'action' => $this->generateUrl('innova_playlist_add', array('id' => $mr->getId())),
            'method' => 'POST'));

        $form->handleRequest($request);
        if ($form->isValid()) {            
            
            $em = $this->get('innova_media_resource.manager.playlist');
            // need the playlist with the id
            $playlist = $em->save($playlist);
            
            $data = $request->request->get('media_resource_playlist');
            $playlistRegions = $data['playlistRegions'];
            
            $plRegionEm = $this->get('innova_media_resource.manager.playlist_region');
            // create the relationship entities
            $plRegionEm->createPLaylistRegions($playlist, $playlistRegions);
            
            /*foreach($playlistRegions as $plRegion){
                $entity = new PlaylistRegion();
                $entity->setPlaylist($playlist);
                $entity->setOrdering(intval($plRegion['ordering']));
                $region = $this->getDoctrine()->getRepository('InnovaMediaResourceBundle:Region')->find($plRegion['region']);
                $entity->setRegion($region);
                $entity = $plRegionEm->save($entity);
            } */
            return $this->redirect($this->generateUrl('innova_playlists', array('id' => $mr->getId())));
        }

        return $this->render('InnovaMediaResourceBundle:Playlist:add.html.twig', array(
                    '_resource' => $mr,
                    'form' => $form->createView()
                        )
        );
    }

    /**
     * Show the form for new playlist creation
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="innova_playlist_edit")
     * @Method({"GET", "POST"})
     * @ParamConverter("Playlist", class="InnovaMediaResourceBundle:Playlist")
     */
    public function editAction(Playlist $playlist, Request $request) {
       
    }
    
    /**
     * Show the form for new playlist creation
     * @Route("/delete/{id}", requirements={"id" = "\d+"}, name="innova_playlist_delete")
     * @Method("GET")
     * @ParamConverter("Playlist", class="InnovaMediaResourceBundle:Playlist")
     */
    public function deleteAction(Playlist $playlist) {
        
        $mrId = $playlist->getMediaResource()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($playlist);
        $em->flush();
        
        return $this->redirect($this->generateUrl('innova_playlists', array('id' => $mrId)));
    }

    /*

      public function updateAction(){
      }

      public function deleteAction(MediaResource $mr){

      } */
}
