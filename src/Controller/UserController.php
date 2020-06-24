<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
        return $this->json($userPseudo, 200, []);
    }

    /**
     * @param User $user
     * @Route("/user/list/{id}", name="user_list_id", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findById(User $user) {
        return $this->json($user, 200, []);
    }
}
