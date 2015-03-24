<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Innova\MediaResourceBundle\Entity\Region;

class RegionConfigManager {

    protected $em;
    protected $translator;

    public function __construct(EntityManager $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:Region');
    }

    public function save(Region $region) {
        $this->em->persist($region);
        $this->em->flush();
        return $region;
    }

    public function delete(Region $region) {
        $this->em->remove($region);
        $this->em->flush();
        return $this;
    }
}
