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

    /**
     * Custom method to record PlaylistRegion entities
     * @param Playlist $playlist
     * @param array $data POST data passed by the symfony form 
     */
    public function createPLaylistRegions(Playlist $playlist, $data) {
        foreach ($data as $plRegion) {
            $plr = new PlaylistRegion();
            $plr->setPlaylist($playlist);
            $plr->setOrdering(intval($plRegion['ordering']));
            $region = $this->em->getRepository('InnovaMediaResourceBundle:Region')->find($plRegion['region']);
            $plr->setRegion($region);
            $this->save($plr);
        }
    }

}
