<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends ActiveRecord
{
	public $verifyCode;
	private $connection;

	public function __construct()
	{
		$this->connection = Yii::$app->db;
	}

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone'], 'required'],

            ['name', 'match', 'pattern' => '/^[a-zA-Z ]+$/', 'message' => 'Invalid name'],
            ['name', 'unique', 'on' => 'insert'],
            ['name', 'unique', 'on' => 'update', 'filter' => ['!=', 'lead_id', Yii::$app->request->get('id')]],

            ['email', 'email'],
            ['email', 'unique', 'on' => 'insert'],
            ['email', 'unique', 'on' => 'update', 'filter' => ['!=', 'lead_id', Yii::$app->request->get('id')]],

	        ['phone', 'match', 'pattern' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Invalid phone'],
	        ['phone', 'unique', 'on' => 'insert'],
	        ['phone', 'unique', 'on' => 'update', 'filter' => ['!=', 'lead_id', Yii::$app->request->get('id')]],

            ['verifyCode', 'captcha'],
        ];
    }

	public function search($params, $where = null)
	{
		$query = ContactForm::find()->where($where);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pagesize' => 20,
			],
			'sort' => [
				'attributes' => ['name', 'email', 'phone'],
			],
		]);

		$this->load($params);

		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['like', 'email', $this->email]);
		$query->andFilterWhere(['like', 'phone', $this->phone]);

		return $dataProvider;
	}

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

	public static function tableName(){
		return 'lead';
	}

	public static function getDb()
	{
		return \Yii::$app->db;
	}
}
