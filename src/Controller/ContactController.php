<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Contact ;
use App\Form\ContactType ;
use App\Repository\ContactRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="page_contact")
     */
    public function index(Request $request, ObjectManager $manager, ContactRepository $contactRepository)
    {
        $contact =  new Contact();
        $form = $this->createForm(ContactType::class, $contact) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($contact);
         $manager->flush();
         return $this->redirectToRoute('page_contact');
        }
        return $this->render('home/contact.html.twig' , [
            'formulaire'=> $form->createView(),
            'contacts' => $contactRepository->findAll()  ]) ;

            
            
       
}


 /**
     * @Route("/administrateur/contact", name="page_contact_admin")
     */
    public function contact(Request $request, ObjectManager $manager, ContactRepository $contactRepository)
    {
        $contact =  new Contact();
        $form = $this->createForm(ContactType::class, $contact) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($contact);
         $manager->flush();
         return $this->redirectToRoute('page_contact');
        }
        return $this->render('security/contact_admin.html.twig' , [
            'formulaire'=> $form->createView(),
            'contacts' => $contactRepository->findAll()  ]) ;

            
            
       
}


    /**
     * @Route("administrateur/contact/read/{id}", name="contactRead_page", methods="GET|POST")
     */
    public function editContact($id, Request $request, Contact $contact, ObjectManager $manager, ContactRepository $contactRepository)
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('page_contact', ['id' => $contact->getId()]);
        }
        
        return $this->render('security/contactRead.html.twig' , [
            'formulaire'=> $form->createView(),
            'contacts' => $contactRepository->find($id)
        ]);
        
    }

}
