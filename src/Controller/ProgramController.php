<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;

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
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
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
     * @Route("/{program}/seasons/{season}", name ="season_show")
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
     * @Route("/{program}/seasons/{season}/episodes/{episode}", name = "episode_show" )
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
