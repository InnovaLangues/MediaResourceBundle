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

    public function getPlaylistRegionsInOrder(Playlist $playlist) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('plr')
                ->from('Innova\MediaResourceBundle\Entity\PlaylistRegion', 'plr')
                ->where('plr.playlist = :playlistId')
                ->orderBy('plr.ordering', 'ASC')
                ->setParameter('playlistId', $playlist->getId());
        
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

}
