<?php

namespace App\Controller;

use App\Entity\Interest;
use App\Entity\ReportAnswer;
use App\Entity\ReportQuestion;
use App\Entity\User;
use App\Entity\UserQuery;
use App\Form\Type\LoginType;
use App\Form\Type\ResetPasswordType;
use App\Lib\myLoginValidator;
use App\Lib\myResetPasswordValidator;
use App\Security\AskeetPasswordEncoder;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    private $userProvider;
    private $encoderFactory;

    public function __construct(UserProviderInterface $userProvider, EncoderFactoryInterface $encoderFactory)
    {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @Route("/user/embed", name="user_embedLogin")
     */
    public function embedLogin($request)
    {
        $data = new myLoginValidator();
        $data->_target_path = $this->generateUrl('question_list');

        // set page request
        $pageRequest = $request->getPathInfo();

        if (null != $pageRequest && $pageRequest != $this->generateUrl('user_login')) {
            $data->_target_path = $pageRequest;
        }

        $form = $this->createForm(LoginType::class, $data);

        return $this->render('user/embedLoginSuccess.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/login", name="user_login")
     */
    public function login(Request $request)
    {
        $data = new myLoginValidator();
        $data->_target_path = $this->generateUrl('question_list');

        // set referer
        $referer = $request->headers->get('referer');

        if (null != $referer && $referer != $this->generateUrl('user_login')) {
            $data->_target_path = $request->headers->get('referer');
        }

        $form = $this->createForm(LoginType::class, $data);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // handle the form submission
                $nickname = $data->nickname;
                $password = $data->password;

                $token = new UsernamePasswordToken($nickname, $password, 'main', ['ROLE_SUBSCRIBER']);

                /*$encoderFactory = new EncoderFactory([
                    User::class => new AskeetPasswordEncoder(),
                ]);*/

                $authenticationManager = new AuthenticationProviderManager([
                    new DaoAuthenticationProvider(
                        $this->userProvider,
                        new UserChecker(),
                        'main',
                        $this->encoderFactory
                    ),
                ]);

                try {
                    $authenticatedToken = $authenticationManager
                        ->authenticate($token);
                } catch (BadCredentialsException $exception) {
                    $form->addError(new FormError($exception->getMessage()));

                    return $this->render('user/loginSuccess.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $this->get('security.token_storage')->setToken($authenticatedToken);

                return $this->redirect($data->_target_path);
            }
        }

        return $this->render('user/loginSuccess.html.twig', [
            'form' => $form->createView(),
        ]);
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
     * @Route("/user/show/{nickname}", name="user_show")
     */
    public function show($nickname)
    {
        $subscriber = UserQuery::retrieveByNickname($nickname);

        $interests = $subscriber->getInterestsJoinQuestion();
        $answers = $subscriber->getAnswersJoinQuestion();
        $questions = $subscriber->getQuestions();

        return $this->render(
            'user/showSuccess.html.twig',
            ['subscriber' => $subscriber,
            'interests' => $interests,
            'answers' => $answers,
            'questions' => $questions,
        ]
        );
    }

    /**
     * @Route("/user/interested/{questionId}", name="user_interested")
     */
    public function interested($questionId)
    {
        $userId = $this->getUser()->getId();

        $interest = new Interest();
        $interest->setQuestionId($questionId);
        $interest->setUserId($userId);
        $interest->save();

        $data = [
            'interestedUsers' => $interest->getQuestion()->getInterestedUsers(),
        ];

        $response = new Response(json_encode($data));

        return $response;
    }

    /**
     * @Route("/user/resetPassword", name="user_resetPassword")
     */
    public function resetPassword(Request $request, MailerInterface $mailer, Packages $assetPackage, TranslatorInterface $translator)
    {
        $data = new myResetPasswordValidator();

        $form = $this->createForm(ResetPasswordType::class, $data);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $resetEmail = $data->email;

                $user = UserQuery::create()
                    ->filterByEmail($resetEmail)
                    ->findOne();
                if ($user) {
                    // reset password
                    $newPassword = substr(rand(100000, 999999), 0, 6);
                    $user->setPassword($newPassword);

                    // send email
                    $subject = 'Askeet password recovery';
                    $email = (new TemplatedEmail())
                        ->from(Address::fromString('Askeet <askeet@symfony-project.com>'))
                        ->to(new Address($resetEmail))
                        ->subject($subject)
                    // path of the Twig template to render
                        ->htmlTemplate('email/resetPassword.html.twig')
                    // pass variables (name => value) to the template
                        ->context([
                            'subject' => $subject,
                            'nickname' => $user->getNickname(),
                            'password' => $newPassword,
                        ]);

                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        // TODO temporary
                        $email->htmlTemplate('email/resetPassword.altbody.html.twig');
                        try {
                            $mailer->send($email);
                        } catch (TransportExceptionInterface $e) {
                            return $this->redirectToRoute('question_list');
                        }
                    }

                    // save new password into database
                    $user->save();

                    return $this->redirectToRoute('user_login');
                } else {
                    $form->addError(new FormError($translator->trans('resetPassword.email.notfound')));
                }
            }
        }

        return $this->render(
            'user/resetPasswordSuccess.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/user/report_question/{questionId}", name="user_report_question")
     */
    public function reportQuestion($questionId)
    {
        try {
            $userId = $this->getUser()->getId();

            $reportQuestion = new ReportQuestion();
            $reportQuestion->setQuestionId($questionId);
            $reportQuestion->setUserId($userId);
            $reportQuestion->save();

            $response = new Response('success');

            return $response;
        } catch (PropelException $e) {
            $response = new Response('failed');

            return $response;
        }
    }

    /**
     * @Route("/user/report_answer/{answerId}", name="user_report_answer")
     */
    public function reportAnswer($answerId)
    {
        try {
            $userId = $this->getUser()->getId();

            $reportAnswer = new ReportAnswer();
            $reportAnswer->setAnswerId($answerId);
            $reportAnswer->setUserId($userId);
            $reportAnswer->save();

            $response = new Response('success');

            return $response;
        } catch (PropelException $e) {
            $response = new Response('failed');

            return $response;
        }
    }

    /**
     * @Route("/user/request_moderator", name="user_request_moderator")
     */
    public function requestModerator(Request $request)
    {
        try {
            $user = $this->getUser();
            $user->setIsModerator(1);
            $user->save();

            $response = new Response('success');

            return $response;
        } catch (PropelException $e) {
            $response = new Response('failed');

            return $response;
        }
    }

}
