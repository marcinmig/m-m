<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Presence;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\Request;

class PresenceController extends Controller
{
    /**
     * @Route("/presence", name="presence_list")
     */
    public function indexAction(Request $request)
    {
        if ($this->getUser()->hasRole('ROLE_SUPER_ADMIN')) {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();

            $dates = [];
            $now = new \DateTime("now");
            $days = date("t");
            for ($i = 1; $i <= $days; $i++) {
                $dates[]= "$i-{$now->format('m-Y')}";
            }


            return $this->render('presence/index-admin.html.twig', ['users' => $users, 'dates' => $dates]);
        }

        $presence = new Presence();
        $form = $this->createFormBuilder($presence)
            ->add('date', DateType::class, ['label' => 'Data'])
            ->add('startTime', TimeType::class, ['label' => 'Czas rozpoczęcia'])
            ->add('endTime', TimeType::class, ['label' => 'Czas zakończenia'])
            ->add('extraInfo', TextType::class, ['label' => 'Uwagi', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Dodaj'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presence = $form->getData();
            $presence->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($presence);
            $em->flush();
            $this->addFlash('success', 'Wpis dodany');
        }

        $presences = $this->getDoctrine()
            ->getRepository(Presence::class)
            ->findAll();

        return $this->render('presence/index.html.twig', [
            'user' => $this->getUser(),
            'presences' => $presences,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/presence/{presenceId}/edit", name="presence_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $presenceId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Access denied');

        $presence = $this->getDoctrine()
            ->getRepository(Presence::class)
            ->find($presenceId);

        $form = $this->createFormBuilder($presence)
            ->add('date', DateType::class, ['label' => 'Data'])
            ->add('startTime', TimeType::class, ['label' => 'Czas rozpoczęcia'])
            ->add('endTime', TimeType::class, ['label' => 'Czas zakończenia'])
            ->add('extraInfo', TextType::class, ['label' => 'Uwagi', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presence = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($presence);
            $em->flush();
            $this->addFlash('success', 'Zmiany zapisane');
        }

        return $this->render('presence/edit.html.twig', [
            'form' => $form->createView(),
            'presence' => $presence,
        ]);
    }

    /**
     * @Route("/presence/{presenceId}/delete", name="presence_delete", methods={"POST"})
     */
    public function deleteAction($presenceId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Access denied');

        $presence = $this->getDoctrine()->getRepository(Presence::class)->find($presenceId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($presence);
        $em->flush();
        $this->addFlash('success', 'Wpis usunięty');

        return $this->redirect($this->generateUrl('presence_list'));
    }

    /**
     * @Route("/presence/for-user/{userId}", name="presence_show")
     */
    public function showAction($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $presences = $this->getDoctrine()
            ->getRepository(Presence::class)
            ->findBy(['user' => $user->getId()]);

        return $this->render('presence/index.html.twig', [
            'user' => $user,
            'presences' => $presences
        ]);
    }

    /**
     * @Route("/presence/for-date/{date}", name="presence_show_date")
     */
    public function showDateAction($date)
    {
        $presences = $this->getDoctrine()
            ->getRepository(Presence::class)
            ->findBy(['date' => new \DateTime($date)]);

        return $this->render('presence/by-date.html.twig', [
            'date' => $date,
            'presences' => $presences
        ]);
    }
}
