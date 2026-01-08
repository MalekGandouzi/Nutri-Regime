<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Form\RegimeType;
use App\Repository\RegimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/regimes')]
class RegimeController extends AbstractController
{
    #[Route('/', name: 'admin_regime_list', methods: ['GET'])]
    public function index(RegimeRepository $repo, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $regimes = $repo->findAll();

        return $this->render('regime/index.html.twig', [
            'regimes' => $regimes,
        ]);
    }

    #[Route('/add', name: 'admin_regime_add', methods: ['GET', 'POST'])]
    public function addM(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $regime = new Regime();
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid('regime_', true) . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image.');
                }

                $regime->setImage($newFilename);
            }

            $em->persist($regime);
            $em->flush();

            $this->addFlash('success', 'Régime créé avec succès.');
            return $this->redirectToRoute('admin_regime_list');
        }

        return $this->render('regime/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_regime_edit_all', methods: ['GET', 'POST'])]
    public function editAll(Regime $regime, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid('regime_', true) . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image.');
                }

                $regime->setImage($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Régime mis à jour.');
            return $this->redirectToRoute('admin_regime_list');
        }

        return $this->render('regime/edit.html.twig', [
            'form'   => $form->createView(),
            'regime' => $regime,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_regime_delete', methods: ['GET'])]
    public function delete(Regime $regime, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('admin_logged')) {
            return $this->redirectToRoute('app_login');
        }

        $em->remove($regime);
        $em->flush();

        $this->addFlash('success', 'Régime supprimé.');
        return $this->redirectToRoute('admin_regime_list');
    }
    #[Route('/{id}', name: 'admin_regime_show', methods: ['GET'])]
public function show(Regime $regime, SessionInterface $session): Response
{
    if (!$session->get('admin_logged')) {
        return $this->redirectToRoute('app_login');
    }

    
    $plats = $regime->getPlats();

    return $this->render('regime/show.html.twig', [
        'regime' => $regime,
        'plats'  => $plats,
    ]);
}


}
