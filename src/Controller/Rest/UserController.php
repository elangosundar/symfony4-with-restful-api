<?php

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


#class UserController extends FOSRestController
class UserController extends Controller
{
    public function index()
    {
       echo "Rest API call...!";
    }

    public function getUserAction()
    {
        echo "get functions loops===>";
        exit;
        /* $repository = $this->getDoctrine()->getRepository(User::class);
        
        // query for a single Product by its primary key (usually "id")
        $user = $repository->findall();
        
        return View::create($user, Response::HTTP_OK , []); */
    }

    public function getAllUsersAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository(User::class);
        $Users = $repository->findAll();        
        $dm->flush();

        //return new Response('OK');  
        return $this->render('rest/users.html.twig', array("userlist" => $Users));
        
        //return View::create($user, Response::HTTP_OK , []);
    }

    public function newUserAction()
    {
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
        $user->setUserGender("Male");
   
        $dm = $this->get('doctrine_mongodb')->getManager();
             
        $dm->persist($user);
        $dm->flush();

        return new Response( 'ok' );
    }
}
