<?php
namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Titre de la tâche'])
            ->add('description', TextareaType::class, [
                'label'    => 'Description',
                'required' => false,
            ])
            ->add('deadline', DateType::class, [
                'label'    => 'Date',
                'widget'   => 'single_text',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label'   => 'Statut',
                'choices' => [
                    'To Do' => 'todo',
                    'Doing' => 'doing',
                    'Done'  => 'done',
                ],
            ])
            ->add('employees', EntityType::class, [
                'class'        => Employee::class,
                'choice_label' => fn(Employee $employee): string => $employee->getFirstname() . ' ' . $employee->getName(),
                'label'        => 'Membre',
                'required'     => false,
                'placeholder'  => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
