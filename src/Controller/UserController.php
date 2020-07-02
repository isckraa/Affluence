<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @Route("/user/list", name="user_list", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();
        $userPseudo = [];
        $i = 0;
        foreach($users as $user) {
            $userPseudo[$i]["id"] = $user->getId();
            $userPseudo[$i]["pseudo"] = $user->getPseudo();
            $userPseudo[$i]["email"] = $user->getEmail();
            $userPseudo[$i]["points"] = $user->getPoints();
            $userPseudo[$i]["roles"] = $user->getRoles();
            $i++;
        }
        return $this->json($userPseudo, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @param User $user
     * @Route("/user/list/{id}", name="user_list_id", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findById(User $user) {
        return $this->json($user, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/user/list_pseudo", name="user_list_pseudo", methods={"GET"})
     */
    public function findByPseudo(UserRepository $userRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $jsonRequest = $request->getContent();
        try {
            $user = $serializer->deserialize($jsonRequest, User::class, 'json');
            return $this->json($userRepository->findBy(["pseudo" => $user->getPseudo()]), 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    /**
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/user/list_email", name="user_list_email", methods={"GET"})
     */
    public function findByEmail(UserRepository $userRepository, SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $jsonRequest = $request->getContent();
        try {
            $user = $serializer->deserialize($jsonRequest, User::class, 'json');
            return $this->json($userRepository->findBy(["email" => $user->getEmail()]), 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
    }

    /**
     * @param User $user
     * @Route("/user/points/{id}", name="user_list_id", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPoints(User $user) {
        $points = $user->getPoints();
        return $this->json($points, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/user/update/{id}", name="user_update", methods={"PUT", "PATCH"})
     */
    public function update(User $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){
        $jsonRequest = $request->getContent();
        try {
            $newData = $serializer->deserialize($jsonRequest, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
        }
        if($user){
            try {
                $user->setPseudo($newData->getPseudo() ?? $user->getPseudo());
                $user->setEmail($newData->getEmail() ?? $user->getEmail());
                $user->setPoints($newData->getPoints() ?? $user->getPoints());
                if (!empty($newData->getRoles())) {
                    $user->setRoles($newData->getRoles());
                }
                if ($newData->getPassword()) {
                    // encode the plain password
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $newData->getPassword()
                        )
                    );
                }
                $em->persist($user);
                $em->flush();
                return $this->json([
                    'status' => 201,
                    'message' => 'Update user success'
                ], 201, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            } catch (\Exception $e){
                return $this->json([
                    'status' => 304,
                    'message' => 'Update user failed. Error : '.$e->getMessage()
                ], 304, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
            }
        }
        return $this->json([
            'status' => 400,
            'message' => 'Bad id'
        ], 400, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }


    /**
     * Check if user is has ROLE_COMMERCANT and return list of his boutiques.
     * @Route("/api/commercant/verification", name="commercant_verification", methods={"POST"})
     * @IsGranted("ROLE_COMMERCANT")
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function isCommercant(SerializerInterface $serializer) {
        $user = $this->getUser();
        $boutique = $user->getBoutique();
        $response = json_decode($serializer->serialize($boutique, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            }
        ]),true);
        $response["user"] = $response["user"]["id"];
        if(isset($response["fileAttente"])) {
            $i = 0;
            foreach ($response["fileAttente"] as $filleAttente) {
                $response["fileAttente"][$i] = $response["fileAttente"][$i]["id"];
                $i++;
            }
        }
        return $this->json($response, 200, ["Access-Control-Allow-Origin" => "*", "Content-Type" => "application/json"]);
    }
}
