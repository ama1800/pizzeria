<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ResetPassType;
use App\Form\AdminEditUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


/**
 * @Route("/user")
 */

class UserController extends AbstractController
{

    /**
     * @isGranted("ROLE_ADMIN") 
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**   
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // // upload photo
            // /** @var UploadedFile $brochureFile */
            // $brochureFile = $form['brochure']->getData();

            // if ($brochureFile) {
            //     $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            //     // this is needed to safely include the file name as part of the URL
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

            //     // Move the file to the directory where brochures are stored
            //     try {
            //         $brochureFile->move(
            //             $this->getParameter('brochures_directory'),
            //             $newFilename
            //         );
            //     } catch (FileException $e) {
            //         // ... handle exception if something happens during file upload
            //         $e->getMessage();
            //     }
            //     // updates the 'photo' property to store the image file name
            //     // instead of its contents
            //     $user->setPhoto($newFilename);
            // }
            //le hashage du mot de passe
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            // Generer le token d'activation de compte

            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Céation du message
            $message = (new \Swift_Message('Activation de votre compte'))
                // Expediteur
                ->setFrom('admin@pizza.fr')
                // Destenataire
                ->setTo($user->getEmail())
                // Contenu
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig',
                        ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );
            // on envoie l'email
            $mailer->send($message);


            // Message flash de succes
            $this->addFlash('warning', 'Création de compte avec succes. Email d\'activation est envoyer à ' . $user->getEmail() . 'Suivez le lien qui y est pour activer votre compte.');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/passoublie", name="app_forgottenPassword")
     */
    public function forgottenPassword(Request $request, UserRepository $userRepository, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        //  on cree le formulaire
        $form = $this->createForm(ResetPassType::class);

        //  traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //  recupéer les donnees
            $data = $form->getData();
            // chercher la corespandance de l'email dans la BD
            $user = $userRepository->findOneByEmail($data['email']);
            if (!$user) {
                // Message flash
                $this->addFlash('danger', 'Cet email n\'existe pas');
                return $this->redirectToRoute('app_login');
            }
            // On génere un token avec le token generator
            $token = $tokenGenerator->generateToken();
            try {
                $user->setRestToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', 'attention, il y a une erreur :' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }
            // envoie vers la page de saisie de l'email
            //  génerer l'adresse de reinitialisation du mot de passe
            $url = $this->generateUrl(
                'app_reset_password',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            //  envoie du message
            $message = (new \Swift_Message('Mot de passe oublier'))
                ->setFrom('no_replay@centre_formation.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    '
        <span>Bonjour,</span><p> vous avez oublier votre mot de passe, vous pouvez le réinitiliser en suivant le lien si dessous.
        Le lien de réinitialisation est disponible pour une duree de 4 heures, dépasser les 4 heures vous devez refaire une autre demande pour pouvoir réinitialiser votre mot de passe.
        Cordialement.
        Pour la réinitialisation de votre mot de passe <a href="' . $url . '">Suivez ce lien</a></p>',
                    'taxt/html'
                );
            //  Envoie du message
            $mailer->send($message);
            // Message de success
            $this->addFlash('message', 'Un email contenant un lien de réinitialisation de votre mot passe vous a étè bien envoyer à votre boite email; si vous ne le recever pas merci de verfier votre email indésirable');
            return $this->redirectToRoute('app_login');
        }
        //  on envoie vers la page de demande de l'email
        return $this->render('security/forgottenPass.html.twig', ['emailForm' => $form->createView()]);
    }
    /**
     * @Route("/resetpass", name="app_reset_password")
     */
    public function resetPassword(Request $request, $token = null, UserRepository $users, UserPasswordEncoderInterface $encoder)
    {

        $token = $request->query->get('token') ? $request->query->get('token') : null;
        if ($token !== null) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['restToken' => $token]);
            if (!$user) {
                // Message flash
                $this->addFlash('danger', 'Y a un probleme d\'authentification!! SVP veuillez réessayer de nouveau.');
                return $this->redirectToRoute('app_login');
            }
            if ($request->isMethod('POST')) {
                $user->setRestToken(null);
                $hash = $encoder->encodePassword($user, $request->request->get('password'));
                $user->setPassword($hash);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();


                $this->addFlash('message', 'Mot de passe modifier avec succee.');
                return $this->redirectToRoute('app_login');
            } else
                return $this->render('security/resetpass.html.twig', ['token' => $token]);
        }
    }

    /**
     * @isGranted("ROLE_ADMIN") 
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @isGranted("ROLE_ADMIN") 
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): Response
    {
        $role = $this->getUser()->getRoles();
        $form = $this->createForm(AdminEditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // // upload photo
            // /** @var UploadedFile $brochureFile */
            // $brochureFile = $form['brochure']->getData();

            // if ($brochureFile) {
            //     $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            //     // this is needed to safely include the file name as part of the URL
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

            //     // Move the file to the directory where brochures are stored
            //     try {
            //         $brochureFile->move(
            //             $this->getParameter('brochures_directory'),
            //             $newFilename
            //         );
            //     } catch (FileException $e) {
            //         // ... handle exception if something happens during file upload
            //         $e->getMessage();
            //     }
            //     // updates the 'photo' property to store the image file name
            //     // instead of its contents
            //     $user->setPhoto($newFilename);
            // }
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            // Message flash de success
            $this->addFlash('success', 'Compte Mise à jour avec succes.');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        // Message flash de supprission
        $this->addFlash('danger', 'Compte supprimer avec succes.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/activation/{token}", name="user_activation", methods={"GET","POST"})
     */
    public function activation($token, UserRepository $userRepository): Response
    {
        // verification de l'existance de token a la base de donnee avec le token du lien
        $user = $userRepository->findOneBy(['activationToken' => $token]);

        // Si aucun user ne posséde ce token

        if (!$user) {
            // Erreur ce token n'existe pas
            throw $this->createNotFoundException('Cet Utilisateur n\'exist pas, ou ce compte est déjà activé!!');
        }
        // Si token existe alors on change ça valeur à null

        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Message flash de sucess activation
        $this->addFlash('message', 'Votre compte est activer vous pouvez y acceder et changer votre profile et mot de passe. Merci');
        return $this->redirectToRoute('home');
    }



    /**
     * @Route("/editpass", name="app_editpass")
     */
    public function changePassword(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
