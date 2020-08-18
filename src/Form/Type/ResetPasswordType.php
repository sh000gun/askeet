<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Lib\myResetPasswordValidator;


class ResetPasswordType extends AbstractType
{ 
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', EmailType::class);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => myResetPasswordValidator::class, 
    ]);
  }

	public function getName()
	{
		return 'resetPassword';
	}
}
