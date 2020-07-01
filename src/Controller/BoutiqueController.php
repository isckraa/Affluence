<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use App\Repository\FileAttenteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserRepository $userRepository) {
        $jsonRequest = $request->getContent();
        try {
            $dataDecode = $serializer->decode($jsonRequest, 'json');
            $user = null;
            if (isset($dataDecode['userId'])) {
                $user = $userRepository->findOneBy(["id" => $dataDecode['userId']]);
            }
            $boutique = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($boutique);
            if(count($errors) > 0) {
                return $this->json($errors, 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            }
            $boutique->setUser($user);
            $em->persist($boutique);
            $em->flush();
            $response = json_decode($serializer->serialize($boutique, 'json', [
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
            ], 201, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 304,
                'message' => "Delete store failed. Error : ".$e->getMessage()
            ], 304, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    /**
     * This function format data to return a list of boutique data.
     * @param SerializerInterface $serializer
     * @param array $boutiques
     * @return array
     */
    public function boutiqueListSerializer(SerializerInterface $serializer, array $boutiques) {
        $boutiqueSerialize = [];
        $i = 0;
        foreach($boutiques as $boutique) {
            $boutiqueSerialize[$i] = json_decode($serializer->serialize($boutique, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                }
            ]),true);
            if(isset($boutiqueSerialize[$i]["user"])) {
                $boutiqueSerialize[$i]["user"] = $boutiqueSerialize[$i]["user"]["id"];
            }
            if(isset($boutiqueSerialize[$i]["fileAttente"])) {
                $fileAttenteData = $boutiqueSerialize[$i]["fileAttente"];
                $j = 0;
                foreach ($fileAttenteData as $fileAttente) {
                    $fileAttente = $fileAttente["id"];
                    $fileAttenteData[$j] = $fileAttente;
                    $j++;
                }
                $boutiqueSerialize[$i]["fileAttente"] = $fileAttenteData;
            }
            $i++;
        }
        return $boutiqueSerialize;
    }

    /**
     * @Route("/boutique/list", name="boutique_list", methods={"GET"})
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer)
    {
        $boutiques = $boutiqueRepository->findAll();
        $boutiqueSerialize = $this->boutiqueListSerializer($serializer, $boutiques);
        return $this->json($boutiqueSerialize, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @param Boutique $boutique
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/boutique/list/{id}", name="boutique_list_id", methods={"GET"})
     */
    public function findById(Boutique $boutique, SerializerInterface $serializer) {
        $response = json_decode($serializer->serialize($boutique, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            }
        ]),true);
        if(isset($response["user"])) {
            $response["user"] = $response["user"]["id"];
        }
        if(isset($response["fileAttente"])) {
            $fileAttenteData = $response["fileAttente"];
            $j = 0;
            foreach ($fileAttenteData as $fileAttente) {
                $fileAttente = $fileAttente["id"];
                $fileAttenteData[$j] = $fileAttente;
                $j++;
            }
            $response["fileAttente"] = $fileAttenteData;
        }
        return $this->json($response,200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/boutique/list_nom", name="boutique_list_nom", methods={"GET"})
     */
    public function findByNom(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $nom = $request->get('nom');
        if ($nom) {
            $boutiques = $boutiqueRepository->findByApproximatifNom($nom);
            $boutiqueSerialize = $this->boutiqueListSerializer($serializer, $boutiques);
            return $this->json($boutiqueSerialize, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
        else {
            return $this->json(
                ["status" => 400, "message"=>"Parameter nom is missing."],
                400,
                ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @Route("/boutique/list_code_postal", name="boutique_list_code_postal", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByCodePostal(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $codePostal = $request->get('codePostal');
        if ($codePostal) {
            $boutiques = $boutiqueRepository->findByApproximatifCodePostal($codePostal);
            $boutiqueSerialize = $this->boutiqueListSerializer($serializer, $boutiques);
            return $this->json($boutiqueSerialize, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
        else {
            return $this->json(
                ["status" => 400, "message"=>"Parameter codePostal is missing."],
                400,
                ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @Route("/boutique/list_ville", name="boutique_list_ville", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByVille(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $ville = $request->get('ville');
        if ($ville) {
            $boutiques = $boutiqueRepository->findByApproximatifVille($ville);
            $boutiqueSerialize = $this->boutiqueListSerializer($serializer, $boutiques);
            return $this->json($boutiqueSerialize, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
        else {
            return $this->json(
                ["status" => 400, "message"=>"Parameter ville is missing."],
                400,
                ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     * @param BoutiqueRepository $boutiqueRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @Route("/boutique/list_gps", name="boutique_list_gps", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByGPS(BoutiqueRepository $boutiqueRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        if($longitude && $latitude){
            $boutiques = $boutiqueRepository->findByGPS($longitude, $latitude, 1);
            $boutiqueSerialize = $this->boutiqueListSerializer($serializer, $boutiques);
            return $this->json($boutiqueSerialize, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
        else {
            return $this->json(
                ["status" => 400, "message"=>"Parameter longitude or latitude is missing."],
                400,
                ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     * @param Boutique $boutique
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/boutique/update/{id}", name="boutique_update", methods={"PUT", "PATCH", "POST"})
     */
    public function update(Boutique $boutique, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UserRepository $userRepository, FileAttenteRepository $fileAttenteRepository){
        $jsonRequest = $request->getContent();
        try {
            $dataDecode = $serializer->decode($jsonRequest, 'json');
            $newData = $serializer->deserialize($jsonRequest, Boutique::class, 'json');
            $errors = $validator->validate($newData);
            if( count($errors) > 0) {
                return $this->json($errors, 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            }
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
        if($boutique){
            try {
                $boutique->setAdresse($newData->getAdresse() ?? $boutique->getAdresse());
                $boutique->setCodePostal($newData->getCodePostal() ?? $boutique->getCodePostal());
                $boutique->setNom($newData->getNom() ?? $boutique->getNom());
                $boutique->setUser($newData->getUser() ?? $boutique->getUser());
                $boutique->setVille($newData->getVille() ?? $boutique->getVille());
                $boutique->setLongitude($newData->getLongitude() ?? $boutique->getLongitude());
                $boutique->setLatitude($newData->getLatitude() ?? $boutique->getLatitude());
                $boutique->setMaxClient($newData->getMaxClient() ?? $boutique->getMaxClient());
                $boutique->setMaskRequired($newData->getMaskRequired() !== null ? $newData->getMaskRequired() : $boutique->getMaskRequired());
                $boutique->setGel($newData->getGel() !== null ? $newData->getGel() : $boutique->getGel());
                if (isset($dataDecode['userId'])) {
                    $user = $userRepository->findOneBy(["id" => $dataDecode['userId']]);
                    $boutique->setUser($user);
                }
                if (isset($dataDecode['addFileAttenteId'])) {
                    $fileAttente = $fileAttenteRepository->findOneBy(["id" => $dataDecode['addFileAttenteId']]);
                    $boutique = $boutique->addFileAttente($fileAttente);
                }
                if (isset($dataDecode['removeFileAttenteId'])) {
                    $fileAttente = $fileAttenteRepository->findOneBy(["id" => $dataDecode['removeFileAttenteId']]);
                    // Verification if the fileAttente is in the boutique data.
                    if (in_array($fileAttente, $boutique->getFileAttente()->toArray())) {
                        // $boutique = $boutique->removeFileAttente($fileAttente);
                        $em->remove($fileAttente);
                    }
                }
                $em->persist($boutique);
                $em->flush();
                return $this->json([
                    'status' => 201,
                    'message' => 'Update store success.'
                ], 201, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            } catch (\Exception $e){
                return $this->json([
                    'status' => 304,
                    'message' => 'Update store failed. Error : '.$e->getMessage()
                ], 304, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            }
        }
        return $this->json([
            'status' => 400,
            'message' => 'Bad id'
        ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }
}
