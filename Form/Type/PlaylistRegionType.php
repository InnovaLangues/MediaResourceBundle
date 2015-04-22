<?php

namespace Innova\MediaResourceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Innova\MediaResourceBundle\Repository\RegionRepository;

/**
 * Description of PlaylistType
 *
 */
class PlaylistRegionType extends AbstractType {

    protected $mr;

    public function __construct($mr) {
        $this->mr = $mr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder->add('region', 'entity', array(
                    'class' => 'InnovaMediaResourceBundle:Region',
                    'label' => 'segment',
                    'query_builder' => function(EntityRepository $er)  {
                        return $er->createQueryBuilder('r')
                                ->where('r.mediaResource = :mr' )
                                ->setParameter('mr', $this->mr);
                        }
                    )
                )
                ->add('ordering', 'integer', array(
                    'required' => true, 'label' => 'order', 'attr' => array('min' => 1)
                )
        );
    }

    public function getDefaultOptions() {
        return array(
            'data_class' => 'Innova\MediaResourceBundle\Entity\PlaylistRegion',
            'translation_domain' => 'resource_form',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults($this->getDefaultOptions());
        return $this;
    }

    public function getName() {
        return 'media_resource_playlist_region';
    }

}
