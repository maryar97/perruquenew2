<?php


namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Panier;
use App\Form\CommandeType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    private EntityManagerInterface $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em ;
    
    }
    #[Route('/order/create', name: 'order_create')]
    public function index(CartService $cartService): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
    

        // dd($this->getUser());

        $form = $this->createForm(CommandeType::class, data: null,options: [
            'user' => $this->getUser()
        ]);
        
        return $this->render('commande/index.html.twig', [
            'form' => $form->createView(),
            'recapCart' => $cartService->getTotal()
        ]);
    }
    #[Route('/order/verify', name: 'order_prepare', methods: ['POST'])]
    public function prepareOrder(SessionInterface $session, CartService $cartService, Request $request): Response
    {
        if(!$this->getUser()){

            return $this->redirectToRoute('app_login');
                    }

        $form = $this->createForm(CommandeType::class, data: null,options: [
            'user' =>$this->getUser()
        ]);
        $total=0;
        $fdp=10;
        $pttc=0;
        $total1=0; 
        $Facturetotaltva = 20; 

        $form->handleRequest($request);

        IF($form->isSubmitted() && $form->isValid()){
            $datetimeimmutable = new \DateTimeImmutable( 'now');
            $transporteur = $form->get('transporteur')->getData();
            $livraison = $form->get('adresse')->getData();
            $livraisonForCommande = $livraison->getAdrPrenom().' '.$livraison->getAdrNom();
            // dd($livraison->getTelephone());
            $livraisonForCommande .= ' ' . $livraison->getAdrTelephone();
            $livraisonForCommande .= ' ' . $livraison->getAdresse();
            $livraisonForCommande .= ' ' . $livraison->getAdrCodepostal() . '-' . $livraison->getAdrVille();
            // $comId = $commandeRepository->findOneBy(['com_fact_id' => $id])->getId();
            $livraisonForCommande .= ' ' . $livraison->getAdrPays();
            $total1 = $pttc + $fdp; 
            $pttc = $total1 * 1.2 ;
            // dd($livraisonForCommande);
            $commande = new Commande();
            $reference = $datetimeimmutable->format('dmy').'-'.uniqid(); 
            $commande->setComUsers($this->getUser());
            $commande->setReference($reference);
            $commande->setCreateAt($datetimeimmutable);
            $commande->setLivraison($livraisonForCommande);
            $commande->setTransporteurNom($transporteur->getNom());
            $commande->setTransporteurPrix($transporteur->getPrix());
            $commande->setIsPaid('bool');
            $commande->setMethode('stripe'); 
            $commande->setComAdrLivr($livraison);
            $commande->setAdrFact($livraisonForCommande); 
            //$commande->setComFactId();
            // $commande->setDateFact($datetimeimmutable);            
            // $commande->setComAdrLivr($livraison);
            // dd($commande);
            // $commande->setComFactId($comId);
            // $commande->setDateFact($datetimeimmutable);
            $this->em->persist($commande);
            // dd($commande); 


            foreach($cartService->getTotal() as $produit)
            {
                // dd($produit);
                $panier = new Panier();
                $panier->setPanierCom($commande);  
                $panier->setPanierQuantite($produit['quantite']); 
                $panier->setPrixUnite($produit['produit']->getPrixachat()); 
                $panier->setPanierProd($produit['produit']);
                $panier->setTotalRecap($produit['produit']->getPrixAchat() * $produit['quantite']);
                $produit['produit']->setQuantite($produit['produit']->getQuantite() - $produit['quantite']); 
                $this->em->persist($panier);
                $this->em->flush();
        }

            // dd($form->getData()); 
            return $this->render('commande/recap.html.twig', [
                'Facturetotaltva' => $Facturetotaltva,
                'total1' => $total1,
                'fdp' => $fdp, 
                'pttc' => $pttc,
                'total' => $total,
                'methode' => $commande->getMethode(),
                'recapCart' => $cartService->getTotal(),
                'transporteur' => $transporteur,
                'livraison' => $livraisonForCommande, 
                'reference' => $commande->getReference()
        ]);

        }

        return $this->redirectToRoute('cart_index');

    }
    
}