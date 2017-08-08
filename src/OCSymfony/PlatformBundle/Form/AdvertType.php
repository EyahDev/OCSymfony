<?php

namespace OCSymfony\PlatformBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Liste des champs du formulaire
        $builder
            ->add('date', DateTimeType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            ->add('image', ImageType::class)
            ->add('categories', EntityType::class, array(
                'class'        => 'OCSymfonyPlatformBundle:Category',
                'choice_label' => 'name',
                'multiple'     => true
            ))
            ->add('save', SubmitType::class);

        // Ajout d'un evenement
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            // Fonction déclenché lors de l'evénement
            function(FormEvent $event) {
                // Récupération de l'objet Advert
                $advert = $event->getData();

                // Sortie de la fonction si advert vaut null
                if (null === $advert) {
                    return;
                }

                // Si l'annonce n'est pas publiée ou n'existe pas
                if (!$advert->getPublished() || null === $advert->getId()) {

                    // Ajout du champ published
                    $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                } else {

                    // Si non, on supprime
                    $event->getForm()->remove('published');
                }
            });
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCSymfony\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ocsymfony_platformbundle_advert';
    }


}
