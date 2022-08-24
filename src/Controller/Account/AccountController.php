<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/account")
*/
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account")
     */
    public function index(OrderRepository $repoOrder): Response
    {
        $orders = $repoOrder->findBy(['state' => 1, 'user'=>$this->getUser()],['id'=>'DESC']);
        return $this->render('account/index.html.twig',[
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/{id}", name="account_order_details")
     */
    public function show(?Order $order): Response
    {
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute("home");
        }

        if(!$order->getState(1)){
            return $this->redirectToRoute("account");
        }
        return $this->render('account/detail_order.html.twig',[
            'order' => $order,
        ]);
    }
}
