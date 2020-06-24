<?php

namespace App\Controller;

use App\Entity\FileAttente;
use App\Form\FileAttenteType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FileAttenteRepository;
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
                            ValidatorInterface $validator): JsonResponse
    {
        $jsonRequest = $request->getContent();
        try {
            $fileAttente = $serializer->deserialize($jsonRequest, FileAttente::class, 'json', [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['boutique'],
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            var_dump($fileAttente);

            
            $json = json_decode($jsonRequest,true);
            var_dump($json);
            
            $errors = $validator->validate($fileAttente);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $boutique = $boutiqueRepository->find(
                ['id'=> $json["boutique"] ]
            );
            //$fileAttente->setBoutique($boutique);

            $boutique->addFileAttente($fileAttente);

            //var_dump($boutique);
            $em->persist($fileAttente);
            $em->persist($boutique);
            $em->flush();
            return $this->json($fileAttente, '201');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @Route("/list/{id}", name="file_attente_show", methods={"GET"})
     */
    public function show(FileAttente $fileAttente): JsonResponse
    {
        return $this->json($fileAttente,200,[]);
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
                return $this->json($errors, 400);
            }
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
        if($fileAttente){
            try {
                if($newData->getDuree() !== NULL)
                    $fileAttente->setDuree($newData->getDuree());
                if($newData->getType() !== NULL)
                    $fileAttente->setType($newData->getType());
                if($newData->getInfoFileAttente() !== NULL)
                    $fileAttente->setInfoFileAttente($newData->getInfoFileAttente());
                if($newData->getBoutique() !== NULL)
                    $fileAttente->setBoutique($newData->getBoutique());
                $em->persist($fileAttente);
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
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => "Delete waiting queue failed. Error : ".$e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/actualize", name="actualize", methods={"POST"})
     */
    public function actualize(Request $request): JsonResponse
    {

    }
}
