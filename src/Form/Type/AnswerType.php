<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Question;
use App\Lib\myAnswerValidator;

class AnswerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->add('body', TextareaType::class)
                ->add('question_id', HiddenType::class);;
	}
	
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => myAnswerValidator::class,
    ]);
  }

	public function getName()
	{
		return 'answer';
	}
}

