<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;

use Innova\MediaResourceBundle\Entity\Media;
use Innova\MediaResourceBundle\Entity\Region;

/**
 * MediaResource Entity
 *
 * @ORM\Table(name="innova_media_resource")
 * @ORM\Entity
 */
class MediaResource extends AbstractResource
{
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
     */
    protected $name;
    
    
     /**
     *
     * @var medias
     * 
     * @ORM\OneToMany(targetEntity="Innova\MediaResourceBundle\Entity\Media", cascade={"remove", "persist"}, mappedBy="mediaResource")
     */
    protected $medias;
    
    
     /**
     * @ORM\OneToMany(targetEntity="Innova\MediaResourceBundle\Entity\Region", cascade={"remove", "persist"}, mappedBy="mediaResource")
     * 
     */
    protected $regions;
    
    public function __construct() {
        $this->medias = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Exercise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }  
    
    
    
    public function addMedia(Media $m){
        $this->medias[] = $m;
        return $this;
    }
    
    public function removeMedia(Media $m){
        $this->medias->removeElement($m);
        return $this;
    }
    
    public function getMedias(){
        return $this->medias;
    }
    
    
    public function addRegion(Region $region){
        $this->regions[] = $region;
        return $this;
    }
    
    public function removeRegion(Region $region){
        $this->regions->removeElement($region);
        return $this;
    }
    
    public function getRegions(){
        return $this->regions;
    }
}
