<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace d3acc\models\base;

use Yii;
use yii\db\Exception;

/**
 * This is the base-model class for table "ac_tran_dim".
 *
 * @property integer $id
 * @property integer $dim_id
 * @property integer $tran_id
 * @property float $amt
 * @property string $notes
 *
 * @property \d3acc\models\AcDim $dim
 * @property \d3acc\models\AcTran $tran
 * @property string $aliasModel
 */
abstract class AcTranDim extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ac_tran_dim';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dim_id', 'tran_id'], 'required'],
            [['dim_id', 'tran_id'], 'integer'],
            [['amt'], 'number'],
            [['notes'], 'string'],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcDim::className(), 'targetAttribute' => ['dim_id' => 'id']],
            [['tran_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcTran::className(), 'targetAttribute' => ['tran_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('d3acc', 'ID'),
            'dim_id' => Yii::t('d3acc', 'Dimension'),
            'tran_id' => Yii::t('d3acc', 'Transaction'),
            'amt' => Yii::t('d3acc', 'Amount'),
            'notes' => Yii::t('d3acc', 'Notes'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'dim_id' => Yii::t('d3acc', 'Dimension'),
            'tran_id' => Yii::t('d3acc', 'Transaction'),
            'amt' => Yii::t('d3acc', 'Amount'),
            'notes' => Yii::t('d3acc', 'Notes'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDim()
    {
        return $this->hasOne(\d3acc\models\AcDim::className(), ['id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTran()
    {
        return $this->hasOne(\d3acc\models\AcTran::className(), ['id' => 'tran_id']);
    }




    public function saveOrException($runValidation = true, $attributeNames = null)
    {
        if(!parent::save($runValidation, $attributeNames)){
            throw new Exception(json_encode($this->getErrors()));
        }
    }
}
