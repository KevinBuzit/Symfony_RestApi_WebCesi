<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Administrator;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

class AuthentificationController extends Controller {
    /**
     * @Rest\Post(
     *      path="/login",
     *      name="app_user_login"
     * )
     * @Rest\View(StatusCode = 201)
     */
    public function login(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $login = $request->request->get('login');
        $password = $request->request->get('password');
        $password = hash('sha256', $password);

        $credentials = json_decode($request->getContent(), true);

        //Search administrator with this login
        //$user = $em->getRepository('AppBundle:Administrator')->findOneBy(['login' => $credentials[0]["login"]], ['password' => $credentials[0]["password"]]);
        $user = $em->getRepository('AppBundle:Administrator')->findOneBy(array("login" => $login, "password" => $password));

        // if null search teacher
        if(!$user) {
            $user = $em->getRepository('AppBundle:Teacher')->findOneBy(array("login" => $login, "password" => $password));
        }
        
        // if null search student
        if(!$user) {
            $user = $em->getRepository('AppBundle:Student')->findOneBy(array("login" => $login, "password" => $password));
        }

        // if not null return user
        if($user) {
            $data = $this->get('jms_serializer')->serialize($user, 'json', SerializationContext::create()->setGroups(array('get_login')));
            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            $array = array('success' => false);
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }
}
?>