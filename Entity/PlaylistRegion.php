<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Innova\MediaResourceBundle\Entity\Region;
use Innova\MediaResourceBundle\Entity\Playlist;

/**
 * Relationship entity with ordering extra field
 * @ORM\Table(name="innova_media_resource_playlist_region")
 * @ORM\Entity
 */
class PlaylistRegion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Related region
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\Region", inversedBy="playlistRegions")
     * @ORM\JoinColumn(name="region_id")
     */
    protected $region;

    /**
     * Related playlist
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\Playlist", cascade={"persist"}, inversedBy="playlistRegions")
     * @ORM\JoinColumn(name="playlist_id", nullable=false, onDelete="CASCADE")
     */
    protected $playlist;    

    /**
     * Order for the element in the playlist
     * Can not call this field order since this is a key word for sql...
     * @var ordering 
     * @ORM\Column(type="integer")
     */
    protected $ordering;

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setRegion(Region $region) {
        $this->region = $region;
        return $this;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setPlaylist(Playlist $playlist) {
        $this->playlist = $playlist;
        return $this;
    }

    public function getPlaylist() {
        return $this->playlist;
    }

    public function setOrdering($ordering) {
        $this->ordering = $ordering;
        return $this;
    }

    public function getOrdering() {
        return $this->ordering;
    }

}
