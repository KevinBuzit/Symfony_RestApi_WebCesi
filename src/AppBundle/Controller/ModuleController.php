<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Module;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

class ModuleController extends Controller
{
    /**
     * @Rest\Post(
     *    path = "/modules",
     *    name = "app_module_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\Module>", converter="fos_rest.request_body")
     */
    public function createAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $module)
        {
            $em->persist($module);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Patch(
     *    path = "/modules/{id}",
     *    name = "app_module_update",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function updateAction($id,Request $request) {
        $em = $this->getDoctrine()->getManager();
        $module = $em->getRepository('AppBundle:Module')->find($id);
        $remoteModule = json_decode($request->getContent(), true);
        
        $module->setTitle($remoteModule[0]['title']);
        $module->setContent($remoteModule[0]['content']);

        $em->persist($module);

        $em->flush();

        return $module;
    }

    /**
     * @Rest\Delete(
     *    path = "/module/{id}",
     *    name = "app_module_delete",
     *    requirements = {"id"="\d+"}
     * @Rest\View(StatusCode = 201)
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $module = $em->getRepository('AppBundle:Module')->find($id);

        $em->remove($module);
        $em->flush();
    }

    /**
     * @Rest\Get(
     *    path = "/modules/{id}",
     *    name = "app_module_get",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getAction(Module $module)
    {
        $data = $this->get('jms_serializer')->serialize($module, 'json', SerializationContext::create()->setGroups(array('get_module','teachers','get_teachers')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/modules", name="app_module_list")
     * 
     * @Rest\View(StatusCode = 200)
     */
    public function listAction()
    {
        $modules = $this->getDoctrine()->getRepository('AppBundle:Module')->findAll();
        
        $data = $this->get('jms_serializer')->serialize($modules, 'json', SerializationContext::create()->setGroups(array('get_module','teachers','get_teachers')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Post(
     *    path = "/addmark",
     *    name = "app_training_addmark"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\StudentModule>", converter="fos_rest.request_body")
     */
    public function addMarkAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $studentModule)
        {
            $studentModule->setIsValid(0);
            $em->persist($studentModule);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Patch(
     *    path = "/marks/valid",
     *    name = "app_training_validmark"
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function validMarkAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $remoteStutentModuleArray = json_decode($request->getContent(), true);

        foreach($remoteStutentModuleArray as $remoteStutentModule){
            $studentModule = $em->getRepository('AppBundle:StudentModule')->find(
                $em->getRepository('AppBundle:Module')->find($remoteStutentModule['module']['id']),
                $em->getRepository('AppBundle:Student')->find($remoteStutentModule['student']['id'])
            );
            $studentModule->setIsValid(1);
            $em->persist($studentModule);
        }

        $em->flush();
        return $remoteStutentModuleArray;
    }

    /**
     * @Rest\Post(
     *    path = "/addteacher",
     *    name = "app_training_addteacher"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("myarr", class="array<AppBundle\Entity\ModuleTeacher>", converter="fos_rest.request_body")
     */
    public function addTeacherAction(Array $myarr)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($myarr as $moduleTeacher)
        {
            $em->persist($moduleTeacher);
        }

        $em->flush();

        return $myarr;
    }

    /**
     * @Rest\Delete(
     *    path = "/deleteteacher",
     *    name = "app_training_deleteteacher"
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function updateTeacherAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $modulesTeacherRemote = json_decode($request->getContent(), true);

        $moduleTeacher = $em->getRepository('AppBundle:ModuleTeacher')->find(
            $em->getRepository('AppBundle:Module')->find($modulesTeacherRemote[0]['module']['id']),
            $em->getRepository('AppBundle:Student')->find($modulesTeacherRemote[0]['student']['id'])    
        );
        $em->remove($moduleTeacher);
        $em->fetch();
    }

    /**
     * @Rest\Options("/modules", name="app_module_options")
     * 
     * @rest\View(StatusCode = 200)
     */
    public function optionAction(){
        return true;
    }
}

?>
