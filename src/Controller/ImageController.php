<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/** 
 * @isGranted("ROLE_GERANT")
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/", name="image_index", methods={"GET"})
     */
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    /** 
     * ** @isGranted("ROLE_GERANT")
     * @Route("/new", name="image_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            // recupére le lien vers la photo à utiliser
            $brochureFile = $form['brochure']->getData();
            if ($brochureFile) { //Si le lien existe
                // on recupére l'emplacement (le path) de la photo à telecharger
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                // on utlise le sullger pour supprimer les espaces dans le nom de la photo pour la telecharger sans problémes
                $safeFilename = $slugger->slug($originalFilename);
                // On génére le nouveau nom de la photo avec son extension
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Déplace l'image vers le fichier où elle sera stocker
                // On utilise le try et catch pour avoir un message d'erreur au cas d'échec.
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // génére un message d'erreur au cas ou y en a
                    $e->getMessage();
                }
                // edite la photo avec le nouveau nom qu'on stock à la base de données.
                // instead of its contents
                $image->setUrl($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('image_index');
        }

        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_show", methods={"GET"})
     */
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    /** 
     * ** @isGranted("ROLE_GERANT")
     * @Route("/{id}/edit", name="image_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Image $image, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            // recupére le lien vers la photo à utiliser
            $brochureFile = $form['brochure']->getData();
            if ($brochureFile) { //Si le lien existe
                // on recupére l'emplacement (le path) de la photo à telecharger
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                // on utlise le sullger pour supprimer les espaces dans le nom de la photo pour la telecharger sans problémes
                $safeFilename = $slugger->slug($originalFilename);
                // On génére le nouveau nom de la photo avec son extension
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Déplace l'image vers le fichier où elle sera stocker
                // On utilise le try et catch pour avoir un message d'erreur au cas d'échec.
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // génére un message d'erreur au cas ou y en a
                    $e->getMessage();
                }
                // edite la photo avec le nouveau nom qu'on stock à la base de données.
                // instead of its contents
                $image->setUrl($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('image_index');
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /** 
     * ** @isGranted("ROLE_GERANT")
     * @Route("/{id}", name="image_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('image_index');
    }
}
