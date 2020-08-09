<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Lib\myLoginValidator;


class LoginType extends AbstractType
{ 
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nickname', TextType::class)
		        ->add('password', PasswordType::class)
                ->add('_target_path', HiddenType::class);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => myLoginValidator::class, 
    ]);
  }

	public function getName()
	{
		return 'login';
	}
}
