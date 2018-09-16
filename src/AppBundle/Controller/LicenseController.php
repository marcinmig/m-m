<?php

namespace AppBundle\Controller;

use AppBundle\Entity\License;
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

class LicenseController extends Controller
{
    /**
     * @Route("/license", name="license_list")
     */
    public function indexAction()
    {
        $licenses = $this->getDoctrine()
            ->getRepository(License::class)
            ->findAll();

        return $this->render('license/index.html.twig', [
            'licenses' => $licenses
        ]);
    }

    /**
     * @Route("/license/create", name="license_create")
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $license = new License();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $invoices = $this->getDoctrine()->getRepository(Invoice::class)->findAll();

        $form = $this->createFormBuilder($license)
            ->add('name', TextType::class, ['label' => 'Nazwa/Opis'])
            ->add('serialNumber', TextType::class, ['label' => 'Klucz seryjny'])
            ->add('purchaseDate', DateType::class, ['label' => 'Data zakupu'])
            ->add('supportExpirationDate', DateType::class, ['label' => 'Data wygaśnięcia wsparcia'])
            ->add('expirationDate', DateType::class, ['label' => 'Data wygaśnięcia licencji'])
            ->add('notes', TextareaType::class, ['label' => 'Notatki', 'required' => false])
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
            $license = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($license);
            $em->flush();
            $this->addFlash('success', 'Licencja dodana');
            return $this->redirectToRoute('license_show', ['licenseId' => $license->getId()]);
        }

        return $this->render('license/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/license/{licenseId}/edit", name="license_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $licenseId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $license = $this->getDoctrine()
            ->getRepository(License::class)
            ->find($licenseId);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $invoices = $this->getDoctrine()->getRepository(Invoice::class)->findAll();

        $form = $this->createFormBuilder($license)
            ->add('name', TextType::class, ['label' => 'Nazwa/Opis'])
            ->add('serialNumber', TextType::class, ['label' => 'Klucz seryjny'])
            ->add('purchaseDate', DateType::class, ['label' => 'Data zakupu'])
            ->add('supportExpirationDate', DateType::class, ['label' => 'Data wygaśnięcia wsparcia'])
            ->add('expirationDate', DateType::class, ['label' => 'Data wygaśnięcia licencji'])
            ->add('notes', TextareaType::class, ['label' => 'Notatki', 'required' => false])
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
            $license = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($license);
            $em->flush();
            $this->addFlash('success', 'Zmiany zapisane');
        }

        return $this->render('license/edit.html.twig', [
            'form' => $form->createView(),
            'license' => $license
        ]);
    }

    /**
     * @Route("/license/{licenseId}/delete", name="license_delete", methods={"POST"})
     */
    public function deleteAction($licenseId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $license = $this->getDoctrine()->getRepository(License::class)->find($licenseId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($license);
        $em->flush();
        $this->addFlash('success', 'Sprzęt usunięty');

        return $this->redirect($this->generateUrl('license_list'));
    }

    /**
     * @Route("/license/{licenseId}", name="license_show")
     */
    public function showAction($licenseId)
    {
        $license = $this->getDoctrine()
            ->getRepository(License::class)
            ->find($licenseId);

        return $this->render('license/show.html.twig', [
            'license' => $license
        ]);
    }
}
