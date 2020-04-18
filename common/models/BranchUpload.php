<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "BranchUpload".
 *
 * @property int $Id
 * @property string $File
 * @property string $UploadedBy
 * @property string $OnDate
 * @property string $UpdateDate
 * @property int $IsDelete
 */
class BranchUpload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BranchUpload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['File', 'UploadedBy'], 'required'],
            [['OnDate', 'UpdateDate'], 'safe'],
            [['IsDelete'], 'integer'],
            [['File'], 'string', 'max' => 100],
        ];
    }
public function imageUpload($image)
{
$im=explode(".", $image->name);
$ext = end($im);

if(strtolower($ext)=='pdf' || strtolower($ext)=='xlsx' || strtolower($ext)=='xlxs' || strtolower($ext)=='doc')
{

// the path to save file, you can set an uploadPath
// in Yii::$app->params (as used in example below)
$basepath=Yii::getAlias('@storage').'/uploads/';
Yii::$app->params['uploadPath'] = $basepath;
$ppp=Yii::$app->security->generateRandomString();
$path = Yii::$app->params['uploadPath'] . $ppp.".{$ext}";
$p=$ppp.".{$ext}";
$image->saveAs($path);

return $p;

}
}
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'File' => 'File',
            'UploadedBy' => 'Uploaded By',
            'OnDate' => 'On Date',
            'UpdateDate' => 'Update Date',
            'IsDelete' => 'Is Delete',
        ];
    }
}
