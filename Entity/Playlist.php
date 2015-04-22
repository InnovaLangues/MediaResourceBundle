<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Innova\MediaResourceBundle\Entity\Context;
use Innova\MediaResourceBundle\Entity\PlaylistRegion;
use Innova\MediaResourceBundle\Entity\MediaResource;

/**
 * A playlist is an ordered list of MediaResource Regions
 * .... Or an ordered list of PLaylistRegion (fake entity for ordered relationship)
 * @ORM\Table(name="innova_media_resource_playlist")
 * @ORM\Entity
 */
class Playlist {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Innova\MediaResourceBundle\Entity\PlaylistRegion", cascade={"remove"}, mappedBy="playlist")
     */
    protected $playlistRegions;

    /**
     * Context for the playlist (if used in a InnovaPath)
     * @var context 
     * @ORM\OneToOne(targetEntity="Innova\MediaResourceBundle\Entity\Context", mappedBy="playlist")
     */
    protected $context;
    
    /**
     * @var MediaResource 
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\MediaResource", inversedBy="playlists")
     * @ORM\JoinColumn(name="media_resource_id", nullable=true)
     */
    protected $mediaResource;

    public function __construct() {
        $this->playlistRegions = new ArrayCollection();
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setContext(Context $context) {
        $this->context = $context;
        return $this;
    }

    public function getContext() {
        return $this->context;
    }
    
     public function setMediaResource(MediaResource $mediaResource) {
        $this->mediaResource = $mediaResource;
        return $this;
    }

    public function getMediaResource() {
        return $this->mediaResource;
    }
    
    public function addPlaylistRegion(PlaylistRegion $playlistRegion){
        $this->playlistRegions[] = $playlistRegion;
        return $this;
    }
    
    public function removePlaylistRegion(PlaylistRegion $playlistRegion){
         $this->playlistRegions->removeElement($playlistRegion);
        return $this;
    }

    public function getPlaylistRegions(){
        return $this->playlistRegions;
    }
}
