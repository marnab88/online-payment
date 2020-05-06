<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout='common';

        $model = new User();
        
        
        
        
            // var_dump(Yii::$app->request->post());
            // die();
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at=date('Y-m-d H:i:s');
            $hash = Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->request->post()['User']['Password']);
            $model->Password=$hash;
           
            $model->Role=2;
            if($model->save()){
                $loginid = "SUD".$model->UserId;
                $user= User::find()->where(['UserId' => $model->UserId])->one();
                $user->LoginId= $loginid;
                $user->OnDate=date('Y-m-d H:i:s');
                // var_dump($user);    
                // die();
                if ($user->save()) {

                    Yii::$app->session->setFlash('success','Subadmin added successfully');
                    return $this->redirect('create');
                    
                }
                
                else{

                    Yii::$app->session->setFlash('error','something going wrong');

                }

            }


            
        }
        $type=array( "MSME" => "MSME", "MFI" => "MFI" ,"both" => "Both"  );
        if (Yii::$app->user->identity->Type == "MSME" && Yii::$app->user->identity->Role == 1) {
            $type=array( "MSME" => "MSME");
        } 
        if(Yii::$app->user->identity->Type == "MFI" && Yii::$app->user->identity->Role == 1) {
            $type=array( "MFI" => "MFI" );
      	}

        $details = User::find()->where(['IsDelete' => 0])->all();
        return $this->render('create', [
            'model' => $model,
            'details' => $details,
			'type'=>$type,
        ]);
    }
    public function actionEdit($id)
    {
        $this->layout='common';
        $type=array( "MSME" => "MSME", "MFI" => "MFI" ,"both" => "Both"  );

        $details = User::find()->where(['IsDelete' => 0])->all();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                 Yii::$app->session->setFlash('success','Subadmin Updated successfully');
                    return $this->redirect(['create']);
            }
            else
            {
                Yii::$app->session->setFlash('error','Somthing Went Wrong');
            }
            
        }

        return $this->render('edit', [
            'model' => $model,
            'details' => $details,
            'type'=>$type,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->UserId]);
        }

        return $this->render('update', [
            'model' => $model,
            
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        // if($model->save()){
        //                 // var_dump($model->getErrors());die();   
                Yii::$app->session->setFlash('success', "Subadmin deleted successfully");
        //     }else{

        //         //var_dump($model->getErrors());die();
        //          Yii::$app->session->setFlash('error', "There is some error!");
        //     }
        return $this->redirect(['create']);
    }
 
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
