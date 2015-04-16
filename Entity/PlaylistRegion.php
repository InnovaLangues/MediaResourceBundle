<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Innova\MediaResourceBundle\Entity\Region;
use Innova\MediaResourceBundle\Entity\Playlist;

/**
 * 
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
     * 
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\Region", inversedBy="playlistRegions")
     * @ORM\JoinColumn(name="region_id")
     */
    protected $region;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\Playlist", inversedBy="playlistRegions")
     * @ORM\JoinColumn(name="playlist_id")
     */
    protected $playlist;    

    /**
     *
     * @var type 
     * @ORM\Column(type="integer")
     */
    protected $order;

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

    public function setOrder(type $order) {
        $this->order = $order;
        return $this;
    }

    public function getOrder() {
        return $this->order;
    }

}
