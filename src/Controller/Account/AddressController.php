<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\AddressType;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    private $session;
    
   
    
    public function __construct( SessionInterface $session )
    {
        $this->session = $session;
        
        
    }

    /**
     * @Route("/new", name="address_new", methods={"GET","POST"})
     */
    public function new(Request $request, CartService $cartService, EntityManagerInterface $entityManager): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $address->setUser($user);

            $entityManager->persist($address);
            $entityManager->flush();

            if($cartService->getFullCart()){
                return $this->redirectToRoute('account');
            }
            
            $this->addFlash('address_message', 'Your address has been saved');
            return $this->redirectToRoute('account');
        }

        return $this->render('address/new.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Address $address,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if($this->session->get('checkout_data')){
                $data = $this->session->get('checkout_data');
                $data['address'] = $address;
                $this->session->set('checkout_data', $data);
                return $this->redirectToRoute("checkout_confirm");
            }
            $this->addFlash('address_message', 'Your address has been edited');
            return $this->redirectToRoute('account');
        }

        return $this->render('address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="address_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Address $address,EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            
            $entityManager->remove($address);
            $entityManager->flush();
            $this->addFlash('address_message', 'Your address has been deleted');
        }

        return $this->redirectToRoute('account');
    }
}
