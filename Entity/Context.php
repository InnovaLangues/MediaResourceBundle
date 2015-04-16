<?php

namespace Innova\MediaResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Context for a Media Resource
 * @ORM\Table(name="innova_media_resource_context")
 * @ORM\Entity
 */
class Context {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var MediaResource
     * @ORM\ManyToOne(targetEntity="Innova\MediaResourceBundle\Entity\MediaResource", inversedBy="contexts")
     * @ORM\JoinColumn(name="media_resource_id", nullable=false)
     */
    protected $mediaResource;

    /**
     * @var Boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $hasActiveListening;

    /**
     * @var Boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $hasAutoPause;

    /**
     * @var Boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $hasLiveListening;

    /**
     * @ORM\OneToOne(targetEntity="Innova\MediaResourceBundle\Entity\Playlist", inversedBy="context")
     * @ORM\JoinColumn(name="playlist_id", nullable=true)
     */
    protected $playlist;

    

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setMediaResource($mediaResource) {
        $this->mediaResource = $mediaResource;        
        return $this;
    }
    
    public function getMediaResource() {
        return $this->mediaResource;
    } 

    public function setHasActiveListening($hasActiveListening) {
        $this->hasActiveListening = $hasActiveListening;        
        return $this;
    }
    
    public function getHasActiveListening() {
        return $this->hasActiveListening;
    }

    public function setHasAutoPause($hasAutoPause) {
        $this->hasAutoPause = $hasAutoPause;
        return $this;
    }
    
    public function getHasAutoPause() {
        return $this->hasAutoPause;
    }

    public function setHasLiveListening($hasLiveListening) {
        $this->hasLiveListening = $hasLiveListening;
        return $this;
    }
    
    public function getHasLiveListening() {
        return $this->hasLiveListening;
    }

    public function setPlaylist($playlist) {
        $this->playlist = $playlist;
        return $this;
    }
    
    public function getPlaylist() {
        return $this->playlist;
    }
}
