<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Innova\MediaResourceBundle\Entity\MediaResource;

/**
 * Media Manager
 */
class MediaManager {

    protected $em;
    protected $uploadFileDir;

    public function __construct(EntityManager $em, $uploadfileDir) {
        $this->em = $em;
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

    protected function getUploadDirectory() {
        return $this->uploadFileDir;
    }
}
