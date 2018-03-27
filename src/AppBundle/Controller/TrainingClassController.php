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
     * @Rest\Get("/trainingclassmarks/{idModule}/{idTrainingClass}", 
     *    name="app_students_training_marks",
     *    requirements = {"id"="\d+"})
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function getTrainingClassMarksAction($idModule, $idTrainingClass)
    {
        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQuery(
            'SELECT s.id AS idStudent, s.name, s.firstname, m.note, m.comment, m.isRemedial, tc.code, tc.year, mo.title
             FROM AppBundle\Entity\TrainingClass tc
             JOIN tc.students s
             LEFT JOIN s.marks m
             LEFT JOIN m.module mo
             WHERE tc.id=?1 AND (mo.id=?2 OR mo.id IS NULL)');
        $query->setParameter(1, $idTrainingClass);
        $query->setParameter(2, $idModule);
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
