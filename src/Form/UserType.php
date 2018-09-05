<?php 
/*
 * Create User Form
 * Author : Elangovan.Sundar
 */

namespace App\Form;

use App\Document\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Validator\Constraints\DateTime;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $educationArray = [     
            'UG College' => 'UG College',
            'PG College' => 'PG College',
            'Masters College' => 'Masters College',
            'Others College' => 'Others College',
        ];

        $builder
            ->setAction('new')
            ->setMethod('POST')
            ->add('userFirstName', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('userLastName', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('userEmail', EmailType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('userMobileNumber', IntegerType::class, array(
               'attr' => array('class' => 'form-control')
            ))
            ->add('userDateofBirth', DateType::class, array(
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                
            ))
            ->add('userEducation',ChoiceType::class,array(
                'choices' => $educationArray, 'multiple' => true, 'attr' => array('class' => 'form-control')
            ))
            ->add('userBloodGroup', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('userGender', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}