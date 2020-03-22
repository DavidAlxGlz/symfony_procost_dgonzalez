<?php

namespace App\Form;

use App\Entity\Tableau\Hours;
use App\Repository\Tableau\MetierRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class HourType extends AbstractType
{

    /** @var MetierRepository */
    private $metierRepository;

    public function __construct(MetierRepository $metierRepository)
    {
        $this->metierRepository = $metierRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('employe', IntegerType::class, [
                'label' => 'Employe',
                'required' => true,
            ])
            ->add('projet', ChoiceType::class, [
                'label' => 'Projet',
                'required' => true,
            ])
            ->add('hours', IntegerType::class, [
                'label' => 'Hours',
                'required' => true,
            ])
            ->add('dateSaisie', DateType::class, [
                'label' => 'dateSaisie',
                'required' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hours::class,
        ]);
    }
}