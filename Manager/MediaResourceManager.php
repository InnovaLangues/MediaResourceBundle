<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Entity\Media;

/**
 * Activity Manager
 * Performs CRUD actions for MediaResource
 */
class MediaResourceManager {

    protected $em;
    protected $kernelRoot;
    protected $translator;

    public function __construct(EntityManager $em, $kernelRoot, TranslatorInterface $translator) {
        $this->em = $em;
        $this->kernelRoot = $kernelRoot;
        $this->translator = $translator;
    }

    public function getAll() {
        return $this->getRepository()->findAll();
    }

    public function findOne($id) {
        return $this->getRepository()->find($id);
    }

    public function save(MediaResource $mr) {
        $this->em->persist($mr);
        $this->em->flush();
        return $mr;
    }

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

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:MediaResource');
    }

    /**
     * MediaResource name (title) update
     * @param MediaResource $mr
     * @param string $name exercise name
     */
    public function updateMediaResourceName(MediaResource $mr, $name) {
        if ($mr->getName() != $name) {
            $mr->setName($name);
        }
        return $this->save($mr);
    }

    /**
     * Handle MediaResource associated files
     * @param UploadedFile $file
     * @param MediaResource $mr
     */
    public function handleMediaResourceMedia(UploadedFile $file, MediaResource $mr) {

        // get file type (audio / video)
        // $originalName = $file->getClientOriginalName();
        // $type = $this->getMediaResourceMediaType($originalName);
        // set new filename
        $name = $this->setFileName($file);

        // upload file
        if ($this->upload($file, $name)) {
            // TODO depending on original format (or not if we want to force encoding format ie mp3 128kbs 44.1 wav 44.1 PCM) encode to mp3 and wav
            $audioMedia = $this->createAudioMedia($name, false);
            // update Exercise with media infos
            if ($audioMedia) {
                $mr->addMedia($audioMedia);
                $this->em->persist($mr);
                $audioMedia->setMediaResource($mr);
                // TODO if needed delete original file
                // $removed = $this->removeUpload($name);
            } else {
                $removed = $this->removeUpload($name);
                $message = $this->translator->trans("error_while_encoding", array(), "media_resource");
                throw new \Exception($message);
            }

            $this->em->flush();
        } else {
            $message = $this->translator->trans("error_while_uploading", array(), "media_resource");
            throw new \Exception($message);
        }
    }

    /**
     * set a name for the file
     * @param UploadedFile $file
     * @return string the new name
     */
    public function setFileName(UploadedFile $file) {
        if (null !== $file) {
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            return sha1(uniqid(mt_rand(), true)) . '.' . $ext;
        }
    }

    /**
     * Get the type of the file regarding it's extension
     * (could not rely on mimeType)
     * @param string $name the full name of the file (with extension)
     * @return string audio or video
     */
    public function getMediaResourceMediaType($name) {

        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $audio_ext = ['ogg', 'mp3', 'wav'];
        $video_ext = ['ogv', 'mp4'];
        $type = '';
        if (in_array($ext, $audio_ext)) {
            $type = 'audio';
        } elseif (in_array($ext, $video_ext)) {
            $type = 'video';
        }
        return $type;
    }

    /**
     * @param string $url original video file url
     * @param bool $fromVideo if we must extract audio from video or just convert original file to ogg
     * @return Media or null if error
     */
    public function createAudioMedia($url) {

        $media = new Media();
        $media->setType('audio');
        $media->setUrl($url);
        return $media;

        /*$ext = pathinfo($url, PATHINFO_EXTENSION);

        // remove extension from video name
        $name = basename($url, "." . $ext);

        // extract audio sound file from the video
        $cmd = 'avconv -i ' . $this->getUploadRootDir() . '/' . $url . ' -acodec libvorbis -q:a 5 ' . $this->getUploadRootDir() . '/' . $name . '.ogg';


        exec($cmd, $output, $returnVar);
        // error
        if ($returnVar !== 0) {
            return null;
        } else {
            // 2 - create a Media with this sound file
            $media = new Media();
            $media->setType('audio');
            $media->setUrl($name . '.ogg');
            return $media;
        }*/
    }

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
        return __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'bundles/innovamediaresource/uploads';
    }

}
