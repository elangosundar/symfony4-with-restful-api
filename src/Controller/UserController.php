<?php
/*
 * Create User Management Systems
 * Author : Elangovan.Sundar
 */

namespace App\Controller;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;

use App\Form\UserType;

class UserController extends Controller
{
    public $userEduArray = [];
    
    public function index()
    {
       echo "Deafult Users call...!";
    }

    /**
     * Matches /users/new
     *
     * @Route("/users/new")
     * @Method("POST")
     * Method will used for creating the new user
     */

    public function newUserAction(Request $request, ValidatorInterface $validator)
    {
        $user = new User();
        $form = $this->createForm(UserType::Class,$user);   
        //$form->remove('userLastName');
        
        $form->handleRequest($request);
//echo "new user form loops";
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
echo "<pre>";print_R($user);exit;            
            $errors = $validator->validate($user);
            if (count($errors) > 0) {    
                $errorsString = (string) $errors;
            }else{
                $dm = $this->get('doctrine_mongodb')->getManager();
                $dm->persist($user);
                $dm->flush();
                return $this->redirectToRoute('users');
            }
        }

        return $this->render('usersAddForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Matches /users/edit/{id}
     *
     * @Route("/users/edit/{id}")
     * @Method("GET")
     * Method will used for editing the user
     */

    public function editUserAction($id, Request $request)
    {
        $user = new User();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository(User::class);
        $users = $repository->findBy(['id' => $id]);
        $form = $this->createForm(UserType::class, $users[0]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) { 
            $dm->flush();
            return $this->redirectToRoute('users');
        }
        return $this->render('usersAddForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Matches /users
     *
     * @Route("/users")
     * @Method("GET")
     * Method will used for showing the userlist
     */

    public function getManageUsersAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository(User::class);
        $users = $repository->findAll();
        $dm->flush();

        /* Pagination Functionality */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dm->getRepository(User::class)->findAll(), //query
            $request->query->getInt('page', 1), //default page
            6 //limit per page
        );

        return $this->render('users.html.twig', array(
            "userlist" => $pagination,
            "pagination" => $pagination
        ));
    }

    /**
     * Matches /users/delete/{id}
     *
     * @Route("/users/delete/{id}")
     * @Method("GET")
     * Method will used for delete the user
     */

    public function deleteUsersAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $dm->getRepository(User::class)->find($id);
        $dm->remove($user);
        $dm->flush();
        return $this->redirectToRoute('users');
    }
}
