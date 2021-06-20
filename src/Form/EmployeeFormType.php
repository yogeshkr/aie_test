<?php


namespace App\Form;


use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Employee Name', 'required' => true, 'attr' => ['data-validation-engine' => 'validate[required]', 'class' => 'form-control']])
            ->add('age', TextType::class, ['label' => 'Age', 'required' => true, 'attr' => ['data-validation-engine' => 'validate[required]', 'class' => 'form-control']])
            ->add('dateOfJoining', DateType::class, ['label' => 'Date of Joining', 'required' => true, 'attr' => ['data-validation-engine' => 'validate[required]', 'class' => 'form-control'], 'widget' => 'single_text'])
            ->add('save', SubmitType::class, ['label' => 'Update', 'attr' => ['class' => 'btn btn-primary clear']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'name' => null,
            'age' => null,
            'dateOfJoining' => new \DateTime(),
        ]);
    }

    public function getBlockPrefix()
    {
        return parent::getBlockPrefix();
    }
}