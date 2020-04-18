<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
/**
 * This is the model class for table "UploadRecords".
 *
 * @property int $RecordId
 * @property string $MonthYear
 * @property string $File
 * @property string $OnDate
 * @property string $UpdatedDate
 * @property int $UploadedBy
 * @property int $NoOfRecords
 * @property int $Cleared
 * @property int $Mismatch
 * @property int $IsDelete
 */
class UploadRecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $State;
    public $Cluster;
    public $BranchName;
    public $CurrentMonthDue;
     public $LatePenalty;
    
    public static function tableName()
    {
        return 'UploadRecords';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['MonthYear', 'File', 'OnDate', 'Type'], 'required'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['UploadedBy', 'NoOfRecords', 'Cleared','IsDelete'], 'integer'],
            [['MonthYear'], 'string', 'max' => 20],
            [['File'], 'string', 'max' => 50],
            //[['File'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx, doc, csv, pdf'],
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
            'RecordId' => 'Record ID',
            'MonthYear' => 'Month Year',
            'File' => 'File',
            'Type' => 'Type',
            'OnDate' => 'On Date',
            'UpdatedDate' => 'Updated Date',
            'UploadedBy' => 'Uploaded By',
            'NoOfRecords' => 'No Of Records',
            'Cleared' => 'Cleared',
            'Mismatch' => 'Mismatch',
            'IsDelete' => 'Is Delete',
        ];
    }
    
    
     public function getUser()
    {
        return $this->hasOne(User::className(), ['UserId' => 'UploadedBy']);
    }

    public function getExceldata()
    {
        return $this->hasMany(ExcelData::className(),['RecordId'=>'RecordId']);
    }
    public function getMsmeexceldata()
    {
        return $this->hasMany(MsmeExcelData::className(),['RecordId'=>'RecordId']);
    }
    
}

