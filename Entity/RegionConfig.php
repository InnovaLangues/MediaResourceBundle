<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Innova\MediaResourceBundle\Entity\Region;

/**
 * RegionConfig
 *
 * @ORM\Table(name="innova_media_resource_region_config")
 * @ORM\Entity
 */
class RegionConfig {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var hasLoop
     *
     * @ORM\Column(name="has_loop", type="boolean")
     */
    private $hasLoop;

    /**
     * @var hasBackward
     *
     * @ORM\Column(name="has_backward", type="boolean")
     */
    private $hasBackward;

    /**
     * @var hasRate
     *
     * @ORM\Column(name="has_rate", type="boolean")
     */
    private $hasRate;

    /**
     * @var helpText
     *
     * @ORM\Column(name="help_text", type="string", length=255)
     */
    private $helpText;

    /**
     * @var region
     * @ORM\OneToOne(targetEntity="Innova\MediaResourceBundle\Entity\Region", inversedBy="regionConfig")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @var related region for help
     * User can be helped by another region content
     * @ORM\Column(name="help_region_uuid", type="string", length=255)
     */
    private $helpRegionUuid;
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function setHasLoop($hasLoop) {
        $this->hasLoop = $hasLoop;
        return $this;
    }

    public function getHasLoop() {
        return $this->hasLoop;
    }

    public function setHasBackward($hasBackward) {
        $this->hasBackward = $hasBackward;
        return $this;
    }

    public function getHasBackward() {
        return $this->hasBackward;
    }

    public function setHasRate($hasRate) {
        $this->hasRate = $hasRate;
        return $this;
    }

    public function getHasRate() {
        return $this->hasRate;
    }

    public function setHelpText($helpText) {
        $this->helpText = $helpText;
        return $this;
    }

    public function getHelpText() {
        return $this->helpText;
    }

    public function setRegion(Region $region) {
        $this->region = $region;
        return $this;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setHelpRegion(Region $helpRegion) {
        $this->helpRegion = $helpRegion;
        return $this;
    }

    public function getHelpRegion() {
        return $this->helpRegion;
    }
    
    public function getHelpRegionUuid() {
        return $this->helpRegionUuid;
    }

    public function setHelpRegionUuid($helpRegionUuid) {
        $this->helpRegionUuid = $helpRegionUuid;
        return $this;
    }

}
