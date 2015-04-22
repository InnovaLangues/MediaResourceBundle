<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Innova\MediaResourceBundle\Entity\Playlist;

/**
 * Description of PlaylistManager
 *
 */
class PlaylistManager {
    
    protected $em;
    protected $translator;

    public function __construct(EntityManager $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function save(Playlist $pl) {
        $this->em->persist($pl);
        $this->em->flush();
        return $pl;
    }
}
