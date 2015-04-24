<?php

namespace Innova\MediaResourceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * Description of PlaylistType
 *
 */
class PlaylistType extends AbstractType {
    
    protected $mr;
    
    public function __construct($mr){
        $this->mr = $mr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {  
 
        $builder->add( 
                    'name', 'text', array('required' => true)
                )
                ->add('playlistRegions', 'collection', array(
                                    'type' => new PlaylistRegionType($this->mr), 
                                    'allow_add' => true,
                                    'allow_delete' => true,
                                    'label' => ' ',
                                    'mapped' => true,
                                    'by_reference' => false,
                                    'options' => array('required' => false)
                     )
                )
                ->add('save', 'submit', array( 'label' => ' ', 'attr' => array('class' => 'btn btn-default fa fa-floppy-o')));
    }

    public function getDefaultOptions() {
        return array(
            'data_class' => 'Innova\MediaResourceBundle\Entity\Playlist',
            'translation_domain' => 'resource_form',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults($this->getDefaultOptions());
        return $this;
    }

    public function getName() {
        return 'media_resource_playlist';
    }

}
