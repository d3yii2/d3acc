<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace d3acc\models\base;

use Yii;

/**
 * This is the base-model class for table "ac_rec_acc".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $label
 *
 * @property \d3acc\models\AcPeriodBalance[] $acPeriodBalances
 * @property \d3acc\models\AcAccount $account
 * @property \d3acc\models\AcRecRef[] $acRecRefs
 * @property \d3acc\models\AcTran[] $acTrans
 * @property \d3acc\models\AcTran[] $acTrans0
 * @property string $aliasModel
 */
abstract class AcRecAcc extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ac_rec_acc';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id'], 'required'],
            [['account_id'], 'integer'],
            [['label'], 'string', 'max' => 100],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcAccount::className(), 'targetAttribute' => ['account_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('d3acc', 'ID'),
            'account_id' => Yii::t('d3acc', 'Account'),
            'label' => Yii::t('d3acc', 'Label'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'account_id' => Yii::t('d3acc', 'Account'),
            'label' => Yii::t('d3acc', 'Label'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcPeriodBalances()
    {
        return $this->hasMany(\d3acc\models\AcPeriodBalance::className(), ['rec_acc_id' => 'id'])->inverseOf('recAcc');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(\d3acc\models\AcAccount::className(), ['id' => 'account_id'])->inverseOf('acRecAccs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcRecRefs()
    {
        return $this->hasMany(\d3acc\models\AcRecRef::className(), ['rec_account_id' => 'id'])->inverseOf('recAccount');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcTrans()
    {
        return $this->hasMany(\d3acc\models\AcTran::className(), ['debit_rec_acc_id' => 'id'])->inverseOf('debitRecAcc');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcTrans0()
    {
        return $this->hasMany(\d3acc\models\AcTran::className(), ['credit_rec_acc_id' => 'id'])->inverseOf('creditRecAcc');
    }




}
