<?php

    namespace App\Controller;

    use App\Entity\ReceivedContact;
    use App\Form\ContactFormType;
    use App\Repository\ReceivedContactRepository;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class ContactController extends AbstractController
    {
        /**
         * @Route("/contact", name="app_contact")
         * @param Request $request
         * @param ReceivedContactRepository $receivedContactRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function contact(Request $request, ReceivedContactRepository $receivedContactRepository)
        {
            $areNotMessages = $receivedContactRepository->queryReturnedEmptySet();

            $message = new ReceivedContact();

            $form = $this->createForm(ContactFormType::class, $message);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $message->setEmail($form->get('email')->getData());
                $message->setContent($form->get('content')->getData());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);
                $entityManager->flush();

                unset($form);
                unset($message);

                $this->addFlash('success', 'Wiadomość została wysłana! Oczekuj na zwrotnego emaila!');
                return $this->redirectToRoute('app_contact');
            }

            return $this->render('contact/contact.html.twig', [
                'contactForm' => $form->createView(),
                'areNotMessages' => $areNotMessages
            ]);
        }

        /**
         * @Route("/contact/show", name="app_show_emails_contact")
         * @param ReceivedContactRepository $receivedContactRepository
         * @IsGranted("ROLE_ADMIN")
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function showemails(ReceivedContactRepository $receivedContactRepository)
        {
            $query = $receivedContactRepository->findAllOrderedByEmail();

            return $this->render('contact/show.html.twig', [
                'mails' => $query,
            ]);
        }

    }
