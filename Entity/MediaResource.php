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
class MediaResource extends AbstractResource {

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

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var boolean
     *
     * @ORM\Column(name="modified", type="boolean")
     */
    protected $modified;

    /**
     * 
     * 
     */
    public $file;

    public function __construct() {
        $this->medias = new ArrayCollection();
        $this->regions = new ArrayCollection();

        $this->published = false;
        $this->modified = false;
    }

    public function getFile() {
        return $this->file;
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
     * Set name
     *
     * @param string $name
     * @return Exercise
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set published
     * @param boolean $published
     * @return \Innova\MediaResourceBundle\Entity\MediaResource
     */
    public function setPublished($published) {
        $this->published = $published;
        return $this;
    }

    /**
     * Is media resource already published
     * @return boolean
     */
    public function isPublished() {
        return $this->published;
    }

    /**
     * Set modified
     * @param boolean $modified
     * @return \Innova\MediaResourceBundle\Entity\MediaResource
     */
    public function setModified($modified) {
        $this->modified = $modified;
        return $this;
    }

    /**
     * Is media resource modified since the last deployment
     * @return boolean
     */
    public function isModified() {
        return $this->modified;
    }

    public function addMedia(Media $m) {
        $this->medias[] = $m;
        return $this;
    }

    public function removeMedia(Media $m) {
        $this->medias->removeElement($m);
        return $this;
    }

    public function getMedias() {
        return $this->medias;
    }

    public function addRegion(Region $region) {
        $this->regions[] = $region;
        return $this;
    }

    public function removeRegion(Region $region) {
        $this->regions->removeElement($region);
        return $this;
    }

    public function getRegions() {
        return $this->regions;
    }

    /**
     * Wrapper to access workspace of the Path
     * @return \Claroline\CoreBundle\Entity\Workspace\Workspace
     */
    public function getWorkspace() {
        $workspace = null;
        if (!empty($this->resourceNode)) {
            $workspace = $this->resourceNode->getWorkspace();
        }
        return $workspace;
    }

}
