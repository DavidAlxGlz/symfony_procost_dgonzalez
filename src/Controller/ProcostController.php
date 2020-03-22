<?php

namespace App\Controller;

use App\Entity\Tableau\Employe;
use App\Entity\Tableau\Metier;
use App\Entity\Tableau\Projet;
use App\Form\EmployeType;
use App\Form\MetierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProjetType;
use App\Repository\Tableau\EmployeRepository;
use App\Repository\Tableau\HoursRepository;
use App\Repository\Tableau\MetierRepository;
use App\Repository\Tableau\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

class ProcostController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    
    /** @var EmployeRepository */
    private $employeRepository;

    /** @var ProjetRepository */
    private $projetRepository;

    /** @var HoursRepository */
    private $hoursRepository;

    /** @var HoursRepository */
    private $metierRepository;

    public function __construct(EntityManagerInterface $em,EmployeRepository $employeRepository,ProjetRepository $projetRepository,HoursRepository $hoursRepository,MetierRepository $metierRepository)
    {
        $this->employeRepository = $employeRepository;
        $this->projetRepository = $projetRepository;
        $this->hoursRepository = $hoursRepository;
        $this->metierRepository = $metierRepository;
        $this->em = $em;
    }

    /**
     * @Route("procost/main", name="app_main")
     */
    public function index()
    {
        $livr = $this->projetRepository->findAll();
        $encour = $this->projetRepository->findLivre();
        $tauxLivraison = (count($encour)/count($livr))*100;
        dump($this->employeRepository->findTopEmploye());
        
        return $this->render('procost/index.html.twig', [
            'employes' => $this->employeRepository->findAll(),
            'projetsEnCours' => $this->projetRepository->findEnCours(),
            'projetsLivres' => $this->projetRepository->findLivre(),
            'hoursTotal' => $this->hoursRepository->findSumHours(),
            'tempsProduction' => $this->hoursRepository->findTempsProduction(),
            'dernierProjets' => $this->projetRepository->findDerniers(),
            'rentabilite' => $this->hoursRepository->findHoursLivraison(),
            'tauxLivraison'=> $tauxLivraison,
            
        ]);
    }

    /**
     * @Route("procost/Projets/list", name="app_projets_list")
     * 
     */
    public function projetsList()
    {
        return $this->render('procost/lists/projetsList.html.twig',[
            'projets' => $this->projetRepository->findAll(),
        ]);
    }

    /**
     * @Route("procost/Employes/list", name="app_employes_list")
     */
    public function employesList()
    {
        return $this->render('procost/lists/employesList.html.twig',[
            'employes' => $this->employeRepository->findAll(),
        ]);
    }

    /**
     * @Route("procost/Metiers/list", name="app_metiers_list")
     */
    public function metiersList()
    {
        return $this->render('procost/lists/metiersList.html.twig',[
            'metiers' => $this->metierRepository->findAll(),
        ]);
    }

    

    /**
     * @Route("procost/Employes/detail/{id}", name="app_employes_detail" , requirements={"id" = "\d+"})
     */
    public function employesDetail(int $id)
    {
        dump($this->hoursRepository->findHistoricProduction($id));
        return $this->render('procost/details/employesdetail.html.twig',[
            'employeDetails' => $this->employeRepository->find($id),
            'projets' => $this->projetRepository->findAll(),
            'horas' => $this->hoursRepository->findHistoricProduction($id),
            'employe' => $id,
            
        ]);
    }

    /**
     * @Route("procost/Projets/detail/{id}", name="app_projets_detail",requirements={"id" = "\d+"})
     */
    public function projetsDetail($id)
    {
        dump($this->hoursRepository->findHistoricProductionProjet($id));
        return $this->render('procost/details/projetsdetail.html.twig',[
            'projetDetails' => $this->projetRepository->find($id),
            'horas' => $this->hoursRepository->findHistoricProductionProjet($id),
            'employes' => $this->employeRepository->findAll(),
            'projet' => $id,
        ]);
    }


    //
    //Routes to Edit
    //

    /**
     * @Route("procost/Metiers/form/{id}", name="app_metiers_form",requirements={"id" = "\d+"})
     */
    public function metiersForm($id,Request $request):Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $metier = $this->em->getRepository(Metier::class)->find($id);

        $form = $this->createForm(MetierType::class,$metier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success',"Well Done!! now try to learn french :(");
            $this->em->persist($metier);
            $this->em->flush();
        }
        return $this->render('procost/form/metiersForm.html.twig',[
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("procost/Projets/form/{id}", name="app_projets_form",requirements={"id" = "\d+"})
     */
    public function projetsForm($id,Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $projet = $this->em->getRepository(Projet::class)->find($id);
       $form = $this->createForm(ProjetType::class,$projet);

       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $this->addFlash('success',"Well Done!! now try to learn french :(");
        $this->em->persist($projet);
        $this->em->flush();
       }
        return $this->render('procost/form/projetsForm.html.twig',[
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("procost/Employes/form/{id}", name="app_employes_form",requirements={"id" = "\d+"})
     */
    public function employesForm($id,Request $request): Response
    {
        $employe = $this->em->getRepository(Employe::class)->find($id);
        $form = $this->createForm(EmployeType::class,$employe);
 
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
         $this->addFlash('success',"Well Done!! now try to learn french :(");
         $this->em->persist($employe);
         $this->em->flush();
        }   
        return $this->render('procost/form/employesForm.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    //
    //Forms to Add
    //

    /**
     * @Route("procost/Metiers/form/Add", name="app_metiers_add")
     */
    public function metiersFormAdd(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $metier = new Metier();
        $form = $this->createForm(MetierType::class,$metier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success',"Well Done!! now try to learn french :(");
            $this->em->persist($metier);
            $this->em->flush();
        }

        return $this->render('procost/add/metiersForm.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
    * @Route("procost/Employes/form/Add", name="app_employes_add")
    */
   public function employesFormAdd(Request $request): Response
   {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

       $employe = new Employe();
       $form = $this->createForm(EmployeType::class,$employe);

       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $this->addFlash('success',"Well Done!! now try to learn french :(");
        $this->em->persist($employe);
        $this->em->flush();
       }
       return $this->render('procost/add/employesForm.html.twig',[
           'form' => $form->createView(),
       ]);
   }

     /**
     * @Route("procost/Projets/form/Add", name="app_projets_add")
     */
    public function projetsFormAdd(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

       $projet = new Projet();
       $form = $this->createForm(ProjetType::class,$projet);

       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $this->addFlash('success',"Well Done!! now try to learn french :(");
        $this->em->persist($projet);
        $this->em->flush();
       }
        return $this->render('procost/add/projetsForm.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}
