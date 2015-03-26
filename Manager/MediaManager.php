<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Innova\MediaResourceBundle\Entity\MediaResource;

/**
 * Media Manager
 */
class MediaManager {

    protected $fileDirName;
    protected $em;

    public function __construct(EntityManager $em, $fileDirName) {
        $this->em = $em;
        $this->fileDirName = $fileDirName;
    }

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:Media');
    }

    public function getAudioMediaUrl(MediaResource $mr) {
        $audio = $this->getRepository()->findOneBy(array('mediaResource' => $mr, 'type' => 'audio'));
        if($audio){
            return  $this->fileDirName . '/' . $audio->getUrl();
        }
        return null;
    }

    public function getVideoMedia(MediaResource $mr) {
        $video = $this->getRepository()->findOneBy(array('mediaResource' => $mr, 'type' => 'video'));
        if($video){
            return  $this->fileDirName . '/' . $video->getUrl();
        }
        return null;
    }

}
