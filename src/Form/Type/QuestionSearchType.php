<?php

namespace App\Form\Type;

use App\Lib\myQuestionSearchValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', TextType::class)
                ->add('search_all', CheckboxType::class, [
            'label'    => 'search with all words',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => myQuestionSearchValidator::class,
    ]);
    }

    public function getName()
    {
        return 'questionSearch';
    }
}
