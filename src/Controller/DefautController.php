<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Question;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use App\Repository\ProfesseurRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function createList(Liste $liste=null,SessionInterface $session, EntityManagerInterface $manager, Request $request, QuestionRepository $questionRepository, ProfesseurRepository $professeurRepository): Response
    {
        if ($session->get('idProf') == null) {
            return $this->redirectToRoute('login');
        }
        if (!$liste) {
            $liste = new Liste();
        }
        $formListe = $this->createForm(ListeType::class, $liste);
        $formListe->handleRequest($request);
        if ($formListe->isSubmitted()) {
            if ($formListe->isValid()) {
                if (!$liste->getId()) {
                    $liste->setDateCreation(strtotime('now'))
                    ->setCreateur($professeurRepository->find($session->get('idProf')))
                    ->setVisibilite(0);
                }

                $manager->persist($liste);
                $manager->flush();
                $nb = $request->request->get('nbQuestions');


                for ($i = 0; $i < $nb; $i++) {
                    $questionform = $request->request->get("question$i");
                    $questionfiles = $request->files->get("question$i");
                    $question = new Question();
                    $question->setReponse($questionform["reponse"]);
                    $manager->persist($question);
                    $manager->flush();
                    $id = strtotime('now');
                    if (isset($questionfiles["image"])) {
                        $image = $questionfiles['image'];
                        $nom = $id."." . $image->guessExtension();
                        try {
                            $image->move(
                               'uploads/images/',
                                $nom
                            );
                            $question->setUrlImage("uploads/images/$nom");
                        } catch (FileException $e) {
                            return 'error image';
                        }
                    }
                    if (isset($questionfiles["audio"])) {
                        $audio = $questionfiles['audio'];
                        $nom =  $id."."  . $audio->guessExtension();
                        try {
                            $audio->move(
                                'uploads/audios/',
                                $nom
                            );
                            $question->setUrlAudio("uploads/audios/$nom");
                        } catch (FileException $e) {
                            return 'error image';
                        }
                    }
                    $manager->persist($question);
                    $liste->addQuestion($question);
                    $manager->persist($liste);
                    $manager->flush();
                }
                $q = $request->request->get('q');
                foreach ($q as $question){
                    $liste->addQuestion($questionRepository->find($question));
                    $manager->persist($liste);
                    $manager->flush();
                }

            }
        }
        $questions = $questionRepository->findAll();


        return $this->render('Defaut/create.html.twig', ['formListe' => $formListe->createView(), 'questions'=>$questions, 'questionsPrise'=>$liste->getQuestions()]);
    }

    /**
     * @Route("/export/liste/{id}", name="export")
     */
    public function exportListe(Liste $liste): Response
    {
        $text = '{ "nom":"'.$liste->getNom().'", "questions":[';
        $zipArchive = new ZipArchive();
        $time=strtotime('now');
        $zipArchive->open($liste->getNom()."$time.zip", ZipArchive::CREATE);

        foreach ($liste->getQuestions() as $key=>$question){
            $text.= '{"reponse":"'.$question->getReponse().'", "urlImage":"'.$question->getUrlImage().'", "urlAudio":"'.$question->getUrlAudio().'"}';
            if($key<count($liste->getQuestions())-1)
                $text.=',';
            $name = explode('/', $question->getUrlImage());
            $zipArchive->addFile($question->getUrlImage(), 'images/'.$name[count($name)-1]);
            $name = explode('/', $question->getUrlAudio());
            $zipArchive->addFile($question->getUrlAudio(), 'audios/'.$name[count($name)-1]);

        }
        $text.=']}';
        $zipArchive->addFromString('export.json', $text);
        $zipArchive->close();

        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=export_".$liste->getNom().".zip");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($liste->getNom()."$time.zip");
        unlink($liste->getNom()."$time.zip");

        die();
    }
    /**
     * @Route("import/liste", name="import")
     */
    public function importListe(Request $request, SessionInterface $session,EntityManagerInterface $manager, ProfesseurRepository $professeurRepository): Response
    {
        $zipUrl = $request->files->get('file');

        if($zipUrl) {
            $zip = new ZipArchive;
            $zip->open($zipUrl);
            $zip->extractTo('uploads/');

            $res =file_get_contents('uploads/export.json', true);
            $data = json_decode($res);
            echo '<pre>';
            print_r($data);
            echo '</pre>';


            $liste = new Liste();
            $liste->setDateCreation(strtotime('now'))
                ->setNom($data->nom)
                ->setVisibilite(0)
                ->setCreateur($professeurRepository->find($session->get('idProf')));
                $manager->persist($liste);
                $manager->flush();
            foreach ($data->questions as $q){
                $question = new Question();
                $question->setReponse($q->reponse)
                    ->setUrlAudio($q->urlAudio)
                    ->setUrlImage($q->urlImage);
                $manager->persist($question);
                $liste->addQuestion($question);
            }
            $manager->persist($liste);
            $manager->flush();
            unlink('uploads/export.txt');
            $zip->close();

            echo "ok";
            die();
        }
        return $this->render('Defaut/import.html.twig', [
            'controller_name' => 'DefautController',
        ]);
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

    /**
     * @Route("/DisplayListe", name="DisplayListe")
     */
    public function DisplayListe(SessionInterface $session, ListeRepository $listeRepository, ProfesseurRepository  $professeurRepository): Response
    {
        $prof = $professeurRepository->find($session->get('idProf'));
        return $this->render('Defaut/DisplayListe.html.twig', [
            'controller_name' => 'DefautController',
            'nom'=>$session->get('nom')." ".$session->get('prenom'),
            'listes'=>$listeRepository->findBy(['createur'=>$prof])
        ]);
    }


    /**
     * @Route("/Utilisateur", name="Utilisateur")
     */
    public function Utilisateur(SessionInterface $session): Response
    {
        return $this->render('Defaut/Utilisateur.html.twig', [
            'controller_name' => 'DefautController',
            'nom'=>$session->get('nom')." ".$session->get('prenom'),
        ]);
    }

    /**
     * @Route("/Mots", name="Mots")
     */
    public function Mots(SessionInterface $session): Response
    {
        return $this->render('Defaut/Mots.html.twig', [
            'controller_name' => 'DefautController',
            'nom'=>$session->get('nom')." ".$session->get('prenom'),
        ]);
    }


}
