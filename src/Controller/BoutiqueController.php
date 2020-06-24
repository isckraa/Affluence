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
     * @param Boutique $boutique
     * @Route("/boutique/delete/{id}", name="boutique_delete", methods={"DELETE"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Boutique $boutique) {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($boutique);
            $entityManager->flush();
            return $this->json([
                'status' => 201,
                'message' => "Delete store success"
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => "Delete store failed. Error : ".$e->getMessage()
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
     * @param Boutique $boutique
     * @Route("/boutique/list/{id}", name="boutique_list_id", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findById(Boutique $boutique) {
        return $this->json($boutique, 200, []);
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @Route("/boutique/list/nom", name="boutique_list_nom", methods={"GET"})
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
     * @param Request $request
     * @param ValidatorInterface $validator
     * @Route("/boutique/list/code_postal", name="boutique_list_code_postal", methods={"GET"})
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

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @Route("/boutique/list/ville", name="boutique_list_ville", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByVille(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $jsonRequest = $request->getContent();
        try {
            $boutique = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($boutique);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }
            return $this->json($boutiqueRepository->findBy(["ville" => $boutique->getVille()]), 200, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Boutique $boutique
     * @param EntityManagerInterface $em
     * @Route("/boutique/update/{id}", name="boutique_update", methods={"PUT", "PATCH"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Boutique $boutique, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em){
        $jsonRequest = $request->getContent();
        try {
            $newData = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($newData);
            if( count($errors) > 0) {
                return $this->json($errors, 400);
            }
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
        if($boutique){
            try {
                $boutique->setAdresse($newData->getAdresse());
                $boutique->setCodePostal($newData->getCodePostal());
                $boutique->setNom($newData->getNom());
                $boutique->setUser($newData->getUser());
                $boutique->setVille($newData->getVille());
                $boutique->setLongitude($newData->getLongitude());
                $boutique->setLatitude($newData->getLatitude());
                $em->persist($boutique);
                $em->flush();
                return $this->json([
                    'status' => 201,
                    'message' => 'Update store success'
                ], 201);
            } catch (\Exception $e){
                return $this->json([
                    'status' => 201,
                    'message' => 'Update store failed. Error : '.$e->getMessage()
                ], 201);
            }
        }
        return $this->json([
            'status' => 400,
            'message' => 'Bad id'
        ], 400);
    }
}
