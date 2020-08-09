<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Question;

class QuestionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
    $builder->add('title', TextType::class, [
                'constraints' => [
                  new Assert\Length(['min' => 2]),
                  new Assert\NotBlank,
                ],
              ])
		        ->add('body', TextareaType::class);
	}
	
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Question::class,
    ]);
  }

	public function getName()
	{
		return 'question';
	}
}
