<?php

    namespace App\Controller;

    use App\Entity\Curiosity;
    use App\Form\CuriosityFormType;
    use App\Repository\CuriosityRepository;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class CuriosityController extends AbstractController
    {
        /**
         * @Route("/admin/curiosities", name="app_admin_curiosities")
         * @param CuriosityRepository $curiosityRepository
         * @return \Symfony\Component\HttpFoundation\Response
         * @IsGranted("ROLE_ADMIN")
         */
        public function index(CuriosityRepository $curiosityRepository)
        {
            $curiosities = $curiosityRepository->getAllOrderedByCreatedAtDESC();

            return $this->render('curiosity/show.html.twig', [
                'curiosities' => $curiosities,
            ]);
        }

        /**
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         * @IsGranted("ROLE_ADMIN")
         * @Route("/curiosities/new", name="app_curiosity_new")
         */
        public function add(Request $request)
        {
            $message = new Curiosity();

            $form = $this->createForm(CuriosityFormType::class, $message);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $message->setText($form->get('text')->getData());
                $message->setLink($form->get('link')->getData());
                $message->setTitle($form->get('title')->getData());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success-curiosity', 'Ciekawostka została dodana do bazy!');

                unset($form);
                unset($message);


                return $this->redirectToRoute('app_curiosity_new');
            }

            return $this->render('curiosity/add.html.twig', [
                'curiosityForm' => $form->createView(),
            ]);
        }

//        todo: edit, delete
    }
