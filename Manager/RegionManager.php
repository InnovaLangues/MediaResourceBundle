<?php

namespace Innova\MediaResourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Innova\MediaResourceBundle\Entity\MediaResource;
use Innova\MediaResourceBundle\Entity\Region;
use Innova\MediaResourceBundle\Entity\RegionConfig;

class RegionManager {

    protected $em;
    protected $translator;

    public function __construct(EntityManager $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
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

    public function findByAndOrder(MediaResource $mr) {
        return $this->getRepository()->findBy(array('mediaResource' => $mr), array('start' => 'ASC'));
    }

    public function getRepository() {
        return $this->em->getRepository('InnovaMediaResourceBundle:Region');
    }

    /**
     * Update an exercise (title)
     * @param MediaResource $mr
     * @param Array of stdClass $region 
     */
    public function handleMediaResourceRegions(MediaResource $mr, $data) {

        $regions = $this->getRegionsFromData($data);
        $this->deleteUnusedRegions($mr, $regions);
        // update or create
        foreach ($regions as $region) {
            // update
            if ($region['id']) {
                $entity = $this->getRepository()->find($region['id']);
            }
            // new
            else {
                $entity = new Region();
                $entity->setMediaResource($mr);
                $regionConfig = new RegionConfig();
                $regionConfig -> setRegion($entity);
                $entity -> setRegionConfig($regionConfig);
            }
            
            if ($entity) {
                $entity->setStart($region['start']);
                $entity->setEnd($region['end']);
                $entity->setNote($region['note']);
                
                $config = $entity -> getRegionConfig();
                $config -> setHelpText($region['text']);
                $config -> setHasLoop($region['loop']);
                $config -> setHasRate($region['rate']);
                $config -> setHasBackward($region['backward']);
                
                
                $this->save($entity);
            }
        }
        return $mr;
    }

    private function getRegionsFromData($data) {
        $regions = array();
        $starts = $data['start'];
        $ends = $data['end']; // array
        $notes = $data['note'];
        $ids = $data['region-id'];

        $helpRegionIds = $data['help-region-id'];
        $loops = $data['loop'];
        $backwards = $data['backward'];
        $rates = $data['rate'];
        $texts = $data['text'];
        
        $nbData = count($starts);

        for ($i = 0; $i < $nbData; $i++) {
            $regions[] = array(
                                'id' => $ids[$i], 
                                'start' => $starts[$i], 
                                'end' => $ends[$i], 
                                'note' => $notes[$i], 
                                'help-region-id' => $helpRegionIds[$i],
                                'loop' => $loops[$i],
                                'backward' => $backwards[$i],
                                'rate'  => $rates[$i],
                                'text'  => $texts[$i]
                        );
        }

        return $regions;
    }

    private function deleteUnusedRegions(MediaResource $mr, $toCheck) {
        // get existing regions in database
        $existing = $this->getRepository()->findBy(array('mediaResource' => $mr));

        // delete regions if they are no more here
        if (count($existing) > 0) {
            $toDelete = $this->checkIfRegionExists($existing, $toCheck);

            foreach ($toDelete as $unused) {
                $this->delete($unused);
            }
        }
    }

    private function checkIfRegionExists($existing, $toCheck) {
        $toDelete = [];
        foreach ($existing as $region) {
            $found = false;
            foreach ($toCheck as $current) {
                if ($current['id'] == $region->getId()) {
                    $found = true;
                    break;
                }
            }

            // if not found, this is an unused region
            if (!$found) {
                $toDelete[] = $region;
            }
        }
        return $toDelete;
    }

}
