<?php

namespace App\Controller;

use App\Entity\FileAttente;
use App\Form\FileAttenteType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FileAttenteRepository;
use App\Entity\InfoFileAttente;
use App\Repository\InfoFileAttenteRepository;
use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/file/attente")
 */
class FileAttenteController extends AbstractController
{
    /**
     * @Route("/", name="file_attente_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('file_attente/index.html.twig', [
            'controller_name' => 'FileAttenteController',
        ]);
    }

    /**
     * @Route("/create", name="file_attente_create", methods={"POST"})
     */
    public function create(Request $request, BoutiqueRepository $boutiqueRepository, 
                            SerializerInterface $serializer, EntityManagerInterface $em, 
                            ValidatorInterface $validator): Response
    {
        $jsonRequest = $request->getContent();
        try {
            $fileAttente = $serializer->deserialize($jsonRequest, FileAttente::class, 'json', [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['boutique'],
            ]);
            
            $json = $serializer->decode($jsonRequest,'json');
            
            $errors = $validator->validate($fileAttente);
            if(count($errors) > 0) {
                return $this->json($errors, 400, ["Access-Control-Allow-Origin" => "*"]);
            }

            $boutique = $boutiqueRepository->findOneBy(
                ['id'=> $json["boutiqueId"] ]
            );
            $fileAttente->setBoutique($boutique);
            $em->persist($fileAttente);
            $em->flush();

            $response = json_decode($serializer->serialize($fileAttente, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                }
            ]),true);
            $response["boutique"] = $response["boutique"]["id"];
            return $this->json($response,201, ["Access-Control-Allow-Origin" => "*"]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400, ["Access-Control-Allow-Origin" => "*"]);
        }
    }

    /**
     * @Route("/list/{id}", name="file_attente_show", methods={"GET"})
     */
    public function show(SerializerInterface $serializer,FileAttente $fileAttente): JsonResponse
    {

        $response = json_decode($serializer->serialize($fileAttente, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                }
            ]),true);
        $response["boutique"] = $response["boutique"]["id"];
        return $this->json($response,200, ["Access-Control-Allow-Origin" => "*"]);
    }

    /**
     * @Route("/update/{id}", name="file_attente_edit", methods={"POST","PUT"})
     */
    public function update(FileAttente $fileAttente, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $jsonRequest = $request->getContent();
        try {
            $newData = $serializer->deserialize($jsonRequest, FileAttente::class, 'json');
            $errors = $validator->validate($newData);
            if( count($errors) > 0) {
                return $this->json($errors, 400, ["Access-Control-Allow-Origin" => "*"]);
            }
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*"]);
        }
        if($fileAttente){
            try {
                if($newData->getDuree() !== NULL)
                    $fileAttente->setDuree($newData->getDuree());
                if($newData->getType() !== NULL)
                    $fileAttente->setType($newData->getType());
                if($newData->getBoutique() !== NULL)
                    $fileAttente->setBoutique($newData->getBoutique());
                $em->persist($fileAttente);
                $em->flush();
                return $this->json([
                    'status' => 201,
                    'message' => 'Update await list success'
                ], 201, ["Access-Control-Allow-Origin" => "*"]);
            } catch (\Exception $e){
                return $this->json([
                    'status' => 304,
                    'message' => 'Update await list failed. Error : '.$e->getMessage()
                ], 304, ["Access-Control-Allow-Origin" => "*"]);
            }
        }
        return $this->json([
            'status' => 400,
            'message' => 'Bad id'
        ], 400, ["Access-Control-Allow-Origin" => "*"]);
    }

    /**
     * @Route("/delete/{id}", name="file_attente_delete", methods={"DELETE"})
     */
    public function delete(FileAttente $fileAttente): JsonResponse
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fileAttente);
            $entityManager->flush();
            return $this->json([
                'status' => 201,
                'message' => "Delete waiting queue success"
            ], 201, ["Access-Control-Allow-Origin" => "*"]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 304,
                'message' => "Delete waiting queue failed. Error : ".$e->getMessage()
            ], 304, ["Access-Control-Allow-Origin" => "*"]);
        }
    }

    /**
     * @Route("/actualize/{id}", name="actualize", methods={"GET"})
     */
    public function actualize(Request $request, InfoFileAttenteRepository $infoFARepository, FileAttente $fileAttente): JsonResponse
    {
        $listInfos = $infoFARepository->findByQueueDate($fileAttente->getId());
        
        var_dump($listInfos[0]->getHeureEntree());

        foreach($listInfos as &$infos){

        }

        return $this->json([
            'status' => 201,
            'message' => "Actualize success"
        ], 201, ["Access-Control-Allow-Origin" => "*"]);
    }
}
