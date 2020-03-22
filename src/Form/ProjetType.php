<?php

namespace App\Form;

use App\Entity\Tableau\Projet;
use App\Repository\Tableau\EmployeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{

    /** @var EmployeRepository */
    private $employeRepository;

    public function __construct(EmployeRepository $employeRepository)
    {
        $this->employeRepository = $employeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('prixVente', IntegerType::class, [
                'label' => 'Prix Vente',
                'required' => true,
            ])
            ->add('dateCreation', DateType::class, [
                'label' => 'Date Creation',
                'required' => true,
            ])
            ->add('dateLivraison', DateType::class, [
                'label' => 'Date Livraison',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}