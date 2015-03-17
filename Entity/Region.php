<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Innova\MediaResourceBundle\Entity\MediaResource;

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
     * @var media resource
     * @ORM\OneToOne(targetEntity="Innova\MediaResourceBundle\Entity\RegionConfig", mappedBy="region", cascade={"persist", "remove"}) 
     */
    private $regionConfig;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set start of the region
     *
     * @param float $start
     * @return Region
     */
    public function setStart($start) {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return float 
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param float $end
     * @return Region
     */
    public function setEnd($end) {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return float 
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Region
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
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

}
