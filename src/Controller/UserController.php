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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Validator\Constraints\NotBlank;


//use Symfony\Component\Form\Extension\Core\Type\HiddenType;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends AbstractController
{
    public function index()
    {
       echo "Rest API call...!";
    }

    /**
     * Matches /users/new
     *
     * @Route("/users/new")
     * @Method("POST")
     * Method will used for creating the new user from REST
     */

    public function newUserAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('userFirstName', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userLastName', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userEmail', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userMobileNumber', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userDateofBirth', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userEducation', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userBloodGroup', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('userGender', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = new User();               
            $user->setUserFirstName($data['userFirstName']);
            $user->setUserLastName($data['userLastName']);
            $user->setUserEmail($data['userEmail']);
            $user->setUserMobileNumber($data['userMobileNumber']);
            $user->setUserDateofBirth($data['userDateofBirth']);
            $user->setUserEducation($data['userEducation']);
            $user->setUserBloodGroup($data['userBloodGroup']);
            $user->setUserGender($data['userGender']);

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($user);
            $dm->flush();
        }

        return $this->render('users.html.twig', array(
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

    public function getManageUsersAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository(User::class);
        $users = $repository->findAll();        
        $dm->flush();

        return $this->render('rest/users.html.twig', array("userlist" => $users));
    }
}
