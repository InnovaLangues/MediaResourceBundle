<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Innova\MediaResourceBundle\Entity\MediaResource;

/**
 * Media Manager
 */
class MediaManager {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:Media');
    }

    public function getAudioMediaUrl(MediaResource $mr) {
        $audio = $this->getRepository()->findOneBy(array('mediaResource' => $mr, 'type' => 'audio'));
        if ($audio) {
            return $audio->getUrl();
        }
        return null;
    }

    public function getVideoMedia(MediaResource $mr) {
        $video = $this->getRepository()->findOneBy(array('mediaResource' => $mr, 'type' => 'video'));
        if ($video) {
            return $video->getUrl();
        }
        return null;
    }
/*
    public function upload(UploadedFile $file, $url) {
        if (null === $file) {
            return;
        }
        $uploaded = $file->move($this->getUploadRootDir(), $url);
        unset($file);
        return $uploaded;
    }

    public function removeUpload($filename) {
        $url = $this->getUploadRootDir() . '/' . $filename;
        if (file_exists($url)) {
            unlink($url);
            return true;
        } else {
            return false;
        }
    }

    public function getAbsolutePath() {
        return null === $this->getUrl() ? null : $this->getUploadRootDir() . '/' . $this->url;
    }

    public function getWebPath() {
        return null === $this->getUrl() ? null : $this->getUploadDir() . '/' . $this->url;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'media/uploads';
    }
*/
}
