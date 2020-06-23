<?php

namespace App\Controller;

use App\Entity\Item;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item")
     */
    public function index()
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    /**
     * @Route("/item/{id}", name="item_show")
     */
    public function showAction(SerializerInterface $serializer)
    {
        $item = new Item();
        $item
            ->setNom('Mon premier article')
            ->setDetails('Le detail de mon article.')
        ;
        $json = $serializer->serialize($item, 'json');

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
