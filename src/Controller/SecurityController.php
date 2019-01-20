<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Form\RegistrationFormType;
    use App\Repository\UserPhotoRepository;
    use App\Security\LoginFormAuthenticator;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class SecurityController extends AbstractController
    {
        /**
         * @Route("/login", name="app_login")
         * @param AuthenticationUtils $authenticationUtils
         * @return Response
         */
        public function login(AuthenticationUtils $authenticationUtils): Response
        {
            if (($this->isGranted("ROLE_USER"))) {
                return $this->redirectToRoute("app_homepage");
            }

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        }

        /**
         * @Route("/register", name="app_register")
         * @param Request $request
         * @param UserPasswordEncoderInterface $passwordEncoder
         * @param GuardAuthenticatorHandler $guardHandler
         * @param LoginFormAuthenticator $authenticator
         * @param UserPhotoRepository $userPhotoRepository
         * @return Response
         */
        public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, UserPhotoRepository $userPhotoRepository): Response
        {
            if (($this->isGranted("ROLE_USER"))) {
                return $this->redirectToRoute("app_homepage");
            }
            /** @var User $user */
            $user = new User();

            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                if( true === $form->get('agreeTerms')->getData())
                    $user->agreeToTerms();
                $user->setRoles(['ROLE_USER']);
                $user->setPhoto($this->getRandomReference('main_photos'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }

            return $this->render('security/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }

        /**
         * @Route("/logout", name="app_logout")
         * @throws \Exception
         */
        public
        function logout()
        {
            throw new \Exception("Logout failed!");
        }
    }
