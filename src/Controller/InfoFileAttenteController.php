<?php

namespace App\Controller;

use App\Entity\InfoFileAttente;
use App\Repository\FileAttenteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InfoFileAttenteController extends AbstractController
{
    /**
     * @Route("/info/file/attente", name="info_file_attente")
     */
    public function index()
    {
        return $this->render('info_file_attente/index.html.twig', [
            'controller_name' => 'InfoFileAttenteController',
        ]);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @param FileAttenteRepository $fileAttenteRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/info/create", name="info_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserRepository $userRepository, FileAttenteRepository $fileAttenteRepository) {
        $jsonRequest = $request->getContent();
        try {
            $dataDecode = $serializer->decode($jsonRequest, 'json');
            $user = null;
            if (isset($dataDecode['userId'])) {
                $user = $userRepository->findOneBy(["id" => $dataDecode['userId']]);
            }
            $fileAttente = null;
            if (isset($dataDecode['fileAttenteId'])) {
                $fileAttente = $fileAttenteRepository->findOneBy(["id" => $dataDecode['fileAttenteId']]);
            }
            $infoFileAttente = $serializer->deserialize($jsonRequest, infoFileAttente::class, 'json');
            $errors = $validator->validate($infoFileAttente);
            if(count($errors) > 0) {
                return $this->json($errors, 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            }
            $infoFileAttente->setUser($user);
            $infoFileAttente->setFileAttente($fileAttente);
            $em->persist($infoFileAttente);
            $em->flush();
            $response = json_decode($serializer->serialize($infoFileAttente, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                }
            ]),true);
            $response["user"] = $response["user"]["id"];
            return $this->json($response,201, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    /**
     * @param InfoFileAttente $infoFileAttente
     * @Route("/info/delete/{id}", name="info_delete", methods={"DELETE"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(InfoFileAttente $infoFileAttente) {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($infoFileAttente);
            $entityManager->flush();
            return $this->json([
                'status' => 201,
                'message' => "Delete info success"
            ], 201, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => "Delete info failed. Error : ".$e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    /**
     * @param InfoFileAttente $infoFileAttente
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/info/list/{id}", name="info_list_id", methods={"GET"})
     */
    public function findById(InfoFileAttente $infoFileAttente, SerializerInterface $serializer) {
        $response = json_decode($serializer->serialize($infoFileAttente, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            }
        ]),true);
        $response["user"] = $response["user"]["id"];
        $response["fileAttente"] = $response["fileAttente"]["id"];
        return $this->json($response,201, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }
}
