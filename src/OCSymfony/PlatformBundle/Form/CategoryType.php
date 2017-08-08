<?php

namespace OCSymfony\PlatformBundle\Form;

use OCSymfony\PlatformBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = "D%";

        $builder->add('categories', EntityType::class, array(
            'class' => 'OCSymfonyPlatformBundle:Category',
            'choice_label' => 'name',
            'multiple' => true,
            'query_builder' => function(CategoryRepository $repository) use($pattern) {
                return $repository->getLikeQueryBuilder($pattern);
            }
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCSymfony\PlatformBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ocsymfony_platformbundle_category';
    }


}
