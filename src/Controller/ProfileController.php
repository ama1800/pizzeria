<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarType;
use App\Form\ProfileType;
use App\Form\ModifPassType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_USER")
 * @Route("/profile")
 */

class ProfileController extends AbstractController
{
    /**
     * @Route("/{id}", name="profile", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository)
    {
        $id = $request->get('id');
        $user = $userRepository->find($id);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/{id}/modif", name="profile_edit", methods={"GET","POST"})
     */
    public function modifProfile(Request $request, SluggerInterface $slugger, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        //  id d'user dans la session
        $id = $this->getUser()->getId();
        //  objet user dans la session
        $user = $this->getUser();
        // id user dans l'url
        $userModif = $request->get('id');
        // dd($userModif);
        if ($id == $userModif) {
            //  form modification email et etat civile adresse
            $form1 = $this->createForm(ProfileType::class, $user);
            //  form modification mot de passe
            $form2 = $this->createForm(ModifPassType::class, $user);
            //  id modification avatar
            // $form3 = $this->createForm(AvatarType::class, $user);


            $form1->handleRequest($request);
            if ($form1->isSubmitted() && $form1->isValid()) {

                $entityManager->persist($user);
                $entityManager->flush();
                // message de succee
                $this->addFlash('message', 'Profile à jour. Merci');
                // redirection vers le profile
                return $this->redirectToRoute('profile', ['id' => $id]);
            }



            $form2->handleRequest($request);
            if ($form2->isSubmitted() && $form2->isValid()) {
                // get l'ancien mot de passe
                $oldPassword = $request->request->get('modif_pass')['actuelPassword'];
                // Si c'est le bon 'ancien mot de passe
                if ($encoder->isPasswordValid($user, $oldPassword)) {
                    // hashage du nouveau password
                    $newEncodedPassword = $encoder->encodePassword(
                        $user,
                        $form2->get('plainPassword')->getData()
                    );
                    $user->setPassword($newEncodedPassword);
                    // enregistrement de hash du nouveau mot de passe
                    $entityManager->flush();
                } else {
                    $form2->addError(new FormError('Erreur..!Vous n\'avez pas saisie l\'ancien mot de passe à changer'));
                }
                $this->addFlash('succes', 'Votre mot de passe à bien été changé !');
                return $this->redirectToRoute('profile', ['id' => $id]);
            }


            // $form3->handleRequest($request);
            // if ($form3->isSubmitted() && $form3->isValid()) 
            // {
            //     // dd($user);
            //     // upload photo
            //     /** @var UploadedFile $brochureFile */
            //     $brochureFile = $form3['brochure']->getData();

            //     if ($brochureFile) 
            //     {
            //         $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            //         // this is needed to safely include the file name as part of the URL
            //         $safeFilename = $slugger->slug($originalFilename);
            //         $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            //         // Move the file to the directory where brochures are stored
            //         try {
            //             $brochureFile->move(
            //                 $this->getParameter('brochures_directory'),
            //                 $newFilename
            //             );
            //         } catch (FileException $e) {
            //             // ... handle exception if something happens during file upload
            //             $e->getMessage();
            //         }
            //         // updates the 'photo' property to store the image file name
            //         // instead of its contents
            //         $user->setPhoto($newFilename); 
            //     }
            //      $entityManager->persist($user);
            //      $entityManager->flush();

            //     $this->addFlash('message', 'Profile à jour. Merci');
            //     return $this->redirectToRoute('profile', ['id'=> $id]);
            // }
            return $this->render('profile/modifProfile.html.twig', [
                'user' => $user,
                'form1' => $form1->createView(),
                'form2' => $form2->createView(),
                // 'form3' => $form3->createView(),
            ]);
        } else {
            $this->addFlash('danger', 'Ce n\'est pas votre compte..! Vous ne pouvez pas le modifier!');
            return  $this->redirectToRoute('home');
        }
    }
}
