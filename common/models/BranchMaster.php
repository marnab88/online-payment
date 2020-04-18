<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "BranchMaster".
 *
 * @property int $Bid
 * @property int $BranchId
 * @property string $BranchName
 * @property string $Cluster
 * @property string $State
 * @property string $Entity
 * @property int $IsDelete
 * @property string $Ondate
 * @property string $UpdateDate
 */
class BranchMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BranchMaster';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BranchId'], 'required'],
            [['BranchId'], 'integer'],
            [['Ondate', 'UpdateDate', 'Cluster','State', 'Entity'], 'safe'],
            [['BranchName' ], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Bid' => 'Bid',
            'BranchId' => 'Branch ID',
            'BranchName' => 'Branch Name',
            'Cluster' => 'Cluster',
            'State' => 'State',
            'Entity' => 'Entity',
            'IsDelete' => 'Is Delete',
            'Ondate' => 'Ondate',
            'UpdateDate' => 'Update Date',
        ];
    }
}
