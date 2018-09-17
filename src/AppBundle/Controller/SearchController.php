<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\License;
use AppBundle\Entity\Presence;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $form = $this->createFormBuilder([], ['method' => 'GET', 'csrf_protection' => false])
            ->add('query', TextType::class, ['label' => 'Fraza'])
            ->add('from', DateType::class, [
                'label' => 'Szukaj od',
                'required' => false,
                'empty_data' => null,
                'widget' => 'text'
            ])
            ->add('to', DateType::class, [
                'label' => 'Szukaj do',
                'required' => false,
                'empty_data' => null,
                'widget' => 'text'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Szukaj'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['query'];
            $from = $data['from'];
            $to = $data['to'];

            if (!$from) {
                $from = new \DateTime('-30 years');
            }
            if (!$to) {
                $to = new \DateTime('+30 years');
            }

            if (strlen($query) < 3) {
                $this->addFlash('error', 'Wyszukiwana fraza nie może być krótsza niż 3 znaki');
                return $this->render('search.html.twig', []);
            }

            $users = $this->getDoctrine()->getRepository(User::class)->search($query, $from, $to);
            $invoices = $this->getDoctrine()->getRepository(Invoice::class)->search($query, $from, $to);
            $devices = $this->getDoctrine()->getRepository(Device::class)->search($query, $from, $to);
            $licenses = $this->getDoctrine()->getRepository(License::class)->search($query, $from, $to);
            $presences = $this->getDoctrine()->getRepository(Presence::class)->search($query, $from, $to);

            return $this->render('search.html.twig', [
                'users' => $users,
                'invoices' => $invoices,
                'devices' => $devices,
                'licenses' => $licenses,
                'presences' => $presences,
                'form' => $form->createView()
            ]);
        }

        return $this->render('search.html.twig', ['form' => $form->createView()]);
    }
}
