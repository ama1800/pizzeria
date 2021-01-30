<?php

namespace App\Controller;

use App\Form\ActivationTokenType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $token = $user->getActivationToken();
            // verfication si le token d'activation n'est pas null
            if (isset($token) && $token !== null) {
                // Message indiquant qu'il faut activer le compte
                $this->addFlash('danger', 'Votre compte n\'est pas actif, veuillez l\'activer à partir du lien qu\'on vous a envoyé sur vote email. Merci');
            }
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/resendActivation", name="resendActivation", methods={"POST"})
     */
    public function resendActivation(Request $request, \Swift_Mailer $mailer, EntityManagerInterface $entityManager): Response
    {
        // récupérer l'user de la session
        $user = $this->getUser();
        // Regenerer le nouveau token d'activation de compte

        $user->setActivationToken(md5(uniqid()));

        // persister l'user avec le nouveau token
        $entityManager->persist($user);
        $entityManager->flush();
        // le message envoyer à l'user
        $message = (new \Swift_Message('Activation de votre compte'))
            // Expediteur
            ->setFrom('admin@pizza.fr')
            // Email du déstinataire
            ->setTo($user->getEmail())
            // Contenu avec le lien générer pour l'activation du compte
            ->setBody(
                $this->renderView(
                    'emails/activation.html.twig',
                    ['token' => $user->getActivationToken()]
                ),
                'text/html'
            );
        // on envoie l'email
        $mailer->send($message);


        // Message flash de succes d'envoie d'un nouveau code
        $this->addFlash('warning', 'Un nouveau lien a été envoyer à ' . $user->getEmail() . '');
        return $this->redirectToRoute('home');
    }
}
