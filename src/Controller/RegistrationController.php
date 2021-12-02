<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{
    private string $recaptchaSecretKet;

    /**
     * RegistrationController constructor.
     * @param string $recaptchaSecretKet
     */
    public function __construct(string $recaptchaSecretKet)
    {
        $this->recaptchaSecretKet = $recaptchaSecretKet;
    }

    /**
         * @Route("/register", name="app.register")
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recaptcha = new ReCaptcha($this->recaptchaSecretKet);
            $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

            if (!$resp->isSuccess()) {
                $this->addFlash('error', 'Le Recaptcha doit être coché');
                return $this->render('security/content/registration.html.twig', [
                    'registrationForm' => $form->createView()
                ]);
            }
            // Persist the user in DB
            $user->setIsChoiceAllowed(0)
                ->setIsVerified(0)
                ->setRoles(['ROLE_USER'])
                ->setUuid(Uuid::v4())
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé. Un email vous a été envoyé avec un lien pour confirmer votre inscription.');

            return $this->redirectToRoute('app.register');
        }

        return $this->render('security/content/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app.verify.email")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app.register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app.register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app.register');
    }
}
