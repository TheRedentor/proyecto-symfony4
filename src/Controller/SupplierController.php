<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Supplier;
use App\Form\SupplierFormType;

class SupplierController extends AbstractController
{
    /**
    * @Route("/")
    */
    public function index(ManagerRegistry $doctrine): Response
    {
        $suppliers = $doctrine->getRepository(Supplier::class)->findAll();
        $numberOfSuppliers = Count($suppliers);
        return $this->render('supplier/index.html.twig', [
            'suppliers' => $suppliers,
            'numberOfSuppliers' => $numberOfSuppliers,
        ]);
    }

    public function create_form(ManagerRegistry $doctrine, Request $request): Response{
        $entityManager = $doctrine->getManager();

        $supplier = new Supplier();

        $form = $this->createForm(SupplierFormType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $supplier->setRegistered(new \DateTime());
            $supplier->setEdited(new \DateTime());
            try{
                $entityManager->persist($supplier);
                $entityManager->flush();
            }
            catch(\Exception $e){
                return $this->redirectToRoute('create_form');
            }
            return $this->redirectToRoute('index');
        }

        return $this->render('supplier/create_form.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }

    public function edit_form(ManagerRegistry $doctrine, int $id, Request $request): Response{
        $entityManager = $doctrine->getManager();

        $supplier = $doctrine->getRepository(Supplier::class)->find($id);

        if ($supplier != null){
            $form = $this->createForm(SupplierFormType::class, $supplier, array('method' => 'PUT'));

            try{
                $form->handleRequest($request);
            }
            catch(\Exception $e){
                return $this->redirectToRoute('edit_form', array('id' => $id));
            }
    
            if ($form->isSubmitted() && $form->isValid()){
                $supplier->setEdited(new \DateTime());
                $entityManager->persist($supplier);
                $entityManager->flush();
                return $this->redirectToRoute('index');;
            }
    
            return $this->render('supplier/edit_form.html.twig', [
                'edit_form' => $form->createView(),
            ]);
        }
        else{
            return $this->redirectToRoute('index');
        }
    }

    public function delete(ManagerRegistry $doctrine, int $id): Response{
        $entityManager = $doctrine->getManager();

        $supplier = $doctrine->getRepository(Supplier::class)->find($id);
        $entityManager->remove($supplier);
        $entityManager->flush();
        return $this->redirectToRoute('index');
    }
}
