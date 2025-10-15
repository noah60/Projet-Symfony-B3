<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Prestataire;
use App\Form\UserProfileType;
use App\Form\ClientProfileType;
use App\Form\PrestataireProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/account')]
#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();

        // Redirection selon rôle
        if (in_array('ROLE_PRESTATAIRE', $user->getRoles(), true)) {
            return $this->redirectToRoute('app_account_prestataire');
        }

        return $this->redirectToRoute('app_account_client');
    }

    #[Route('/client', name: 'app_account_client')]
    public function clientAccount(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        // Récupération (ou création) du Client lié à l'utilisateur
        $client = $em->getRepository(Client::class)->findOneBy(['utilisateur' => $user]);
        $isNewClient = false;
        if (!$client) {
            $client = new Client();
            $client->setUtilisateur($user); // vérifie que la relation s'appelle bien 'utilisateur'
            $isNewClient = true;
        }

        // Création des formulaires
        $userForm = $this->createForm(UserProfileType::class, $user);
        $clientForm = $this->createForm(ClientProfileType::class, $client);

        // Liaison à la requête
        $userForm->handleRequest($request);
        $clientForm->handleRequest($request);

        // --- Traitement du formulaire utilisateur ---
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // Récupération du mot de passe en clair depuis le formulaire (champ plainPassword)
            $plainPassword = $userForm->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Persist si nécessaire (même si l'entité est normalement déjà gérée)
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Vos informations personnelles ont été mises à jour.');
            return $this->redirectToRoute('app_account_client');
        }

        // --- Traitement du formulaire client ---
        if ($clientForm->isSubmitted() && $clientForm->isValid()) {
            // Exemple : gestion d'un champ d'upload 'imageFile' (si présent dans ton form)
            if ($clientForm->has('imageFile')) {
                $imageFile = $clientForm->get('imageFile')->getData();
                if ($imageFile) {
                    $newFilename = uniqid('', true) . '.' . $imageFile->guessExtension();
                    // Assure-toi d'avoir configuré 'uploads_directory' dans services.yaml / parameters
                    $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                    $client->setImageFilename($newFilename);
                }
            }

            // Si l'entité est nouvelle, on la persiste
            if ($isNewClient) {
                $em->persist($client);
            }
            $em->flush();

            $this->addFlash('success', 'Votre profil client a été mis à jour.');
            return $this->redirectToRoute('app_account_client');
        }

        return $this->render('account/client.html.twig', [
            'user' => $user,
            'client' => $client,
            'userForm' => $userForm->createView(),
            'clientForm' => $clientForm->createView(),
        ]);
    }

    #[Route('/prestataire', name: 'app_account_prestataire')]
    #[IsGranted('ROLE_PRESTATAIRE')]
    public function prestataireAccount(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        // Récupération (ou création) du Prestataire lié à l'utilisateur
        $prestataire = $em->getRepository(Prestataire::class)->findOneBy(['utilisateur' => $user]);
        $isNewPrestataire = false;
        if (!$prestataire) {
            $prestataire = new Prestataire();
            $prestataire->setUtilisateur($user); // vérifie que la relation s'appelle bien 'utilisateur'
            // valeurs par défaut cohérentes
            $prestataire->setNombreavis(0);
            $prestataire->setNote(0.0);
            $isNewPrestataire = true;
        }

        // Création des formulaires
        $userForm = $this->createForm(UserProfileType::class, $user);
        $prestataireForm = $this->createForm(PrestataireProfileType::class, $prestataire);

        // Liaison à la requête
        $userForm->handleRequest($request);
        $prestataireForm->handleRequest($request);

        // --- Traitement du formulaire utilisateur ---
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $userForm->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Vos informations personnelles ont été mises à jour.');
            return $this->redirectToRoute('app_account_prestataire');
        }

        // --- Traitement du formulaire prestataire ---
        if ($prestataireForm->isSubmitted() && $prestataireForm->isValid()) {
            if ($isNewPrestataire) {
                $em->persist($prestataire);
            }
            $em->flush();

            $this->addFlash('success', 'Votre profil prestataire a été mis à jour.');
            return $this->redirectToRoute('app_account_prestataire');
        }

        return $this->render('account/prestataire.html.twig', [
            'user' => $user,
            'prestataire' => $prestataire,
            'userForm' => $userForm->createView(),
            'prestataireForm' => $prestataireForm->createView(),
        ]);
    }

    #[Route('/delete', name: 'app_account_delete', methods: ['POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été supprimé.');
            return $this->redirectToRoute('app_logout');
        }

        $this->addFlash('error', 'Token CSRF invalide.');
        return $this->redirectToRoute('app_account');
    }
}
