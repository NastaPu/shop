<?php
namespace frontend\controllers;

use frontend\services\ContactService;
use frontend\services\PasswordResetService;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\LoginForm;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use frontend\forms\ContactForm;
use frontend\services\SignupService;
use common\entities\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private $contactService;
    private $passwordResetService;

   /* public function __construct(
        $id,
        $module,
        ContactService $contactService,
        PasswordResetService $passwordResetService,
        $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->contactService = $contactService;
        $this->passwordResetService = $passwordResetService;
    }*/

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                (new ContactService())->send($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
               // Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $form,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $user = (new SignupService())->signup($form);
            if(Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        }
        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                (new passwordResetService())->request($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $form,
        ]);
    }

    public function actionResetPassword($token)
    {
        var_dump($token);
       /* $service = new PasswordResetService();
        try {
            $service->validateToken($token);
        } catch(\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }*/
        $form = new ResetPasswordForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                (new passwordResetService())->reset($token, $form);
                Yii::$app->session->setFlash('success', 'New password saved.');
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
            }
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $form,
            'token' => $token
        ]);
    }
}
