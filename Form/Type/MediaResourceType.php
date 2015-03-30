<?php

namespace Innova\MediaResourceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
/**
 * Description of MediaResourceType
 *
 */
class MediaResourceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text', array('required' => true))
                ->add('file', 'file', array('required' => true, 'mapped' => false, 'constraints' => array(
                    new NotBlank(),
                    new File(array(
                                    'mimeTypes' => array(
                                        'audio/mpeg',
                                        'audio/ogg', 
                                        'audio/wav',
                                        'audio/x-wav',
                                        'application/ogg'
                                        ),
                                        'mimeTypesMessage' => 'The type of the file is invalid ({{ type }}). Allowed types are {{ types }}. Please check that your file is well encoded.'
                                    )
                                    
                            )
                )));
    }

    public function getDefaultOptions() {
        return array(
            'data_class' => 'Innova\MediaResourceBundle\Entity\MediaResource',
            'translation_domain' => 'resource',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults($this->getDefaultOptions());
        return $this;
    }

    public function getName() {
        return 'media_resource';
    }

}
