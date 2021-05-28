<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProgramController extends AbstractController
{
     /**
     * @Route("/programs/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="program_list")
     */
    public function show($id = 1): Response
    {
        return $this->render('/program/show.html.twig', [
            'website'=> "Wild Séries",
            'id' => $id,
        ]);
    }
}
