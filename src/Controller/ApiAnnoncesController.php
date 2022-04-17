<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Routing\Annotation\Route;

class ApiAnnoncesController extends AbstractController
{
    /**
     * @Route("/api/annonces", name="api_annonces", methods={"GET"})
     */
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        $annonce = $annoncesRepository->findAll();

        return $this->json($annonce, 200, ["Access-Control-Allow-Origin" => "*"]);
    }

    /**
     * @Route("/api/annonces/{id}", name="api_annonces_show", methods={"GET"})
     */
    public function show_id($id, AnnoncesRepository $annoncesRepository): Response
    {
        $annonce = $annoncesRepository->find($id);
        if ($annonce != null) return $this->json($annonce, 200, ["Access-Control-Allow-Origin" => "*"]);
        else return $this->json(["erreur" => "Maison introuvable"], 404);
    }

    /**
     * @Route("/api/annonces/", name="api_annonces_create", methods={"POST"})
     */
    public function crete(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $data = $request->getContent();

        $datajson = json_decode($data);


        if ((property_exists($datajson, "titre") && property_exists($datajson, "description"))) {

            $annonce = new Annonces();
            $annonce->setTitle($datajson->titre)
                ->setDescription($datajson->description);

            $em = $managerRegistry->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->json(["succes" => true, "data" => $annonce], 201);
        }
        dump($datajson);

        return $this->json(["succes" => false, "message" => "Donnée erronées"], 400);
    }

    /**
     * @Route("/api/annonces/{id}", name="api_annonces_delete", methods={"DELETE"})
     */
    public function delete($id, ManagerRegistry $managerRegistry, AnnoncesRepository $annoncesRepository): Response
    {
        $annonce = $annoncesRepository->find($id);

        if ($annonce != null){
            $em = $managerRegistry->getManager();

            $em->remove($annonce);
            $em->flush();

            return $this->json(null, 204);
        }
        else return $this->json(["erreur" => "Maison introuvable"], 404);
    }
}
