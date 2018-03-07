<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Training;
use AppBundle\Entity\TrainingModule;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

class TrainingController extends Controller
{
    /**
     * @Rest\Post(
     *    path = "/trainings",
     *    name = "app_training_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\Training>", converter="fos_rest.request_body")
     */
    public function createAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $formation)
        {
            $em->persist($formation);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Patch(
     *    path = "/trainings/{id}",
     *    name = "app_training_update",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function updateAction($id, Request $requset) {
        $em = $this->getDoctrine()->getManager();

        $training = $em->getRepository('AppBundle:Training')->find($id);
        $trainingRemote = json_decode($requset->getContent());
        $training->setTitle($trainingRemote[0]['title']);
        $training->setCode($trainingRemote[0]['code']);
        
        $em->persist($training);
        $em->flush();

        return $training;
    }

    /**
     * @Rest\Delete(
     *    path = "/trainings/{id}",
     *    name = "app_training_delete",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $training = $em->getRepository('AppBundle:Training')->find($id);

        $em->remove($training);
        $em->flush();
    }


    /**
     * @Rest\Get(
     *    path = "/trainings/{id}",
     *    name = "app_training_get",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getAction(Training $training)
    {
        $data = $this->get('jms_serializer')->serialize($training, 'json', SerializationContext::create()->setGroups(array('get_training','modules','get_training')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/trainings", name="app_training_list")
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function listAction()
    {
        $trainings = $this->getDoctrine()->getRepository('AppBundle:Training')->findAll();
        
        $data = $this->get('jms_serializer')->serialize($trainings, 'json', SerializationContext::create()->setGroups(array('get_training','modules','get_training')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Post(
     *    path = "/addmodules",
     *    name = "app_training_addmodule"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\TrainingModule>", converter="fos_rest.request_body")
     */
    public function addModuleAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $trainingModule)
        {
            $em->persist($trainingModule);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Get("/trainingstudents/{id}", 
     *    name="app_training_students",
     *    requirements = {"id"="\d+"})
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function getMarksAction(Training $training)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $training->getId();
        
        $query = $em->createQuery(
            'SELECT s.id AS idStudent, mo.id AS idModule, mo.title AS moduleTitle, m.note, m.comment, m.isRemedial
             FROM AppBundle\Entity\Student s 
             JOIN s.marks m 
             JOIN m.module mo
             WHERE s.id=?1');
        $query->setParameter(1, $id);
        $result = $query->getResult();
        
        $data = $this->get('jms_serializer')->serialize($result, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Options("/trainings", name="app_formation_options")
     * 
     * @rest\View(StatusCode = 200)
     */
    public function optionAction(){
        return true;
    }
}

?>
