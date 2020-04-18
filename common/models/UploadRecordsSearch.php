<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UploadRecords;

/**
 * UploadRecordsSearch represents the model behind the search form of `common\models\UploadRecords`.
 */
class UploadRecordsSearch extends UploadRecords
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['RecordId', 'UploadedBy', 'NoOfRecords', 'Cleared', 'Mismatch', 'IsDelete'], 'integer'],
            [['MonthYear', 'File', 'OnDate', 'UpdatedDate'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UploadRecords::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'RecordId' => $this->RecordId,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
            'UploadedBy' => $this->UploadedBy,
            'NoOfRecords' => $this->NoOfRecords,
            'Cleared' => $this->Cleared,
            'Mismatch' => $this->Mismatch,
            'IsDelete' => $this->IsDelete,
        ]);

        $query->andFilterWhere(['like', 'MonthYear', $this->MonthYear])
            ->andFilterWhere(['like', 'File', $this->File]);

        return $dataProvider;
    }
}
