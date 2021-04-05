<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Question;
use App\Form\ListeType;
use App\Repository\ProfesseurRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use ZipArchive;

class DefautController extends AbstractController
{
    /**
     * @Route("/liste/{id}", name="liste")
     */
    public function index(Liste $liste): Response
    {
        return $this->render('Defaut/Liste.html.twig', [
            'controller_name' => 'DefautController',
            'liste'=>$liste
        ]);
    }


    /**
     * @Route("/", name="login")
     */
    public function login(Request $request, ProfesseurRepository $professeurRepository, SessionInterface $session): Response
    {

        $login  = $request->request->get('login');
        $mdp  = $request->request->get('motDePasse');
        if($login != null && $mdp != null){
            $prof = $professeurRepository->findOneBy(['login'=>$login, 'motDePasse'=>$mdp]);
            if($prof != null){
                $session->set('idProf', $prof->getId());
                $session->set('nom', $prof->getNom());
                $session->set('prenom', $prof->getPrenom());
                return $this->redirectToRoute('admin');
            }
        }
        return $this->render('Defaut/Accueil.html.twig', [
            'controller_name' => 'DefautController',

        ]);
    }
    /**
     * @Route("create/liste", name="listeCreate")
     * @Route("edit/liste/{id}", name="listeEdit")
     */
    public function createList(Liste $liste=null,SessionInterface $session, EntityManagerInterface $manager, Request $request, QuestionRepository $questionRepository): Response
    {
        if ($session->get('idProf') == null) {
            return $this->redirectToRoute('login');
        }
        if(!$liste) {
            $liste = new Liste();
            }
        $formListe = $this->createForm(ListeType::class, $liste);
        $formListe->handleRequest($request);
        if($formListe->isSubmitted()){
            if ( $formListe->isValid()) {
               if(!$liste->getId()){
                   $liste->setDateCreation(strtotime('now'));
               }
               $manager->persist($liste);
               $manager->flush();
            }
            else{
                echo "error format invalide";
            }
            die();
        }
        $questions = $questionRepository->findAll();

        return $this->render('Defaut/create.html.twig', ['formListe' => $formListe->createView(), 'questions'=>$questions]);
    }

    /**
     * @Route("/liste/export/{id}", name="export")
     */
    public function exportListe(Liste $liste,ZipArchive $zip): Response
    {
        $text = "{ 'nom':'".$liste->getNom()."', 'questions':{";

        $zip->open($liste->getNom().".zip", ZipArchive::CREATE);
        foreach ($liste->getQuestions() as $question){
            $text.= "{'reponse':'".$question->getReponse()."', 'urlImage':'".$question->getUrlImage()."', 'urlAudio':'".$question->getUrlAudio()."'}";

            $zip->addFile($this->getParameter('uploads_dir') .$question->getUrlImage());
            $zip->addFile($this->getParameter('uploads_dir') .$question->getUrlImage());

        }
        $text.='}';

        /*header("Content-type: application/$extension");
        header("Content-Disposition: attachment; filename=" . $video->getTitre().".".$extension);
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($this->getParameter('uploads_dir') . $video->getFilename());*/

        die();
    }
    /**
     * @Route("/liste/import", name="import")
     */
    public function importListe(Request $request, ZipArchive $zipArchive,SessionInterface $session,EntityManagerInterface $manager): Response
    {
        $zipUrl = $request->files->get('video')['fichier'];
        $zip =$zipArchive->open($zipUrl);

        while ($zip_entry = zip_read($zip)) {
            switch (zip_entry_name($zip_entry)){
                case "export.txt":
                    if (zip_entry_open($zip, $zip_entry, "r")) {
                        $data = json_decode(zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                        $liste = new Liste();
                        $liste->setDateCreation(strtotime('now'))
                            ->setNom($data['nom'])
                            ->setVisibilite(0)
                            ->setCreateur($session->get('idProf'));
                            $manager->persist($liste);
                            $manager->flush();
                        foreach ($data['questions'] as $q){
                            $question = new Question();
                            $question->setReponse($q['reponse'])
                                ->setUrlAudio($q['urlAudio'])
                                ->setUrlImage($q['urlImage']);
                            $manager->persist($question);
                            $liste->addQuestion($question);
                        }
                        $manager->persist($liste);
                        $manager->flush();
                        zip_entry_close($zip_entry);
                    }
                    break;
                case "images":

                    break;
                case "audios":
                break;
            }

        }

        die();
    }

    /**
     * @Route("/question", name="Question")
     */
    public function question(): Response
    {
        return $this->render('Defaut/Question.html.twig', [
            'controller_name' => 'DefautController',
        ]);
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function admin(SessionInterface $session): Response
    {
        return $this->render('Defaut/Admin.html.twig', [
            'controller_name' => 'DefautController',
            'nom'=>$session->get('nom')." ".$session->get('prenom')
        ]);
    }





}
