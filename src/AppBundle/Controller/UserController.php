<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;

class UserController extends FOSRestController
{
    /**
     * @ApiDoc(
     * description="Create a new Object",
     *  input="AppBundle\Form\Type\UserType",
     *  output="AppBundle\Entity\User"
     * )
     * @View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}
