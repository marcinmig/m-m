<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Access denied');

        $form = $this->createFormBuilder()
            ->add('username', TextType::class, ['label' => 'Nazwa użytkownika'])
            ->add('email', EmailType::class, ['label' => 'E-Mail'])
            ->add('password',PasswordType::class, ['label' => 'Hasło'])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Administrator' => 'ROLE_SUPER_ADMIN',
                    'Pracownik' => 'ROLE_USER',
                    'Audytor' => 'ROLE_AUDITOR',
                ],
                'label' => 'Uprawnienia'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Dodaj'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setPlainPassword($userData['password']);
            $user->addRole($userData['role']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Użytkownik dodany');
            return $this->redirectToRoute('user_show', ['userId' => $user->getId()]);
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{userId}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $userId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Access denied');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, ['label' => 'E-Mail'])
            ->add('plainPassword',PasswordType::class, ['required' => false, 'label' => 'Hasło'])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Administrator' => 'ROLE_SUPER_ADMIN',
                    'Pracownik' => 'ROLE_USER',
                    'Audytor' => 'ROLE_AUDITOR',
                ],
                'label' => 'Uprawnienia',
                'mapped' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();
            $this->addFlash('success', 'Zmiany zapisane');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/{userId}/delete", name="user_delete", methods={"POST"})
     */
    public function deleteAction($userId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Access denied');

        if ($userId == $this->getUser()->getId()) {
            $this->addFlash('error', 'Nie można usunąć swojego konta');
        }
        else {
            $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Użytkownik usunięty');
        }

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * @Route("/user/{userId}", name="user_show")
     */
    public function showAction($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
}
