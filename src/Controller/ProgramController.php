<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/programs", name = "program_")
     */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name = "index")
     * @return Response
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findAll();
        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs],
        );
    }

    /**
     * @Route("/new", name = "new")
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())->from()
            ->from($this->getParameter('mailer_from'))
            ->to('your_email@example.com')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);
            
            return $this->redirectToRoute("program_index");
        }
        return $this->render("/program/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }

     /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
        $seasons = $program->getSeason();
        if(!$program){
            throw $this->createNotFoundException(
                "No program with id : " . $program->getId() . " found in program's table" 
            );
        }
        return $this->render('/program/show.html.twig', [
            'website'=> "Wild Séries",
            'id' => $program->getId(),
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }
    /**
     * @Route("/{slug}/seasons/{season}", name ="season_show")
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id'=> $program]);
        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id'=> $season]);
        $episodes = $season->getEpisodes();
        return $this->render('/program/season_show.html.twig', [
               'website'=> "Wild Séries",
               'program' => $program,
               'season' => $season,
               'episodes' => $episodes,
        ]);
    }
    /**
     * @Route("/{program_slug}/seasons/{season}/episodes/{episode_slug}", name = "episode_show" )
     * @ParamConverter("program", class = "App\Entity\Program", options={"mapping": {"program_slug": "slug"}})
     * @ParamConverter("episode", class = "App\Entity\Episode", options={"mapping": {"episode_slug": "slug"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('/program/episode_show.html.twig', [
            'website'=> "Wild Séries",
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
