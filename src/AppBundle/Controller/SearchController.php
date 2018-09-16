<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\License;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->query->get('query');

        if (strlen($query) < 3) {
            $this->addFlash('error', 'Wyszukiwana fraza nie może być krótsza niż 3 znaki');
            return $this->render('search.html.twig', []);
        }

        $users = $this->getDoctrine()->getRepository(User::class)->search($query);
        $invoices = $this->getDoctrine()->getRepository(Invoice::class)->search($query);
        $devices = $this->getDoctrine()->getRepository(Device::class)->search($query);
        $licenses = $this->getDoctrine()->getRepository(License::class)->search($query);

        return $this->render('search.html.twig', [
            'users' => $users,
            'invoices' => $invoices,
            'devices' => $devices,
            'licenses' => $licenses,
        ]);
    }
}
