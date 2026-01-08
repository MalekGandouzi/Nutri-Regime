<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/plats')]
class PlatController extends AbstractController
{
    #[Route('/', name: 'admin_plat_list', methods: ['GET'])]
    public function index(PlatRepository $repo, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $plats = $repo->findAll();

        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
        ]);
    }

    #[Route('/add', name: 'admin_plat_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid('plat_', true) . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image.');
                }

                $plat->setImage($newFilename);
            }

            $em->persist($plat);
            $em->flush();

            $this->addFlash('success', 'Plat créé avec succès.');
            return $this->redirectToRoute('admin_plat_list');
        }

        return $this->render('plat/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_plat_edit_all', methods: ['GET', 'POST'])]
    public function editAll(Plat $plat, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid('plat_', true) . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image.');
                }

                $plat->setImage($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Plat mis à jour.');
            return $this->redirectToRoute('admin_plat_list');
        }

        return $this->render('plat/edit.html.twig', [
            'form' => $form->createView(),
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}', name: 'admin_plat_show', methods: ['GET'])]
    public function show(Plat $plat, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('plat/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_plat_delete', methods: ['POST'])]
    public function delete(Plat $plat, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $em->remove($plat);
            $em->flush();
            $this->addFlash('success', 'Plat supprimé.');
        }

        return $this->redirectToRoute('admin_plat_list');
    }
}
