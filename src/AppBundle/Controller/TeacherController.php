<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Teacher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

class TeacherController extends Controller
{
    /**
     * @Rest\Post(
     *    path = "/teachers",
     *    name = "app_teacher_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\Teacher>", converter="fos_rest.request_body")
     */
    public function createAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $teacher)
        {
            $teacher->setRole('Teachers');
            $em->persist($teacher);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Patch(
     *    path = "/teachers/{id}",
     *    name = "app_teacher_update",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function updateAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $teacher = $em->getRepository('AppBundle:Teacher')->find($id);

        $teacherRemote = json_decode($request->getContent(), true);

        $teacher->setName($teacherRemote[0]['name']);
        $teacher->setFirstname($teacherRemote[0]['firstname']);
        $teacher->setLogin($teacherRemote[0]['login']);
        $teacher->setPassword($teacherRemote[0]['password']);
        $teacher->setRole($teacherRemote[0]['role']);

        $em->persist($teacher);
        $em->flush();

        return $teacher;
    }

    /**
     * @Rest\Delete(
     *    path = "/teachers/{id}",
     *    name = "app_teacher_delete",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $teacher = $em->getRepository('AppBundle:Teacher')->find($id);

        $em->remove($teacher);
        $em->flush();
    }

    /**
     * @Rest\Get(
     *    path = "/teachers/{id}",
     *    name = "app_teacher_get",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getAction(Teacher $teacher)
    {
        $data = $this->get('jms_serializer')->serialize($teacher, 'json', SerializationContext::create()->setGroups(array('get')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/teachers", name="app_teacher_list")
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function listAction()
    {
        $teachers = $this->getDoctrine()->getRepository('AppBundle:Teacher')->findAll();
        
        $data = $this->get('jms_serializer')->serialize($teachers, 'json', SerializationContext::create()->setGroups(array('get')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/teachersmodules/{id}", 
     *    name="app_teacher_module",
     *    requirements = {"id"="\d+"})
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function getMarksAction(Teacher $teacher)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $teacher->getId();
        
        $query = $em->createQuery(
            'SELECT t.id AS idTeacher, m.id AS idModule, m.title, m.content
             FROM AppBundle\Entity\Teacher t
             JOIN t.modules mt
             JOIN mt.modules m
             WHERE t.id=?1');
        $query->setParameter(1, $id);
        $result = $query->getResult();
        
        $data = $this->get('jms_serializer')->serialize($result, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Options("/teachers", name="app_enseignant_options")
     * 
     * @rest\View(StatusCode = 200)
     */
    public function optionAction(){
        return true;
    }
}

?>
