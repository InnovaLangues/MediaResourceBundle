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
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Handle MediaRessource Playlist management
 * 
 * 
 */
class PlaylistController extends Controller {

    /**
     * display playlists available for current MediaRessource
     * @Route("/{id}", requirements={"id" = "\d+"}, name="innova_playlists")
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
        if (false === $this->container->get('security.context')->isGranted('EDIT', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        $audioPath = $this->get('innova_media_resource.manager.media_resource_media')->getAudioMediaUrl($mr);
        $playlist = new Playlist();
        $form = $this->createForm(new PlaylistType($mr), $playlist, array(
            'action' => $this->generateUrl('innova_playlist_add', array('id' => $mr->getId())),
            'method' => 'POST'));

        $form->handleRequest($request);
        if ($form->isValid()) {

            //$em = $this->get('innova_media_resource.manager.playlist');
            $em = $this->getDoctrine()->getManager();
            $playlist->setMediaResource($mr);
            $playlistRegions = $playlist->getPlaylistRegions();
            foreach ($playlistRegions as $plRegion) {
                $plRegion->setPlaylist($playlist);
            }
            $em->persist($playlist);
            $em->flush();

            $msg = $this->get('translator')->trans('playlist_save_success', array(), 'media_resource');
            $this->get('session')->getFlashBag()->set('success', $msg);

            return $this->redirect($this->generateUrl('innova_playlists', array('id' => $mr->getId())));
        }

        return $this->render('InnovaMediaResourceBundle:Playlist:add.html.twig', array(
                    '_resource' => $mr,
                    'audioUrl' => $audioPath,
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
        $mr = $playlist->getMediaResource();
        if (false === $this->container->get('security.context')->isGranted('EDIT', $mr->getResourceNode())) {
            throw new AccessDeniedException();
        }
        $audioPath = $this->get('innova_media_resource.manager.media_resource_media')->getAudioMediaUrl($mr);
        // temporary assign old PlaylistRegion to an ArrayCollection (for deleting)
        $originalPLayListRegions = new ArrayCollection();
        foreach ($playlist->getPlaylistRegions() as $plr) {
            $originalPLayListRegions->add($plr);
        }

        $form = $this->createForm(new PlaylistType($mr), $playlist, array(
            'action' => $this->generateUrl('innova_playlist_edit', array('id' => $playlist->getId())),
            'method' => 'POST'));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // delete no more existing PlaylistRegions
            foreach ($originalPLayListRegions as $plr) {
                if ($playlist->getPlaylistRegions()->contains($plr) == false) {
                    $playlist->removePlaylistRegion($plr);
                    $em->persist($playlist);
                }
            }
            // updated PlaylistRegions
            $playlistRegions = $playlist->getPlaylistRegions();
            foreach ($playlistRegions as $plRegion) {
                $plRegion->setPlaylist($playlist);
            }
            $em->persist($playlist);
            $em->flush();

            $msg = $this->get('translator')->trans('playlist_save_success', array(), 'media_resource');
            $this->get('session')->getFlashBag()->set('success', $msg);

            return $this->redirect($this->generateUrl('innova_playlists', array('id' => $mr->getId())));
        }

        return $this->render('InnovaMediaResourceBundle:Playlist:edit.html.twig', array(
                    '_resource' => $mr,
                    'audioUrl' => $audioPath,
                    'form' => $form->createView()
                    )
        );
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

        $msg = $this->get('translator')->trans('playlist_deletion_success', array(), 'media_resource');
        $this->get('session')->getFlashBag()->set('success', $msg);

        return $this->redirect($this->generateUrl('innova_playlists', array('id' => $mrId)));
    }

}
