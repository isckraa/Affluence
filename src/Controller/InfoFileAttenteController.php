<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use App\Entity\InfoFileAttente;
use App\Entity\User;
use App\Repository\FileAttenteRepository;
use App\Repository\InfoFileAttenteRepository;
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

            $infoFileAttente->setDayDate(new \DateTime(date("Y-m-d")));
            
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
                'status' => 304,
                'message' => "Delete info failed. Error : ".$e->getMessage()
            ], 304, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
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
        $response["heureEntree"] = date_format($infoFileAttente->getHeureEntree(),'H:i:s');
        $response["heureSortie"] = date_format($infoFileAttente->getHeureSortie(),'H:i:s');
        return $this->json($response,200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @param Boutique $boutique
     * @param SerializerInterface $serializer
     * @Route("/info/boutique/{id}", name="info_boutique", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByBoutique(Boutique $boutique, SerializerInterface $serializer) {
        $filesAttente = $boutique->getFileAttente();
        $infosFileAttente = [];
        $i = 0;
        foreach($filesAttente as $fileAttente) {
            $infosFileAttente[$i] = json_decode($serializer->serialize($fileAttente, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                }
            ]),true);
            // $infosFileAttente[$i]["user"] = $infosFileAttente[$i]["user"]["id"];
            $infosFileAttente[$i]["boutique"] = $infosFileAttente[$i]["boutique"]["id"];
            // Return only hour and minutes.
            $infosFileAttente[$i]["duree"] = substr($infosFileAttente[$i]["duree"], 11, 5);
            $j =0;
            foreach($infosFileAttente[$i]["infoFileAttentes"] as $infoFileAttente) {
                $infoFileAttente["user"] = $infoFileAttente["user"]["id"];
                $infoFileAttente["heureEntree"] = substr($infoFileAttente["heureEntree"],11, 5);
                $infoFileAttente["heureSortie"] = substr($infoFileAttente["heureSortie"],11, 5);
                $infosFileAttente[$i]["infoFileAttentes"][$j] = $infoFileAttente;
                $j++;
            }
            $i++;
        }
        return $this->json($infosFileAttente, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @param User $user
     * @param SerializerInterface $serializer
     * @Route("/info/user/{id}", name="info_user", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findByUser(User $user, SerializerInterface $serializer) {
        $infosFileAttente = [];
        $i = 0;
        foreach($user->getInfoFileAttentes()->toArray() as $fileAttente) {
            $infosFileAttente[$i] = json_decode($serializer->serialize($fileAttente, 'json', [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                }
            ]),true);
            if(isset($infoFileAttente[$i]["user"])) {
                $infosFileAttente[$i]["user"] = $infosFileAttente[$i]["user"]["id"];
            }
            // Return only hour and minutes.
            $infosFileAttente[$i]["heureEntree"] = substr($infosFileAttente[$i]["heureEntree"], 11, 5);
            $infosFileAttente[$i]["heureSortie"] = substr($infosFileAttente[$i]["heureSortie"], 11, 5);
            $infosFileAttente[$i]["user"] = $infosFileAttente[$i]["user"]["id"];
            $infosFileAttente[$i]["fileAttente"] = $infosFileAttente[$i]["fileAttente"]["id"];
            $i++;
        }
        return $this->json($infosFileAttente, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @param InfoFileAttente $infoFileAttente
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param FileAttenteRepository $fileAttenteRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/info/update/{id}", name="info_update", methods={"PUT", "PATCH", "POST"})
     */
    public function update(InfoFileAttente $infoFileAttente, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UserRepository $userRepository, FileAttenteRepository $fileAttenteRepository){
        $jsonRequest = $request->getContent();
        try {
            $dataDecode = $serializer->decode($jsonRequest, 'json');
            $newData = $serializer->deserialize($jsonRequest, InfoFileAttente::class, 'json');
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
        if($infoFileAttente){
            try {
                $infoFileAttente->setAffluence($newData->getAffluence() ?? $infoFileAttente->getAffluence());
                $infoFileAttente->setHeureEntree($newData->getHeureEntree() ?? $infoFileAttente->getHeureEntree());
                $infoFileAttente->setHeureSortie($newData->getHeureSortie() ?? $infoFileAttente->getHeureSortie());
                $infoFileAttente->setType($newData->getType() ?? $infoFileAttente->getType());
                if (isset($dataDecode['userId'])) {
                    $user = $userRepository->findOneBy(["id" => $dataDecode['userId']]);
                    $infoFileAttente->setUser($user);
                }
                if (isset($dataDecode['fileAttenteId'])) {
                    $fileAttente = $fileAttenteRepository->findOneBy(["id" => $dataDecode['userId']]);
                    $infoFileAttente->setFileAttente($fileAttente);
                }
                $em->persist($infoFileAttente);
                $em->flush();
                return $this->json([
                    'status' => 201,
                    'message' => 'Update info success.'
                ], 201, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            } catch (\Exception $e){
                return $this->json([
                    'status' => 304,
                    'message' => 'Update info failed. Error : '.$e->getMessage()
                ], 304, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            }
        }
        return $this->json([
            'status' => 400,
            'message' => 'Bad id'
        ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/info/pushGeo", name="ushGeo_user", methods={"POST"})
     */
    public function pushGeo(UserRepository $userRepository, BoutiqueRepository $boutiqueRepository,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, InfoFileAttenteRepository $infoFARepository, FileAttenteRepository $fileAttenteRepository){
        $jsonRequest = $request->getContent();
        try {
            $dataDecode = $serializer->decode($jsonRequest, 'json');
            
            $latitudeUser = $dataDecode["latitude"];
            $longitudeUser = $dataDecode["longitude"];

            $boutiques = $boutiqueRepository->findByGPS($longitudeUser, $latitudeUser, 10);
            

            $newdate = new \DateTime();
            $newstartdate = new \DateTime(date("H:i:s",strtotime("- 3 minutes")));
            $user = $userRepository->findOneBy([
                'id' => $dataDecode["userId"]
            ]);

            foreach($boutiques as $boutique){
                foreach($boutique->getFileAttente() as $file){
                    $infos = $infoFARepository->findByUser($dataDecode["userId"],$file->getId());
                    if(count($infos) > 0){
                        var_dump("info file true");
                        foreach($infos as $infofileAttente){
                            $infofileAttente->setHeureSortie($newdate);
                            $em->persist($infofileAttente);
                        }
                    }
                    else{
                        var_dump("info file false");

                        $newInfoFileAttente = new InfoFileAttente();

                        $newInfoFileAttente->setUser($user);
                        $newInfoFileAttente->setFileAttente($file);
                        $newInfoFileAttente->setLatitude($latitudeUser);
                        $newInfoFileAttente->setLongitude($longitudeUser);
                        $newInfoFileAttente->setHeureEntree($newstartdate);
                        $newInfoFileAttente->setHeureSortie($newdate);
                        $newInfoFileAttente->setAffluence(1);
                        $newInfoFileAttente->setDayDate($newdate);

                        $em->persist($newInfoFileAttente);
                    }
                    
                }
            }

            $em->flush();

            foreach($boutiques as $boutique){
                foreach($boutique->getFileAttente() as $file){
                    $this->actualize($infoFARepository,$file,$em);
                }
            }

            return $this->json([
                'status' => 201,
                'message' => 'Update waiting queue info success'
            ], 201, ["Access-Control-Allow-Origin" => "*"]);
        }catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    public function actualize( $infoFARepository, $fileAttente,  $em)
    {
        $listInfos = $infoFARepository->findByQueueDate($fileAttente->getId());
        
        $avgWait = 0;
        $count = 0;
        foreach($listInfos as &$infos){
            $diff = date_diff($infos->getHeureSortie(),$infos->getHeureEntree());

            $count ++; 
            $avgWait += $diff->h*60 + $diff->i;
        }

        if($count>0){
            $avgWait/=$count;
            var_dump($avgWait);    
        }

        $hour = str_pad((string) floor($avgWait/60),2,"0",STR_PAD_LEFT);
        $minutes = str_pad((string) $avgWait%60,2,"0",STR_PAD_LEFT);
        $duration = new \DateTime($hour.":".$minutes.":00");
        $fileAttente->setDuree($duration);

        $em->persist($fileAttente);
        $em->flush();
    }
}
