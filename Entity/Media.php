<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Innova\MediaResourceBundle\Entity\MediaResource;

/**
 * Media
 *
 * @ORM\Table(name="innova_media_resource_media")
 * @ORM\Entity
 */
class Media {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     * audio or video
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var MediaResource
     * media resource owner
     * 
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\MediaResource", inversedBy="medias")
     * @ORM\JoinColumn(name="media_resource_id", nullable=false)
     */
    private $mediaResource; 
     
    
    public function setMediaResource(MediaResource $mr){
        $this->mediaResource = $mr;
        return $this;
    }
    
    public function getMediaResource(){
        return $this->mediaResource;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Media
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }   

}
