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
       echo "Rest API call...!";
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $errors = $validator->validate($user);
            if (count($errors) > 0) {    
                $errorsString = (string) $errors;
                //return new Response($errorsString);
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
            2 //limit per page
        );

        return $this->render('users.html.twig', array(
            "userlist" => $pagination,
            "pagination" => $pagination
        ));
    }

    /**
     * Matches /users/edit/{id}
     *
     * @Route("/users/edit/{id}")
     * @Method("GET")
     * Method will used for editing the user
     */

    public function editUserAction(string $id=null, Request $request)
    {
        $user = new User();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository(User::class);

        $educationArray = [            
            'UG College' => 'UG College',
            'PG College' => 'PG College',
            'Masters College' => 'Masters College',
            'Others College' => 'Others College',
        ];

        if($id != null) {
            $users = $repository->findBy(['id' => $id]);
            $users = $users[0]; 
        
            $userEmailString  = implode(",",$users->getUserEmail());
            $userMobileString = implode(",",$users->getUserMobileNumber());
            $this->userEduArray = $users->getUserEducation();

            $form = $this->createFormBuilder($user)
                ->add('userFirstName', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $users->getUserFirstName()
                ))
                ->add('userLastName', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $users->getUserLastName()
                ))
                ->add('userEmail', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $userEmailString
                ))
                ->add('userMobileNumber', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $userMobileString
                ))
                ->add('userDateofBirth', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $users->getUserDateofBirth()
                ))
                ->add('userEducation',ChoiceType::class,array(
                    'choices' => $educationArray,
                    'choice_attr' => function($educationArray, $key, $value) {
                        $selectVal = "";
                        if(in_array($key, $this->userEduArray)){
                            $selectVal = "selected";
                        }
                        return ['selected' => $selectVal];
                    },
                    'multiple' => true, 'attr' => array('class' => 'form-control'), 
                ))
                ->add('userBloodGroup', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $users->getUserBloodGroup()
                ))
                ->add('userGender', TextType::class, array(
                    'constraints' => new NotBlank(), 'attr' => array('class' => 'form-control'), 'data' => $users->getUserGender()
                ))
                ->add('id', HiddenType::class, array(
                    'data' => $id,
                ))
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $dm = $this->get('doctrine_mongodb')->getManager();
                $user = $dm->getRepository(User::class)->find($data->getId());

                $userEmailArray  = explode(",",$data->getUserEmail());
                $userMobileArray = explode(",",$data->getUserMobileNumber());

                $user->setUserFirstName($data->getUserFirstName());
                $user->setUserLastName($data->getUserLastName());
                $user->setUserEmail($userEmailArray);
                $user->setUserMobileNumber($userMobileArray);
                $user->setUserDateofBirth($data->getUserDateofBirth());
                $user->setUserEducation($data->getUserEducation());
                $user->setUserBloodGroup($data->getUserBloodGroup());
                $user->setUserGender($data->getUserGender());
                $dm->flush();

                return $this->redirectToRoute('users');
            }
        }

        return $this->render('usersEditForm.html.twig', array(
            'form' => $form->createView(),
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
