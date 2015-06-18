<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Entity\Media;

/**
 * MediaResource Manager
 */
class MediaResourceManager {

    protected $em;
    protected $translator;
    protected $uploadFileDir;

    public function __construct(EntityManager $em, TranslatorInterface $translator, $uploadfileDir) {
        $this->em = $em;
        $this->translator = $translator;
        $this->uploadFileDir = $uploadfileDir;
    }

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:MediaResource');
    }

    /**
     * Delete associated Media (removing from server hard drive) before deleting the entity
     * @param MediaResource $mr
     * @return \Innova\MediaResourceBundle\Manager\MediaResourceManager
     */
    public function delete(MediaResource $mr) {
        // delete all files from server
        $medias = $mr->getMedias();
        foreach ($medias as $media) {
            $this->removeUpload($media->getUrl());
        }
        $this->em->remove($mr);
        $this->em->flush();
        return $this;
    }

    /**
     * Handle MediaResource associated files
     * @param UploadedFile $file
     * @param MediaResource $mr
     */
    public function handleMediaResourceMedia(UploadedFile $file, MediaResource $mr) {
        // set new filename
        $name = $this->setFileName($file->getClientOriginalName());

        // upload file
        if ($this->upload($file, $name)) {
            // encode original file and create Media Entity
            $audioMedia = $this->createAudioMedia($name, false);
            // update Exercise with new Media Entity infos
            if ($audioMedia) {
                $mr->addMedia($audioMedia);
                $this->em->persist($mr);
                $audioMedia->setMediaResource($mr);
                // delete original file
                $this->removeUpload($name);
            } else {
                $this->removeUpload($name);
                $message = $this->translator->trans("error_while_encoding", array(), "media_resource");
                throw new \Exception($message);
            }
            $this->em->flush();
        } else {
            $message = $this->translator->trans("error_while_uploading", array(), "media_resource");
            throw new \Exception($message);
        }
        return $mr;
    }

    public function copyMedia(MediaResource $mr, Media $origin) {

        $newName = $this->setFileName($origin->getUrl());
        // make a copy of the file
        if (copy($this->getUploadDirectory() . '/' . $origin->getUrl(), $this->getUploadDirectory() . '/' . $newName)) {
            // duplicate file
            $new = new Media();
            $new->setType($origin->getType());
            $new->setUrl($newName);
            $mr->addMedia($new);
            $this->em->persist($mr);
            $new->setMediaResource($mr);
        }
    }

    /**
     * set a name for a file
     * @param string $originalName
     * @return string the new name
     */
    public function setFileName($originalName) {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        return sha1(uniqid(mt_rand(), true)) . '.' . $ext;
    }

    /**
     * encode original file (in any case!!) an create a Media Entity
     * @param string $url original video file url
     * @param bool $fromVideo if we must extract audio from video or just convert original file to ogg
     * @return Media or null if conversion error
     */
    public function createAudioMedia($url) {

        // we want to ensure that the audio file uploaded will be correctly decoded by audioBuffer.decodeAudioData (WebAudio Api)
        // so we want to force the audio format in any case
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $name = basename($url, "." . $ext);
        $cmd = 'avconv -i ' . $this->getUploadDirectory() . '/' . $url . ' -id3v2_version 3 -acodec  libmp3lame -ac 2 -ar 44100 -ab 128k -f mp3 - > ' . $this->getUploadDirectory() . '/' . $name . '.mp3';
        
        $output;
        $returnVar;
        exec($cmd, $output, $returnVar);
        // error
        if ($returnVar !== 0) {
            return null;
        } else {
            // 2 - create a Media with this sound file
            $media = new Media();
            $media->setType('audio');
            $media->setUrl($name . '.mp3');
            return $media;
        }
    }

    public function upload(UploadedFile $file, $url) {
        if (null === $file) {
            return;
        }
        $uploaded = $file->move($this->getUploadDirectory(), $url);
        unset($file);
        return $uploaded;
    }

    public function removeUpload($filename) {
        $url = $this->getUploadDirectory() . '/' . $filename;
        if (file_exists($url)) {
            unlink($url);
            return true;
        } else {
            return false;
        }
    }

    protected function getUploadDirectory() {
        return $this->uploadFileDir;
    }
}
