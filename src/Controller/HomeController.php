<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ActivationTokenType;
use App\Form\ContactType;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PanierService $panier, Request $request): Response
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
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // On confirme et on redirige
            $this->addFlash('success', 'Votre e-mail a bien été envoyé, En vous repondre dans les plus bref delais Merci');
            return $this->redirectToRoute('home');
        }
        return $this->render('home/index.html.twig', [
            // 'items' => $panier->fullPanier(),
            'nb' => $panier->nbProduits(),
            'form' => $form->createView()
        ]);
    }
    /**
     * **Renvoyer le code d'activation du compte
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
        $this->addFlash('warning', 'Un nouveau lien a été envoyer à  ' . $user->getEmail() . '.Si vous ne le recevez pas, verfier vos corriers indésirables. Merci');
        return $this->redirectToRoute('home');
    }

    // /**
    //  * @Route("/contact", name="contact", methods={"POST"})
    //  */
    // public function contact(Request $request): Response
    // {
    //     // creates a contact form
    //     $contact = new Contact();

    //     $form = $this->createForm(ContactType::class, $contact);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($contact);
    //         $entityManager->flush();

    //         // On confirme et on redirige
    //         $this->addFlash('success', 'Votre e-mail a bien été envoyé, En vous repondre dans les plus bref delais Merci');
    //         return $this->redirectToRoute('home');
    //     }
    //     return $this->render('home/index.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
