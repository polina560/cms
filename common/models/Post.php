<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "Post".
 *
 * @property int|null $user_id
 * @property string|null $title
 * @property string|null $text
 * @property int|null $post_category_id
 * @property int|null $status
 * @property string|null $image
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Post';
    }

    public UploadedFile|string|null $imageFile = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'post_category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['title', 'image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'post_category_id' => Yii::t('app', 'Post Category ID'),
            'status' => Yii::t('app', 'Status'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'imageFile' => Yii::t('app', 'Image'),

        ];
    }

    public function fields()
    {
        return [
            'title',
            'text',
            'post_category_id' => function($model) {
                $item = \common\models\PostCategory::find()->where(['id' => $model->post_category_id])->one();
                return $item->name;
            },
            'status' => function($model){
                $item = new \common\models\Status();
                return $item->getStatusName($model->status);
            },
            'image' => function($model){
                $public = Yii::getAlias('@public');
                return $public . $model->image;
            },
        ];
    }

    public function beforeValidate(): bool
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        return parent::beforeValidate();
    }

    public function beforeSave($insert): bool
    {
        if ($this->imageFile instanceof UploadedFile) {
            if (!$insert && !empty($this->image)) {
                // удалить старую
                $public = Yii::getAlias('@public');
                $oldImagePath = $public . $this->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // удаление файла
                }
            }
            $randomName = Yii::$app->security->generateRandomString(8);
            $public = Yii::getAlias('@public');
            $path = '/uploads/' . $randomName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($public . $path);
            $this->image = $path;
        }
        return parent::beforeSave($insert);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }



    public function getPostCategory()
    {
        return $this->hasOne(PostCategory::class(),['id' => 'category_id']);

    }

}
