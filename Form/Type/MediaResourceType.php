<?php

namespace Innova\MediaResourceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of MediaResourceType
 *
 */
class MediaResourceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text')
                ->add('file', 'file');
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
