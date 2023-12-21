<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Service\CartService;
use Stripe\Checkout\Session;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generator;


    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->generator = $generator;


    }

    #[Route('/order/create-session-stripe/{reference}', name: 'payment_stripe')]
    public function stripeCheckout($reference): RedirectResponse
    {

        $produitStripe = []; 
        $total1=0; 
        $commande = $this->em->getRepository(Commande::class)->findOneBy(['reference' => $reference]);

        if(!$commande){
            return $this->redirectToRoute('cart_index');
        }
        
        // dd($commande->getPanier());
        foreach($commande->getPanier() as $panier){
            
            //$produitData = $this->em->getRepository(Produit::class)->findOneBy(['sousrubriqueart' => $produit->getProduit()]);
            $produit = $panier->getPanierProd();
            $produitStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produit->getPrixachat() * 100,
                    'product_data' => [
                        'name' => $produit->getLibcourt()
                    ]
                ], 
                'quantity' => $panier->getPanierQuantite()
            ];
        
            $total1 += $produit->getPrixachat() *  $panier->getPanierQuantite();
        }

        $produitStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => (round(($total1 * 0.2),2)) * 100,
                'product_data' => [
                    'name' => "TVA"
                ]
                ],
                'quantity' => 1,
            ];



            
         // dd($produitStripe);

        $produitStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $commande->getTransporteurPrix() * 100,
                'product_data' => [
                    'name' => $commande->getTransporteurNom()
                ]
            ], 
            'quantity' => 1,
        ];

    //     if($pttc > 200) {
    //         //$total =$totaltva+0; 
    //         $produitStripe[] = [
    //                 'price_data' => [
    //                     'currency' => 'eur',
    //                     'unit_amount' => 0,
    //                     'product_data' => [
    //                         'name' => 'fdp'
    //                     ]
    //                     ],
    //                     'quantity' => 1,
    //                 ];
    //     }
    //     else {
    //         $produitStripe[] = [
    //             'price_data' => [
    //                 'currency' => 'eur',
    //                 'unit_amount' => $fdp * 100,
    //                 'product_data' => [
    //                     'name' => 'fdp'
    //                 ]
    //                 ],
    //                 'quantity' => 1,
    //             ];
        
    // }

        Stripe::setApiKey('sk_test_51OGrEOEp0nRmZ5Z08LP6W2QIIvWXlPXk4efldJh8u1yBCm3Az3Ko8ha6o6ARFCbBvPlyrxM9SVEP5ne5EWocYIJS00CWMP336G');


        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $produitStripe
            ],
            'mode' => 'payment',
            'success_url' => $this->generator->generate('payment_success', [
                'reference' => $commande->getReference()],
                UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generator->generate('payment_error',
            ['reference' => $commande->getReference()],
            UrlGeneratorInterface::ABSOLUTE_URL
            )
            
        ]);
        $commande->setStripeSessionId($checkout_session->id);
        $this->em->flush();

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/order/success/{reference}', name: 'payment_success')]
    public function StripeSuccess(SessionInterface $session, $reference, CartService $cartService, EntityManagerInterface $em): Response
    {
        //$commande = $commandeRepository->findOneBy(['reference'=>$reference]);
       // dd($commande->getId());
        $commande = $this->em->getRepository(Commande::class)->findOneBy(['reference'=>$reference]);




                    $session->remove('cart');
                    $commande->setComFactId($commande->getId());
                    $em->persist($commande);
                    $em->flush(); 




        return $this->render('commande/success.html.twig');
    }


    #[Route('/order/error/{reference}', name: 'payment_error')]
    public function StripeError(SessionInterface $session, $reference, CartService $cartService): Response
    {
                $session->remove('cart');

        return $this->render('commande/error.html.twig');
    }
}