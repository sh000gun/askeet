<?php

namespace App\Controller;

use App\Entity\UserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdministratorController extends AbstractController
{
    /**
     * @Route("administrator/moderatorCandidates", name="administrator_moderatorCandidates")
     */
    public function moderatorCandidates(Request $request)
    {
        $candidates = UserQuery::getModeratorCandidates();

        return $this->render('user/usersSuccess.html.twig', [
            'user_pager' => $candidates,
        ]);
    }

    /**
     * @Route("administrator/moderators", name="administrator_moderators")
     */
    public function moderators(Request $request)
    {
        $moderators = UserQuery::getModerators();

        return $this->render('user/usersSuccess.html.twig', [
            'user_pager' => $moderators,
        ]);
    }

    /**
     * @Route("administrator/administrators", name="administrator_administrators")
     */
    public function administrators(Request $request)
    {
        $administrators = UserQuery::getAdministrators();

        return $this->render('user/usersSuccess.html.twig', [
            'user_pager' => $administrators,
        ]);
    }

    /**
     * @Route("administrator/problematicUsers", name="administrator_problematicUsers")
     */
    public function problematicUsers(Request $request)
    {
        $problematicUsers = UserQuery::getProblematicUsers();

        return $this->render('user/usersSuccess.html.twig', [
            'user_pager' => $problematicUsers,
        ]);
    }

    /**
     * @Route("administrator/delete/{userId}", name="administrator_delete")
     */
    public function deleteUser(Request $request, $userId)
    {
        if (UserQuery::deleteUser($userId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

    /**
     * @Route("administrator/refuseModerator/{userId}", name="administrator_refuseModerator")
     */
    public function refuseModerator(Request $request, $userId)
    {
        if (UserQuery::refuseModerator($userId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

    /**
     * @Route("administrator/grantModerator/{userId}", name="administrator_grantModerator")
     */
    public function grantModerator(Request $request, $userId)
    {
        if (UserQuery::grantModerator($userId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

    /**
     * @Route("administrator/grantAdministrator/{userId}", name="administrator_grantAdministrator")
     */
    public function grantAdministrator(Request $request, $userId)
    {
        if (UserQuery::grantAdministrator($userId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

}
