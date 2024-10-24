<?php

namespace api\modules\v1\controllers;

use api\modules\v1\controllers\AppController;
use common\models\Post;
use common\models\Status;
use OpenApi\Attributes\Get;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class PostController extends AppController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authentificator' => ['except' => ['index']],

        ]);
    }

    public function actionIndex()
    {
        $id = $this->getParameterFromRequest('id');
        $user_id = $this->getParameterFromRequest('user_id');
        $category_id = $this->getParameterFromRequest('category_id');
        $first_item = $this->getParameterFromRequest('first_item');
        $item_count = $this->getParameterFromRequest('item_count');

        $query = Post::find();

        $query->andWhere(['status' => Status::published]);

        if($id != null) {
            $query->andWhere(['id' => $id]);
            return [
                'success' => false,
                'errors' => $query->one(),
            ];
        }
        if($user_id != null) {
            $query->andWhere(['user_id' => $user_id]);
        }
        if($category_id != null) {
            $query->andWhere(['category_id' => $category_id]);
        }


        return $this->returnSuccess([
            'posts' =>  $query->offset($first_item)->limit($item_count)->all()
        ]);

//        return $this->returnSuccess([
//            'posts' =>  Post::find()->where(['status' => Status::published])->all()
//        ]);
    }


    public function actionCreatePost()
    {

        $category_id = $this->getParameterFromRequest('category_id');
        $title = $this->getParameterFromRequest('title');
        $text = $this->getParameterFromRequest('text');
        $image = $this->getParameterFromRequest('image');

        if ( !$category_id) {
            return $this->returnError(['category_id' =>  'Поля не заполнены']);
        }
        if ( !$title) {
            return $this->returnError(['title' => 'Поля не заполнены']);
        }
        if ( !$text) {
            return $this->returnError(['text' => 'Поля не заполнены']);
        }


        $post = new Post();

        $user_id = Yii::$app->user->id;
        if($post->load(['post_category_id'=> $category_id, 'title'=>$title, 'text'=> $text, 'user_id' => $user_id],'') && $post->validate()){
            $post->save();

            return $this->returnSuccess([
                'posts' =>  $post]);
        }
        else {
            return [
                'success' => false,
                'errors' => $post->getErrors(),
            ];
        }


    }

    public function actionUpdatePost()
    {
//        $request =Yii::$app->request;
//        $params = $request->get();

        $id = $this->getParameterFromRequest('id');
        $category_id = $this->getParameterFromRequest('category_id');
        $title = $this->getParameterFromRequest('title');
        $text = $this->getParameterFromRequest('text');

        if ( !$id) {
            return $this->returnError(['id' => 'Поле id не заполнено']);
        }

        $user_id = Yii::$app->user->id;
        $post = Post::find()->where(['id' => $id]);
        if($post)
//        ,"user_id" => $user_id]);

        if($category_id){
            return  $this->returnError(['id' => 'Пост не найден']);
        }
        if($post->user_id != $user_id) {
            return  $this->returnError(['id' => 'Нет прав на редактирование']);
        }

        if($post->load( ['category_id'=> $category_id, 'title'=>$title, 'text'=> $text],'') && $post->validate()){
            $post->save;
            return $this->returnSuccess([
                'posts' =>  $post]);
        }
        else {
            return [
                'success' => false,
                'errors' => $post->getErrors(),
            ];
        }



    }



}