<?php

namespace App\DataFixtures;

use App\Entity\Tableau\Employ;
use App\Entity\Tableau\Employe;
use App\Entity\Tableau\Hours;
use App\Entity\Tableau\Metier;
use App\Entity\Tableau\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    private $encoder;

    /** @var ObjectManager */
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->User();
        $this->Metiers();
        $this->Projets();
        $this->Employes();
        $this->Hours();
        $this->manager->flush();
    }

    public function User(): void
    {

        //añadir getreference para utilizar emails existentes
        $user = new User();

        $plainPassword = 'mexicanito';
        $encoded = $this->encoder->encodePassword($user, $plainPassword);

        $user->setPassword($encoded);
        $user->setUsername('david_alx_glz@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);

        $this->manager->persist($user);

        $user2 = new User();

        $plainPassword = 'baguette';
        $encoded = $this->encoder->encodePassword($user2, $plainPassword);

        $user2->setPassword($encoded);
        $user2->setUsername('figaro@gmail.com');
        $user2->setRoles(['ROLE_EMPLOYE']);

        $this->manager->persist($user2);
    }

    

    public function Employes():void
    {
        $dateStart = new DateTime('01-01-1960');
        $dateEnd = new DateTime('01-01-2005');
        for ($i = 1; $i < 15; $i++){
            $employ = (new Employe())
            ->setNom("Nom".$i)
            ->setPrenom("Prenom".$i)
            ->setEmail("employ".$i."@gmail.com")
            ->setMetier($this->getReference('metier'.random_int(0,6)))
            ->setCoutHoraire(mt_rand(10,40))
            ->setDateEmbauche($this->randomDateInRange($dateStart,$dateEnd));

            $this->addReference('employe' . $i, $employ);
            $this->manager->persist($employ);
        }   
    }

    public function Projets(): void
    {

        for ($i = 1; $i < 15; $i++) {

            $projet = (new Projet())
                ->setNom("projet" . $i)
                ->setDescription("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,")
                ->setPrixVente(mt_rand(1, 1000))
                ->setDateCreation(new DateTime())
                ->setDateLivraison($this->dateLivraison());
                $this->addReference('projet' . $i, $projet);

            $this->manager->persist($projet);
        }
    }

    public function dateLivraison()
    {
        $random = random_int(1,2);
        if($random === 1)
        {
      return  $this->randomDateInRange(new DateTime(), new DateTime('20-01-2025'));
        }else{
            return NULL;
        }
    }

    

    public function Metiers(): void
    {
        $metiers = [

            'Web developer',
            'Web designer',
            'SEO Manager',
            'Marketing Manager',
            'Mobile developer',
            'DevOps Engineer',
            'Security Engineer',

        ];

        foreach ($metiers as $key => $name) {
            $metier = (new Metier())
                ->setNom($name);

            $this->manager->persist($metier);
            $this->addReference('metier' . $key, $metier);
        }
    }

    public function Hours()
    {
        $dateStart = new DateTime("2019-01-01");
        $dateEnd = new DateTime();
        for($i = 1;$i < 30;$i++)
        {
            $hour = (new Hours())
            ->setEmploye($this->getReference('employe'.random_int(1,14)))
            ->setProjet($this->getReference('projet'.random_int(1,14)))
            ->setDateSaisie($this->randomDateInRange($dateStart,$dateEnd))
            ->setHours(random_int(1,8));

            $this->manager->persist($hour);

        }
    }

    

    public function randomDateInRange(DateTime $start, DateTime $end)
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate;
    }
}
