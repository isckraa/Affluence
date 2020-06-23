<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BoutiqueController extends AbstractController
{
    /**
     * @Route("/boutique", name="boutique")
     */
    public function index()
    {
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
        ]);
    }

    /**
     * @Route("/boutique/create", name="boutique_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator) {
        $jsonRequest = $request->getContent();
        try {
            $boutique = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($boutique);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($boutique);
            $em->flush();
            return $this->json($boutique, '201');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @Route("/boutique/list", name="boutique_list", methods={"GET"})
     */
    public function list(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer)
    {
        return $this->json($boutiqueRepository->findAll(), 200, []);
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @Route("/boutique/list/id", name="boutique_list_id", methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findById(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $requestContent = $request->getContent();
        /* try {
            // $boutique = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            var_dump($boutique);
            $errors = $validator->validate($boutique);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }
            return $this->json($boutiqueRepository->findOneBy(["id" => $boutique->getId()]), 200, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        } */
        $id = (int)$requestContent;
        if ($id > 0) {
            return $this->json($boutiqueRepository->findOneBy(["id" => $id]), 200, []);
        }
        return $this->json([
            'status' => 400,
            'message' => "Not good integer"
        ], 400);
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @Route("/boutique/list/nom", name="boutique_list_nom", methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByNom(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $jsonRequest = $request->getContent();
        try {
            $boutique = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($boutique);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }
            return $this->json($boutiqueRepository->findBy(["nom" => $boutique->getNom()]), 200, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @Route("/boutique/list/code_postal", name="boutique_list_code_postal", methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByCodePostal(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $jsonRequest = $request->getContent();
        try {
            $boutique = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($boutique);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }
            return $this->json($boutiqueRepository->findBy(["codePostal" => $boutique->getCodePostal()]), 200, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
