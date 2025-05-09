<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use App\Service\EmailService;
use App\Service\FaceRecognitionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{
    private $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Gérer l'upload de l'image de visage si présente
            if ($faceImage = $form->get('faceImage')->getData()) {
                try {
                    // Vérifier si un visage est détecté dans l'image
                    $result = $this->faceRecognitionService->detectFace($faceImage);
                    
                    if (!empty($result['faces'])) {
                        // Générer un nom de fichier unique
                        $originalFilename = pathinfo($faceImage->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$faceImage->guessExtension();

                        // Déplacer le fichier dans le répertoire des uploads
                        $faceImage->move(
                            $this->getParameter('face_images_directory'),
                            $newFilename
                        );

                        // Sauvegarder le nom du fichier dans l'entité
                        $user->setFaceImage($newFilename);
                    } else {
                        $this->addFlash('error', 'Aucun visage n\'a été détecté dans l\'image fournie.');
                        return $this->render('security/register.html.twig', [
                            'registrationForm' => $form->createView(),
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du traitement de l\'image : ' . $e->getMessage());
                    return $this->render('security/register.html.twig', [
                        'registrationForm' => $form->createView(),
                    ]);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_front_office');
        }

        if ($request->isMethod('POST')) {
            // Mode 1 : Connexion par reconnaissance faciale
            if ($request->files->has('face_image')) {
                try {
                    $faceImage = $request->files->get('face_image');
                    $email = $request->request->get('emailUser');

                    $user = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $email]);

                    if (!$user) {
                        $this->addFlash('error', 'Utilisateur non trouvé.');
                        return $this->render('security/login.html.twig', [
                            'last_username' => $email,
                            'error' => null
                        ]);
                    }

                    if (!$user->getFaceImage()) {
                        $this->addFlash('error', 'Aucune image de référence n\'est enregistrée pour cet utilisateur.');
                        return $this->render('security/login.html.twig', [
                            'last_username' => $email,
                            'error' => null
                        ]);
                    }

                    $referenceImagePath = $this->getParameter('face_images_directory') . '/' . $user->getFaceImage();
                    $referenceImage = new \Symfony\Component\HttpFoundation\File\UploadedFile(
                        $referenceImagePath,
                        $user->getFaceImage(),
                        null,
                        null,
                        true // test mode
                    );

                    $result = $this->faceRecognitionService->compareFaces($faceImage, $referenceImage);

                    // Face++ renvoie 'confidence' (0-100)
                    if (!empty($result['confidence']) && $result['confidence'] > 80) {
                        // Connexion manuelle de l'utilisateur
                        $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                            $user,
                            'main',
                            $user->getRoles()
                        );
                        $this->container->get('security.token_storage')->setToken($token);
                        $request->getSession()->set('_security_main', serialize($token));
                        $this->addFlash('success', 'Connexion par reconnaissance faciale réussie !');
                        // Redirection selon le rôle
                        $roles = $user->getRoles();
                        if (in_array('ROLE_Employe', $roles) || in_array('ROLE_Candidat', $roles)) {
                            return $this->redirectToRoute('app_front_office');
                        } else {
                            return $this->redirectToRoute('app_home');
                        }
                    } else {
                        $this->addFlash('error', 'Le visage ne correspond pas à l\'image de référence.');
                        return $this->render('security/login.html.twig', [
                            'last_username' => $email,
                            'error' => null
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la reconnaissance faciale: ' . $e->getMessage());
                    return $this->render('security/login.html.twig', [
                        'last_username' => $request->request->get('emailUser'),
                        'error' => null
                    ]);
                }
            }
            // Mode 2 : Connexion classique (email + mot de passe)
            // Laisse le système de sécurité Symfony gérer normalement
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $email]);

            if ($user) {
                try {
                    // Générer un code de réinitialisation à 6 chiffres
                    $resetCode = $emailService->generateResetCode();
                    $user->setResetCode($resetCode);
                    $entityManager->flush();

                    // Envoyer l'email avec le code de réinitialisation
                    $emailService->sendResetPasswordEmail($user->getEmailUser(), $resetCode);

                    $this->addFlash('success', 'Un email contenant le code de réinitialisation a été envoyé à votre adresse email.');
                    return $this->redirectToRoute('app_reset_password', ['email' => $email]);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer plus tard.');
                    return $this->render('security/forgot_password.html.twig');
                }
            }

            $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
        }

        return $this->render('security/forgot_password.html.twig');
    }

    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $email = $request->query->get('email');
        $user = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $email]);

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $resetCode = $request->request->get('reset_code');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validation du code de réinitialisation
            if (!preg_match('/^\d{6}$/', $resetCode)) {
                $this->addFlash('error', 'Le code de réinitialisation doit contenir exactement 6 chiffres.');
                return $this->render('security/reset_password.html.twig');
            }

            if ($user->getResetCode() !== $resetCode) {
                $this->addFlash('error', 'Code de réinitialisation invalide.');
                return $this->render('security/reset_password.html.twig');
            }

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->render('security/reset_password.html.twig');
            }

            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $user->setResetCode(null);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig');
    }

    #[Route('/login/face', name: 'app_login_face')]
    public function loginFace(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            try {
                $faceImage = $request->files->get('face_image');
                $email = $request->request->get('emailUser');

                $user = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $email]);

                if (!$user) {
                    $this->addFlash('error', 'Utilisateur non trouvé.');
                    return $this->render('security/login_face.html.twig');
                }

                if (!$user->getFaceImage()) {
                    $this->addFlash('error', 'Aucune image de référence n\'est enregistrée pour cet utilisateur.');
                    return $this->render('security/login_face.html.twig');
                }

                $referenceImagePath = $this->getParameter('face_images_directory') . '/' . $user->getFaceImage();
                $referenceImage = new \Symfony\Component\HttpFoundation\File\UploadedFile(
                    $referenceImagePath,
                    $user->getFaceImage(),
                    null,
                    null,
                    true // test mode
                );

                $result = $this->faceRecognitionService->compareFaces($faceImage, $referenceImage);

                if (!empty($result['confidence']) && $result['confidence'] > 80) {
                    $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                        $user,
                        'main',
                        $user->getRoles()
                    );
                    $this->container->get('security.token_storage')->setToken($token);
                    $request->getSession()->set('_security_main', serialize($token));
                    $this->addFlash('success', 'Connexion par reconnaissance faciale réussie !');
                    // Redirection selon le rôle
                    $roles = $user->getRoles();
                    if (in_array('ROLE_Employe', $roles) || in_array('ROLE_Candidat', $roles)) {
                        return $this->redirectToRoute('app_front_office');
                    } else {
                        return $this->redirectToRoute('app_front_office');
                    }
                } else {
                    $this->addFlash('error', 'Le visage ne correspond pas à l\'image de référence.');
                    return $this->render('security/login_face.html.twig');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la reconnaissance faciale: ' . $e->getMessage());
                return $this->render('security/login_face.html.twig');
            }
        }

        return $this->render('security/login_face.html.twig');
    }
} 