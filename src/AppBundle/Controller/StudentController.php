<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

class StudentController extends Controller
{
    
    /**
     * @Rest\Post(
     *    path = "/students",
     *    name = "app_students_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\Student>", converter="fos_rest.request_body")
     */
    public function createAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $student)
        {
            $student->setRole('Student');
            $em->persist($student);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Get(
     *    path = "/students/{id}",
     *    name = "app_students_get",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getAction(Student $students)
    {
        $data = $this->get('jms_serializer')->serialize($students, 'json', SerializationContext::create()->setGroups(array('get_student','TrainingClass')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/students", name="app_students_list")
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function listAction()
    {
        $students = $this->getDoctrine()->getRepository('AppBundle:Student')->findAll();
        
        $data = $this->get('jms_serializer')->serialize($students, 'json', SerializationContext::create()->setGroups(array('get_student','TrainingClass')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get(
     *    path = "/studentsmarks/{id}",
     *    name = "app_students_get",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function studentMarksAction(Student $students)
    {
        $data = $this->get('jms_serializer')->serialize($students, 'json', SerializationContext::create()->setGroups(array('get_student','marks','TrainingClass')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Options("/students", name="app_eleve_options")
     * 
     * @rest\View(StatusCode = 200)
     */
    public function optionAction(){
        return true;
    }
}

?>
