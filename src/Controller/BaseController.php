<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; 
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Contact;
use App\Form\AvisType;
use App\Entity\Avis;
use App\Form\CartedidentiteType;
use App\Form\CantineType;


 
class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('mattmattronaldo@gmail.com')
                ->subject($contact->getSujet())
                ->htmlTemplate('emails/email.html.twig')
                ->context([
                    'nom'=> $contact->getNom(),
                    'sujet'=> $contact->getSujet(),
                    'message'=> $contact->getMessage()
                ]);
                $contact->setDateEnvoi(new \Datetime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
              
                $mailer->send($email);
                $this->addFlash('notice','Message envoyé, nous avons bien reçu votre message :)');
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

 
    #[Route('/apropos', name: 'apropos')]
    public function apropos(Request $request): Response
    {
        $avis = new avis();
        $form = $this->createForm(AvisType::class, $avis);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $avis->getnom();
                $avis->getemail();
                $avis->getmessage();
                $avis->getnote();
                $avis->setDateEnvoi(new \Datetime());

                $em = $this->getDoctrine()->getManager();
                $em->persist($avis);
                $em->flush();

                $this->addFlash('notice','Message envoyé, merci pour votre avis :)');
                return $this->redirectToRoute('index');
            }
        }
        return $this->render('base/apropos.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/cartesdidentite', name: 'cartesdidentite')]
    public function cartesdidentite(Request $request): Response
    {
        $form = $this->createForm(CartedidentiteType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $this->addFlash('notice','Message envoyé, vos informations ont bien était pris en compte :)');
                return $this->redirectToRoute('index');
            }
        }
        return $this->render('base/cartesdidentite.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ecoles', name: 'ecoles')]
    public function ecoles(Request $request): Response

    {
        $form = $this->createForm(CantineType::class);
       
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $this->addFlash('notice','Message envoyé, la réservation a bien était pris en compte :)');
                return $this->redirectToRoute('ecoles');

            }
        }
        return $this->render('base/ecoles.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/loisirs', name: 'loisirs')]
    public function loisirs(): Response
    {
        return $this->render('base/loisirs.html.twig', [
        ]);
    }

    #[Route('/mentionslegales', name: 'mentionslegales')]
    public function mentionslegales(): Response
    {
        return $this->render('base/mentionslegales.html.twig', [
        ]);
    }

    #[Route('/monuments', name: 'monuments')]
    public function monuments(): Response
    {
        return $this->render('base/monuments.html.twig', [
        ]);
    }

    #[Route('/liste-contacts', name: 'liste-contacts')]
    public function listecontacts(): Response
    {
        return $this->render('contacts/liste-contacts.html.twig', [
        ]);
    }
}
