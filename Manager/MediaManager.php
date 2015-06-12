<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Entity\Media;

/**
 * Media Manager
 */
class MediaManager {

    protected $em;
    protected $readFileDir;
    protected $uploadFileDir;

    public function __construct(EntityManager $em, $readFileDir, $uploadfileDir) {
        $this->em = $em;
        $this->readFileDir = $readFileDir;
        $this->uploadFileDir = $uploadfileDir;
    }

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:Media');
    }
    
    public function getAudioMediaUrlForAjax(MediaResource $mr) {
        $audio = $this->getRepository()->findOneBy(array('mediaResource' => $mr, 'type' => 'audio'));
        if ($audio) {
            return $this->getUploadDirectory() . '/' . $audio->getUrl();
        }
        return null;
    }

    protected function getReadFileDirectory() {
        return $this->readFileDir;
    }

    protected function getUploadDirectory() {
        return $this->uploadFileDir;
    }
}
