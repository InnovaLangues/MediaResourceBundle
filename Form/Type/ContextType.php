<?php

namespace Innova\MediaResourceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
/**
 * Description of ContextType
 *
 */
class ContextType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('hasAutoPause', 'checkbox', array(
                            'label' => 'Autoriser le mode pause automatique ?',
                            'required' => false,
                    )
                )
                ->add('hasLiveListening', 'checkbox', array(
                            'label' => 'Autoriser le mode live ?',
                            'required' => false,
                    )
                )
                ->add('hasActiveListening', 'checkbox', array(
                            'label' => 'Autoriser le mode actif ?',
                            'required' => false,
                    )
                )
                ->add('playlist', 'entity', array(
                            'class' => 'InnovaMediaResourceBundle:Playlist',
                            'query_builder' => function(EntityRepository $er) use ($mediaResourceId){
                                $er->findByMediaResource($mediaResourceId);
                            }
                )
        );
    }

    public function getDefaultOptions() {
        return array(
            'data_class' => 'Innova\MediaResourceBundle\Entity\Context',
            'translation_domain' => 'resource',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults($this->getDefaultOptions());
        return $this;
    }

    public function getName() {
        return 'media_resource_context';
    }

}
