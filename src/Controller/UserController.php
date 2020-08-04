<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\UserQuery;

class UserController extends AbstractController
{ 
    /**
     * @Route("/user/login", name="user_login")
     */

    public function login(Request $request, ValidatorInterface $validator)
    {

      if ($request->getMethod() != 'POST')
      {
        // display the form
        return $this->render(
          'user/loginSuccess.html.twig',
        );
      }
      else 
      {
        // handle the form submission
        $nickname = $request->get('nickname');
        $password = $request->get('password');


        $input = ['nickname' => $nickname, 'password' => $password];

        $constraints = new Assert\Collection([
            'nickname' => [new Assert\Length(['min' => 2]), new Assert\NotBlank],
            'password' => [new Assert\notBlank],
        ]);

        $violations = $validator->validate($input, $constraints);

        if (count($violations) > 0) {

            $accessor = PropertyAccess::createPropertyAccessor();

            $errorMessages = [];

            foreach ($violations as $violation) {

                $accessor->setValue($errorMessages,
                    $violation->getPropertyPath(),
                    $violation->getMessage());
            }

            return $this->render('user/loginSuccess.html.twig',
              ['errorMessages' => $errorMessages,
               'nickname' => $nickname,]
            );
        } else {

        $user = UserQuery::create()
          ->filterByNickname($nickname)
          ->findOne();

        if ($user )
        {
          if (sha1($user->getSalt().$password) == $user->getSha1Password())
          {
            $token = new UsernamePasswordToken($nickname, $password, 'default', ['subscriber']);
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_default', serialize($token)); 
            $this->get('session')->set('user', $nickname);
          }
          else
          {
            $login_error = 'this account does not exist or you entered a wrong password';
            
            return $this->render('user/loginSuccess.html.twig',
              ['login_error' => $login_error,
               'failure_path' => $this->generateUrl('question_list'),
               'nickname' => $nickname,
            ]
            );

          }
        }
        
         return $this->redirect($request->get('referer') != 'user_login' ? $request->get('referer') : 'question_list' );

        }
      }
    }

    /**
     * @Route("/user/logout", name="user_logout")
     */
    public function logout()
    {
      $this->get('security.token_storage')->setToken();
      $this->get('session')->set('_security_default', null);
      $this->get('session')->invalidate();        
      
      return $this->redirectToRoute('question_list');
    }

     /**
     * @Route("/user/show/{id}", name="user_show", requirements={"page"="+d\"})
     */

    public function show($id)
    {
      $subscriber = UserQuery::create()
        ->findPK($id);
      $interests = $subscriber->getInterestsJoinQuestion();
      $answers   = $subscriber->getAnswersJoinQuestion();
      $questions = $subscriber->getQuestions();

      return $this->render('user/showSuccess.html.twig',
        array('subscriber' => $subscriber,
              'interests' => $interests,
              'answers' => $answers,
              'questions' => $questions
            )
            );
    }
}
