<?php
/*
 * Create User Management Systems using RESTFUL API
 * Author : Elangovan.Sundar
 */

namespace App\Controller\Rest;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends FOSRestController
{
    public function index()
    {
       echo "Rest API call...!";
    }

    /**
     * Matches /Rest/users/{id}
     * 
     * @Route("/Rest/users/{id}", requirements={"id"="\d+"})
     * @Method("GET")
     * Method will used for Showing the user based on ID for REST
     */
    public function getUserAction(string $id=null) 
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository(User::class);
        if($id != null)
            $Users = $repository->findBy(['id' => $id]); 
        else
            $Users = $repository->findAll();
        return View::create($Users, Response::HTTP_CREATED , []);
    }

    /**
     * Matches /Rest/users/new
     *
     * @Route("/Rest/users/new")
     * @Method("POST")
     * Method will used for creating the new user from REST
     */

    public function postUserAction(Request $request)
    {
        $user = new User();

        /* 
        //set the default user details

        $usrEduDetArray = $usrEmailArray = $usrMobileArray = [];
        
        $usrEduDetArray = [
                            "UG"=>"UG college","PG"=>"PG college","Masters"=>"Masters College",
                            "Others"=> ["OtherCollege1","OtherCollege2"]
                          ];
        
        $usrEmailArray = ["leaf@gmail.com","yaan@gmail.com"];
        $usrMobileArray = ["1234567890","2345678901"];
        
        $user = new User();               
        $user->setUserFirstName("Elangovan");
        $user->setUserLastName("Sundar");
        $user->setUserEmail($usrEmailArray);
        $user->setUserMobileNumber($usrMobileArray);
        $user->setUserDateofBirth("21-05-1990");
        $user->setUserEducation($usrEduDetArray);
        $user->setUserBloodGroup("O+ve");
        $user->setUserGender("Male"); */

        $user->setUserFirstName($request->get('userFirstName'));
        $user->setUserLastName($request->get('userLastName'));
        $user->setUserEmail($request->get('userEmail'));
        $user->setUserMobileNumber($request->get('userMobileNumber'));
        $user->setUserDateofBirth($request->get('userDateofBirth'));
        $user->setUserEducation($request->get('userEducation'));
        $user->setUserBloodGroup($request->get('userBloodGroup'));
        $user->setUserGender($request->get('userGender'));

        if (is_object($user) && (count(get_object_vars($user)) > 0))
        {            
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($user);
            $dm->flush();
        }
        return View::create($user, Response::HTTP_CREATED , []);   
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
