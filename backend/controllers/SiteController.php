<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\db\Query;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => ['login', 'error'],
                        'actions' => ['login', 'error', '*', 'gettables', 'getfields', 'getdata'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'gettables', 'getfields', 'getdata'],
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
            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
            ]
        ];  

        return $behaviors;
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
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

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Return the database table list.
     *
     * @return string
     */
    public function actionGettables()
    {
        //if (Yii::$app->request->isAjax) {
            $connection = Yii::$app->get('db2');;
            $command = $connection->createCommand("show tables");
            
            $result = $command->queryAll();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $result;
        //}
    }

    public function actionGetfields()
    {
        $tables_fields = [];

        //if (Yii::$app->request->isAjax) {
            $req = Yii::$app->request->post();

            foreach($req['table_list'] as $table){
                $connection = Yii::$app->get('db2');
                $command = $connection->createCommand("DESC ".$table);
                
                $result = $command->queryAll();
                // foreach($result as $res){
                //     $field_array = explode('_', $res['Field']);
                //     if(sizeOf($field_array) == 4 && $field_array[1] == 'fk')
                //         $related_table[] = ['related_table' => $field_array[2], 'related_field' => $field_array[2].'_pk_'.$field_array[3]];
                // }

                $tables_fields[] = array_merge(array('table_name' => $table), array('fields' => $result) /*, array('relations' => $related_table)*/);
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $tables_fields;
        //}
    }

    public function actionGetdata()
    {
        $data = [];
        

        //if (Yii::$app->request->isAjax) {
            $req = Yii::$app->request->post();
            //var_dump($req);
            foreach($req['table_list'] as $table){
                $tables_data = [];
                $query_fields = [];
                $query_tables = [];
                $query_constraints = [];
                $rows = new Query;
                 
                if( $table[0] != '-- SELECCIONE --' ){
                    $query_tables[] = $table[0];
                    if(sizeOf($table[1]) == 0)
                        $query_fields[] = '*';
                    else if(sizeOf($table[1]) > 0){
                        foreach($table[1] as $field){
                            $query_fields[] = $table[0].'.'.$field;
                        }
                    }
                    if(isset($table[2])){
                        foreach($table[2] as $constraint){
                            if($constraint[1] != -1 && $constraint[2] != '')
                                $query_constraints[] = $table[0].'.'.$constraint[0] .' '. $constraint[1] .' "'. $constraint[2].'"';
                        }                
                    }
                }

                $hasFields = false;
                $hasTables = false;
                if(sizeOf($query_fields) > 1){
                    $rows->select(implode(' , ',$query_fields));
                    $hasFields = true;
                }
                else if(sizeOf($query_fields) == 1){
                    $rows->select( $query_fields[0] );
                    $hasFields = true;
                }
                
                if(sizeOf($query_tables) > 1){
                    $rows->from(implode(' , ',$query_tables));
                    $hasTables = true;
                }
                else if(sizeOf($query_tables) == 1){
                    $rows->from( $query_tables[0] );
                    $hasTables = true;
                }

                if(sizeOf($query_constraints) > 1){
                    $rows->where(implode(' and ',$query_constraints));
                }
                else if(sizeOf($query_constraints) == 1){
                    $rows->where( $query_constraints[0] );
                }
                if($hasFields && $hasTables)
                    $data[] = $rows->all(\Yii::$app->db2);
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $data;

            if(sizeof($req['table_list']) > 0){
                $hasFields = false;
                $hasTables = false;
                if(sizeOf($query_fields) > 1){
                    $rows->select(implode(' , ',$query_fields));
                    $hasFields = true;
                }
                else if(sizeOf($query_fields) == 1){
                    $rows->select( $query_fields[0] );
                    $hasFields = true;
                }
                
                if(sizeOf($query_tables) > 1){
                    $rows->from(implode(' , ',$query_tables));
                    $hasTables = true;
                }
                else if(sizeOf($query_tables) == 1){
                    $rows->from( $query_tables[0] );
                    $hasTables = true;
                }

                if(sizeOf($query_constraints) > 1){
                    $rows->where(implode(' and ',$query_constraints));
                }
                else if(sizeOf($query_constraints) == 1){
                    $rows->where( $query_constraints[0] );
                }
                if($hasFields && $hasTables)
                    $tables_data = $rows->all(\Yii::$app->db2);
            }
            else{
                $tables_data = 'No definió una consulta correctamente';
            }

            if($tables_data == []) $tables_data = 'No definió una consulta correctamente';

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $tables_data;
        //}
    }
}
