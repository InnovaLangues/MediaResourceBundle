<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Innova\MediaResourceBundle\Entity\Playlist;
use Innova\MediaResourceBundle\Entity\PlaylistRegion;

/**
 * Description of PlaylistManager
 *
 */
class PlaylistRegionManager {

    protected $em;
    protected $translator;

    public function __construct(EntityManager $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function save(PlaylistRegion $plRegion) {
        $this->em->persist($plRegion);
        $this->em->flush();
        return $plRegion;
    }
   

}
