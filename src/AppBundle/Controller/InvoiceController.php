<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class InvoiceController extends Controller
{
    /**
     * @Route("/invoice", name="invoice_list")
     */
    public function indexAction()
    {
        $invoices = $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->findAll();

        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoices
        ]);
    }

    /**
     * @Route("/invoice/create", name="invoice_create")
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $invoice = new Invoice();
        $form = $this->createFormBuilder($invoice)
            ->add('invoiceNumber', TextType::class, ['label' => 'Numer faktury'])
            ->add('contractorName', TextType::class, ['label' => 'Nazwa kontrahenta'])
            ->add('contractorVatid',TextType::class, ['label' => 'VAT ID kontrahenta'])
            ->add('netValue', NumberType::class, ['label' => 'Kwota netto'])
            ->add('grossValue', NumberType::class, ['label' => 'Kwota brutto'])
            ->add('taxValue', NumberType::class, ['label' => 'Ktota podatku VAT'])
            ->add('date', DateType::class, ['label' => 'Data wystawienia'])
            ->add('scan', FileType::class, ['label' => 'Skan faktury (PDF)'])
            ->add('submit', SubmitType::class, ['label' => 'Dodaj'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoice = $form->getData();

            $scan = $invoice->getScan();
            $newFileName = $this->generateUniqueFileName().'.'.$scan->guessExtension();

            $scan->move($this->getParameter('scans_directory'), $newFileName);
            $invoice->setScan($newFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($invoice);
            $em->flush();
            $this->addFlash('success', 'Faktura dodana');
            return $this->redirectToRoute('invoice_show', ['invoiceId' => $invoice->getId()]);
        }

        return $this->render('invoice/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/invoice/{invoiceId}/edit", name="invoice_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $invoiceId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $invoice = $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->find($invoiceId);

        if ($invoice->getScan()) {
            $invoice->setScan(new File($this->getParameter('scans_directory') . '/' . $invoice->getScan()));
        }

        $form = $this->createFormBuilder($invoice)
            ->add('invoiceNumber', TextType::class, ['label' => 'Numer faktury'])
            ->add('contractorName', TextType::class, ['label' => 'Nazwa kontrahenta'])
            ->add('contractorVatid',TextType::class, ['label' => 'VAT ID kontrahenta'])
            ->add('netValue', NumberType::class, ['label' => 'Kwota netto'])
            ->add('grossValue', NumberType::class, ['label' => 'Kwota brutto'])
            ->add('taxValue', NumberType::class, ['label' => 'Kwtota podatku VAT'])
            ->add('date', DateType::class, ['label' => 'Data wystawienia'])
            ->add('scan', FileType::class, ['label' => 'Skan faktury (PDF)'])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoice = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($invoice);
            $em->flush();
            $this->addFlash('success', 'Zmiany zapisane');
        }

        return $this->render('invoice/edit.html.twig', [
            'form' => $form->createView(),
            'invoice' => $invoice
        ]);
    }

    /**
     * @Route("/invoice/{invoiceId}/delete", name="invoice_delete", methods={"POST"})
     */
    public function deleteAction($invoiceId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $invoice = $this->getDoctrine()->getRepository(Invoice::class)->find($invoiceId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();
        $this->addFlash('success', 'Faktura usunięta');

        return $this->redirect($this->generateUrl('invoice_list'));
    }

    /**
     * @Route("/invoice/{invoiceId}", name="invoice_show")
     */
    public function showAction($invoiceId)
    {
        $invoice = $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->find($invoiceId);
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
