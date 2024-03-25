<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'pay')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository,
        Security $security
    ): Response {

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {

            // je récupère la session cart
            $cart = $request->getSession()->get('cart');
            // je créé une commande
            $order = new Order;
            $cartTotal = 0;

            for ($i = 0; $i < count($cart["id"]); $i++) {
                $cartTotal += (float) $cart["price"][$i] * $cart["quantity"][$i];
            }

            $order->setAmount($cartTotal);
            $order->setStatus('En cours');
            $order->setUser($this->getUser());
            $order->setDate(new \DateTime);

            $entityManager->persist($order);
            $entityManager->flush();

            // pour chaque élément de mon panier je créé un détail de commande
            for ($i = 0; $i < count($cart["id"]); $i++) {
                $orderDetails = new OrderDetails;
                $orderDetails->setOrderNumber($orderRepository->findOneBy([], ['id' => 'DESC']));
                $orderDetails->setProduct($productRepository->find($cart["id"][$i]));
                $orderDetails->setQuantity($cart["id"][$i]);

                $entityManager->persist($orderDetails);
                $entityManager->flush();

                // on génera le PDF
                // on l'enverra par mail la facture
                // on affichera une page de succès

                return $this->render('payment/index.html.twig', [
                    'controller_name' => 'PaymentController',
                ]);

            }
        }

        $session = $request->getSession();
        $session->set('url_retour', $request->getUri());

        // si pas connecté
        return $this->redirectToRoute('app_login');



    }
}
