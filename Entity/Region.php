<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Entity\PlaylistRegion;

/**
 * Region
 *
 * @ORM\Table(name="innova_media_resource_region")
 * @ORM\Entity
 */
class Region {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="start", type="float")
     */
    private $start;

    /**
     * @var float
     *
     * @ORM\Column(name="end", type="float")
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     *
     * @var MediaResource
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\MediaResource", inversedBy="regions")
     * @ORM\JoinColumn(name="media_resource_id", nullable=false)
     */
    private $mediaResource;

    /**
     * @var region configuration
     * @ORM\OneToOne(targetEntity="Innova\MediaResourceBundle\Entity\RegionConfig", mappedBy="region", cascade={"persist", "remove"}) 
     */
    private $regionConfig;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=255)
     */
    private $uuid;
    
     /**
     * @ORM\OneToMany(targetEntity="Innova\MediaResourceBundle\Entity\PlaylistRegion", mappedBy="region")
     */
    protected $playlistRegions;

   public function __construct() {
        $this->playlistRegions = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setStart($start) {
        $this->start = $start;
        return $this;
    }
    
    public function getStart() {
        return $this->start;
    }
    
    public function setEnd($end) {
        $this->end = $end;
        return $this;
    }
   
    public function getEnd() {
        return $this->end;
    }
    
    public function setNote($note) {
        $this->note = $note;
        return $this;
    }
    
    public function getNote() {
        return $this->note;
    }

    public function setMediaResource(MediaResource $ms) {
        $this->mediaResource = $ms;
        return $this;
    }

    public function getMediaResource() {
        return $this->mediaResource;
    }

    public function setRegionConfig(RegionConfig $rc) {
        $this->regionConfig = $rc;
        return $this;
    }

    public function getRegionConfig() {
        return $this->regionConfig;
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
        return $this;
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
    
    public function __toString() {
        return $this->timeToHms($this->getStart()) . ' - ' . $this->timeToHms($this->getEnd());
    }
    
    private function timeToHms($time){
        $stringSec = strval($time);
        $fullMilli = explode(".", $stringSec);
        $milli = array_key_exists(1, $fullMilli) ?  substr($fullMilli[1], 0, 2): '00';
        $ms = \gmdate('i:s', $time);
       
        return $ms.':'.$milli;
    }

}
