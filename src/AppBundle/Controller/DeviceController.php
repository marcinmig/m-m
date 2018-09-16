<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DeviceController extends Controller
{
    /**
     * @Route("/device", name="device_list")
     */
    public function indexAction()
    {
        $devices = $this->getDoctrine()
            ->getRepository(Device::class)
            ->findAll();

        return $this->render('device/index.html.twig', [
            'devices' => $devices
        ]);
    }

    /**
     * @Route("/device/create", name="device_create")
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $device = new Device();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $invoices = $this->getDoctrine()->getRepository(Invoice::class)->findAll();

        $form = $this->createFormBuilder($device)
            ->add('name', TextType::class, ['label' => 'Nazwa/Opis'])
            ->add('serialNumber', TextType::class, ['label' => 'Numer seryjny'])
            ->add('purchaseDate', DateType::class, ['label' => 'Data zakupu'])
            ->add('warrantyExpirationDate', DateType::class, ['label' => 'Data wygaśnięcia gwarancji'])
            ->add('netPrice', NumberType::class, ['label' => 'Kwota netto'])
            ->add('notes', TextareaType::class, ['label' => 'Notatki'])
            ->add('owner', ChoiceType::class, [
                'label' => 'Właściciel',
                'choices' => $users,
                'choice_label' => function (User $user) {
                    return $user->getUsername();
                },
                'choice_value' => function ($user) {
                    return $user ? $user->getId() : '';
                }
            ])
            ->add('invoice', ChoiceType::class, [
                'label' => 'Faktura',
                'choices' => $invoices,
                'choice_label' => function (Invoice $invoice) {
                    return $invoice->getInvoiceNumber() . '(' . $invoice->getContractorName() . ' ' . $invoice->getContractorVatid() . ')';
                },
                'choice_value' => function ($invoice) {
                    return $invoice ? $invoice->getId() : $invoice;
                }
            ])
            ->add('submit', SubmitType::class, ['label' => 'Dodaj'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($device);
            $em->flush();
            $this->addFlash('success', 'Urządzenie dodane');
            return $this->redirectToRoute('device_show', ['deviceId' => $device->getId()]);
        }

        return $this->render('device/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/device/{deviceId}/edit", name="device_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $deviceId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $device = $this->getDoctrine()
            ->getRepository(Device::class)
            ->find($deviceId);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $invoices = $this->getDoctrine()->getRepository(Invoice::class)->findAll();

        $form = $this->createFormBuilder($device)
            ->add('name', TextType::class, ['label' => 'Nazwa/Opis'])
            ->add('serialNumber', TextType::class, ['label' => 'Numer seryjny'])
            ->add('purchaseDate', DateType::class, ['label' => 'Data zakupu'])
            ->add('warrantyExpirationDate', DateType::class, ['label' => 'Data wygaśnięcia gwarancji'])
            ->add('netPrice', NumberType::class, ['label' => 'Kwota netto'])
            ->add('notes', TextareaType::class, ['label' => 'Notatki'])
            ->add('owner', ChoiceType::class, [
                'label' => 'Właściciel',
                'choices' => $users,
                'choice_label' => function (User $user) {
                    return $user->getUsername();
                },
                'choice_value' => function ($user) {
                    return $user ? $user->getId() : '';
                }
            ])
            ->add('invoice', ChoiceType::class, [
                'label' => 'Faktura',
                'choices' => $invoices,
                'choice_label' => function (Invoice $invoice) {
                    return $invoice->getInvoiceNumber() . '(' . $invoice->getContractorName() . ' ' . $invoice->getContractorVatid() . ')';
                },
                'choice_value' => function ($invoice) {
                    return $invoice ? $invoice->getId() : $invoice;
                }
            ])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($device);
            $em->flush();
            $this->addFlash('success', 'Zmiany zapisane');
        }

        return $this->render('device/edit.html.twig', [
            'form' => $form->createView(),
            'device' => $device
        ]);
    }

    /**
     * @Route("/device/{deviceId}/delete", name="device_delete", methods={"POST"})
     */
    public function deleteAction($deviceId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $device = $this->getDoctrine()->getRepository(Device::class)->find($deviceId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($device);
        $em->flush();
        $this->addFlash('success', 'Sprzęt usunięty');

        return $this->redirect($this->generateUrl('device_list'));
    }

    /**
     * @Route("/device/{deviceId}", name="device_show")
     */
    public function showAction($deviceId)
    {
        $device = $this->getDoctrine()
            ->getRepository(Device::class)
            ->find($deviceId);

        return $this->render('device/show.html.twig', [
            'device' => $device
        ]);
    }
}
