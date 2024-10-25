<?php

namespace api\modules\v1\controllers;

use api\modules\v1\controllers\AppController;
use common\models\Post;
use common\models\Status;
use OpenApi\Attributes\Get;
use Yii;
use yii\data\ActiveDataProvider;
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
                'success' => true,
                'data' => $query->one(),
            ];
        }
        if($user_id != null) {
            $query->andWhere(['user_id' => $user_id]);
        }
        if($category_id != null) {
            $query->andWhere(['category_id' => $category_id]);
        }

        if($first_item != null){
            $query->offset($first_item - 1);
        }
        if($item_count != null){
            $query->limit($item_count);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 3
            ]
        ]);

        return $this->returnSuccess([
            'posts' => $provider
        ]);

//        return $this->returnSuccess([
//            'posts' =>  Post::find()->where(['status' => Status::published])->all()
//        ]);
    }


    public function actionCreatePost()
    {

        $category_id = (int)$this->getParameterFromRequest('category_id');
        $title = $this->getParameterFromRequest('title');
        $text = $this->getParameterFromRequest('text');
        $image = $this->getParameterFromRequest('image');

        if ( empty($category_id) ) {
            return $this->returnError(['category_id' =>  'Поле category_id не заполнено или заполнено некорректно']);
        }
       

        $post = new Post();

        $imagepath = null;

        if($image) {
            if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
                $image = substr($image, strpos($image, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    throw new \Exception('invalid image type');
                }
                $image = str_replace(' ', '+', $image);
                $image = base64_decode($image);

                if ($image === false) {
                    return $this->returnError(['image' => 'Поле заполнено не корректно']);
                }
            } else {
                return $this->returnError(['image' => 'Поле заполнено не корректно']);
            }

            $randomName = Yii::$app->security->generateRandomString(8);

            $public = Yii::getAlias('@public');
            $imagepath = '/uploads/' . $randomName . '.' . $type;


            file_put_contents($public.$imagepath, $image);

        }


        $user_id = Yii::$app->user->id;
        if($post->load(['post_category_id'=> $category_id, 'title'=>$title, 'text'=> $text, 'user_id' => $user_id, 'status' => 20, 'image' => $imagepath],'') && $post->validate()){

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

        $id = (int)$this->getParameterFromRequest('id');
        $category_id = (int)$this->getParameterFromRequest('category_id');
        $title = $this->getParameterFromRequest('title');
        $text = $this->getParameterFromRequest('text');

        if ( empty($id) ) {
            return $this->returnError(['id' => 'Поле id не заполнено или заполнено некорректно']);
        }

        $user_id = Yii::$app->user->id;
        $post = Post::findOne($id);

        if(!$post){
            return $this->returnError(['id' => 'Пост не найден']);
        }


        if($post->user_id != $user_id) {
            return  $this->returnError(['id' => 'Нет прав на редактирование']);
        }

        if(empty($category_id)){
            return $this->returnError(['category_id' => 'Поле category_id заполнено некорректно']);
        }
        else {
            $post->load(['post_category_id'=> $category_id], '');
        }


        if($post->load(['title'=>$title, 'text'=> $text],'') && $post->validate()){
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



    }public function actionDeletePost()
    {
//        $request =Yii::$app->request;
//        $params = $request->get();

        $id = $this->getParameterFromRequest('id');


        if ( !$id) {
            return $this->returnError(['id' => 'Поле id не заполнено']);
        }

        $user_id = Yii::$app->user->id;
        $post = Post::findOne($id);

        if(!$post){
            return $this->returnError(['id' => 'Пост не найден']);
        }

        if($post->user_id != $user_id) {
            return  $this->returnError(['id' => 'Нет прав на удаление поста']);
        }



        unset($post);
        return $this->returnSuccess(['id' => 'Пост успешно удален']);





    }



}