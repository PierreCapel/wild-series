<?php
// src/Controller/ProgramController.php
namespace App\Controller;

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
    public function show($id): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id'=> $id]);

        $seasons = $program->getSeason();
        if(!$program){
            throw $this->createNotFoundException(
                "No program with id : " . $id . " found in program's table" 
            );
        }
        return $this->render('/program/show.html.twig', [
            'website'=> "Wild Séries",
            'id' => $id,
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }
    /**
     * @Route("/{programId}/seasons/{seasonId}", name ="season_show")
     * @return Response
     */
    public function showSeason($programId, $seasonId): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id'=> $programId]);
        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id'=> $seasonId]);
        $episodes = $season->getEpisodes();
        return $this->render('/program/season_show.html.twig', [
               'website'=> "Wild Séries",
               'program' => $program,
               'season' => $season,
               'episodes' => $episodes,
        ]);
    }
}
