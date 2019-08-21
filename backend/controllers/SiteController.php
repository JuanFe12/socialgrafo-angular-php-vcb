<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\db\Query;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
        return $behaviors;
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

    public function actionGetrelatedtables()
    {            
        $result = \Yii::$app->params['related_tables'];

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
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

    public function actionGetbasefields()
    {
        $tables_fields = [];
        $base_table = \Yii::$app->params['base_table'];
                $connection = Yii::$app->get('db2');
                $command = $connection->createCommand("DESC ".$base_table);
                
                $result = $command->queryAll();
                // foreach($result as $res){
                //     $field_array = explode('_', $res['Field']);
                //     if(sizeOf($field_array) == 4 && $field_array[1] == 'fk')
                //         $related_table[] = ['related_table' => $field_array[2], 'related_field' => $field_array[2].'_pk_'.$field_array[3]];
                // }

                $tables_fields[] = array_merge(array('table_name' => $base_table), array('fields' => $result) /*, array('relations' => $related_table)*/);
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

    public function actionGetdatafront()
    {
        //return Yii::$app->request->post();
        $req = Yii::$app->request->post();

        if($req == null) return 500;
        //return $req;
        if(!isset($req['constraint_list'])) return 501;
        if(!isset($req['select_list'])) return 502;
        if(!isset($req['joined'])) return 503;

        $data = [];
        $query_constraints = [];
        $constraints = $req['constraint_list'];
        $selects = $req['select_list'];

        $base_table = \Yii::$app->params['base_table'];
        $rows = new Query;

        //select
        //foreach ($selects as $key => $value) {
            $rows->select(implode(' , ',$selects));
            //$rows->distinct(true);
        //}

        //from
        $rows->from($base_table);

        //join
        foreach (\Yii::$app->params['related_tables'] as $key => $table){
            $rows->join(
                'INNER JOIN', 
                $table['table'], 
                $table['fk_field'].' = '.$table['table'].'.'.$table['field'].'');
        }

        //where
        foreach ($constraints as $key => $value) {
            $query_constraints[] = $value['table_field'].' '.$value['condition'].' '.$value['value'];
        }

        //return var_dump($rows);
        //$data[] = $rows->all(\Yii::$app->db2);

        /* */
        if( $req['joined'] == 'true' ){
            $rows->where(implode(' and ',$query_constraints));
            $data[] = $rows->all(\Yii::$app->db2);
        }
        else{
            foreach ($query_constraints as $key => $value) {
                $new_query = $rows;
                $new_query->where($value);
                $data[] = $new_query->all(\Yii::$app->db2);
            }
        }
        /** */
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;

        
    }
}
