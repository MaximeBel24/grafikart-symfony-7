<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;

class CategoryType extends AbstractType
{
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
        ;
    }


    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event -> getData();
        if(empty($data['slug'])){
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['name']));
            $event->setData($data);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'validation_groups' => ['Default', 'Extra']
        ]);
    }
}
