<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace d3acc\models\base;

use Yii;

/**
 * This is the base-model class for table "ac_period_balance".
 *
 * @property integer $id
 * @property integer $period_id
 * @property integer $rec_acc_id
 * @property string $amount
 *
 * @property \d3acc\models\AcRecAcc $recAcc
 * @property \d3acc\models\AcPeriod $period
 * @property string $aliasModel
 */
abstract class AcPeriodBalance extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ac_period_balance';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period_id', 'rec_acc_id', 'amount'], 'required'],
            [['period_id', 'rec_acc_id'], 'integer'],
            [['amount'], 'number'],
            [['rec_acc_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcRecAcc::className(), 'targetAttribute' => ['rec_acc_id' => 'id']],
            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcPeriod::className(), 'targetAttribute' => ['period_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('d3acc', 'ID'),
            'period_id' => Yii::t('d3acc', 'Period'),
            'rec_acc_id' => Yii::t('d3acc', 'Account'),
            'amount' => Yii::t('d3acc', 'Amount'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'period_id' => Yii::t('d3acc', 'Period'),
            'rec_acc_id' => Yii::t('d3acc', 'Account'),
            'amount' => Yii::t('d3acc', 'Amount'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecAcc()
    {
        return $this->hasOne(\d3acc\models\AcRecAcc::className(), ['id' => 'rec_acc_id'])->inverseOf('acPeriodBalances');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriod()
    {
        return $this->hasOne(\d3acc\models\AcPeriod::className(), ['id' => 'period_id'])->inverseOf('acPeriodBalances');
    }




}
