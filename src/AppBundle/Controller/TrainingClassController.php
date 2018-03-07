<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\TrainingClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

class TrainingClassController extends Controller
{
    /**
     * @Rest\Post(
     *    path = "/trainingclass",
     *    name = "app_trainingclass_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\TrainingClass>", converter="fos_rest.request_body")
     */
    public function createAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $promotion)
        {
            $em->persist($promotion);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Patch(
     *    path = "/trainings/classes/{id}",
     *    name = "app_trainingClasses_update",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function updateAction($id, Request $requset) {
        $em = $this->getDoctrine()->getManager();

        $class = $em->getRepository('AppBundle:TrainingClass')->find($id);
        $classRemote = json_decode($requset->getContent());
        $class->setCode($classRemote[0]['code']);
        $class->setYear($classRemote[0]['year']);
        
        $em->persist($class);
        $em->flush();

        return $class;
    }

    /**
     * @Rest\Delete(
     *    path = "/trainings/classes/{id}",
     *    name = "app_trainingClasses_delete",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $class = $em->getRepository('AppBundle:TrainingClass')->find($id);

        $em->remove($class);
        $em->flush();
    }

    /**
     * @Rest\Get(
     *    path = "/trainingclass/{id}",
     *    name = "app_trainingclass_get",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getAction(TrainingClass $trainingclass)
    {
        $data = $this->get('jms_serializer')->serialize($trainingclass, 'json', SerializationContext::create()->setGroups(array('get_trainingclass','Training','Students','id_student')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/trainingclass", name="app_trainingclass_list")
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function listAction()
    {
        $trainingclass = $this->getDoctrine()->getRepository('AppBundle:TrainingClass')->findAll();
        
        $data = $this->get('jms_serializer')->serialize($trainingclass, 'json', SerializationContext::create()->setGroups(array('get_trainingclass','Training','Students','id_student')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/trainingclassstudents/{id}", 
     *    name="app_students_marks",
     *    requirements = {"id"="\d+"})
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function getMarksAction(TrainingClass $trainingclass)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $trainingclass->getId();
        
        $query = $em->createQuery(
            'SELECT t.id AS idTraining, c.code, c.year, s.name, s.firstname, s.id AS idStudent
             FROM AppBundle\Entity\Training t
             JOIN t.trainingClass c
             JOIN c.students s
             WHERE c.id=?1');
        $query->setParameter(1, $id);
        $result = $query->getResult();
        
        $data = $this->get('jms_serializer')->serialize($result, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Options("/trainingclass", name="app_promotion_options")
     * 
     * @rest\View(StatusCode = 200)
     */
    public function optionAction(){
        return true;
    }
}

?>
