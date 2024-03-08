<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Form\FormListenerFactory;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function __construct(private FormListenerFactory $listenerFactory){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Titre',
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'constraints' => new Sequentially([
                    new Length(min: 4),
                    new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Ceci n\'est pas un slug valide.')
                ]) 
            ])
//            ->add('recipes', EntityType::class, [
//                'class' => Recipe::class,
//                'choice_label' => 'title',
//                'multiple' => true,
//                'expanded' => true,
 //               'by_reference' => false
//            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->listenerFactory->timestamps());
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'validation_groups' => ['Default', 'Extra']
        ]);
    }
}
