<?php

namespace App\Form;

use App\Entity\Tableau\Employe;
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

class EmployeType extends AbstractType
{

    /** @var MetierRepository */
    private $metierRepository;

    public function __construct(MetierRepository $metierRepository)
    {
        $this->metierRepository = $metierRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $metiers = $this->metierRepository->findMetiers();
        dump($metiers);
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'required' => true,
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('metier', ChoiceType::class, [
                'choices' => [
                    'Web developer' => $metiers[0],
                    'Web designer' => $metiers[1],
                    'SEO Manager' => $metiers[2],
                    'Marketing Manager' => $metiers[3],
                    'Mobile developer' => $metiers[4],
                    'DevOps Engineer' => $metiers[5],
                    'Security Engineer' => $metiers[6],
                ],
                'label' => 'Metier',
                'required' => true,
            ])
            ->add('coutHoraire', IntegerType::class, [
                'label' => 'Cout Horaire',
                'required' => true,
            ])
            ->add('dateEmbauche', DateType::class, [
                'label' => 'Date Embauche',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}