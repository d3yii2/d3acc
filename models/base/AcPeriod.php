<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace d3acc\models\base;

use Yii;

/**
 * This is the base-model class for table "ac_period".
 *
 * @property integer $id
 * @property integer $sys_company_id
 * @property integer $period_type
 * @property string $from
 * @property string $to
 * @property string $status
 * @property integer $prev_period
 * @property integer $next_period
 *
 * @property \d3acc\models\AcPeriodBalanceDim[] $acPeriodBalanceDims
 * @property \d3acc\models\AcPeriodBalance[] $acPeriodBalances
 * @property \d3acc\models\AcPeriod[] $acPeriods
 * @property \d3acc\models\AcPeriod[] $acPeriods0
 * @property \d3acc\models\AcTran[] $acTrans
 * @property \d3acc\models\AcPeriod $nextPeriod
 * @property \d3acc\models\PkActualProfit[] $pkActualProfits
 * @property \d3acc\models\AcPeriod $prevPeriod
 * @property string $aliasModel
 */
abstract class AcPeriod extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    public const STATUS_PLANNED = 'Planned';
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_CLOSED = 'Closed';
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'ac_period';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'required' => [['period_type', 'from', 'to'], 'required'],
            'enum-status' => ['status', 'in', 'range' => [
                    self::STATUS_PLANNED,
                    self::STATUS_ACTIVE,
                    self::STATUS_CLOSED,
                ]
            ],
            'tinyint Unsigned' => [['period_type'],'integer' ,'min' => 0 ,'max' => 255],
            'smallint Unsigned' => [['id','sys_company_id','prev_period','next_period'],'integer' ,'min' => 0 ,'max' => 65535],
            [['from', 'to'], 'safe'],
            [['status'], 'string'],
            [['prev_period'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcPeriod::className(), 'targetAttribute' => ['prev_period' => 'id']],
            [['next_period'], 'exist', 'skipOnError' => true, 'targetClass' => \d3acc\models\AcPeriod::className(), 'targetAttribute' => ['next_period' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('d3acc', 'ID'),
            'sys_company_id' => Yii::t('d3acc', 'Sys Company ID'),
            'period_type' => Yii::t('d3acc', 'Type'),
            'from' => Yii::t('d3acc', 'From'),
            'to' => Yii::t('d3acc', 'To'),
            'status' => Yii::t('d3acc', 'Status'),
            'prev_period' => Yii::t('d3acc', 'Previous period'),
            'next_period' => Yii::t('d3acc', 'Next Period'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints(): array
    {
        return array_merge(parent::attributeHints(), [
            'period_type' => Yii::t('d3acc', 'Type'),
            'from' => Yii::t('d3acc', 'From'),
            'to' => Yii::t('d3acc', 'To'),
            'status' => Yii::t('d3acc', 'Status'),
            'prev_period' => Yii::t('d3acc', 'Previous period'),
            'next_period' => Yii::t('d3acc', 'Next Period'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcPeriodBalanceDims()
    {
        return $this->hasMany(\d3acc\models\AcPeriodBalanceDim::className(), ['period_id' => 'id'])->inverseOf('period');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcPeriodBalances()
    {
        return $this->hasMany(\d3acc\models\AcPeriodBalance::className(), ['period_id' => 'id'])->inverseOf('period');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcPeriods()
    {
        return $this->hasMany(\d3acc\models\AcPeriod::className(), ['prev_period' => 'id'])->inverseOf('prevPeriod');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcPeriods0()
    {
        return $this->hasMany(\d3acc\models\AcPeriod::className(), ['next_period' => 'id'])->inverseOf('nextPeriod');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcTrans()
    {
        return $this->hasMany(\d3acc\models\AcTran::className(), ['period_id' => 'id'])->inverseOf('period');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNextPeriod()
    {
        return $this->hasOne(\d3acc\models\AcPeriod::className(), ['id' => 'next_period'])->inverseOf('acPeriods0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPkActualProfits()
    {
        return $this->hasMany(\d3acc\models\PkActualProfit::className(), ['period' => 'id'])->inverseOf('period0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrevPeriod()
    {
        return $this->hasOne(\d3acc\models\AcPeriod::className(), ['id' => 'prev_period'])->inverseOf('acPeriods');
    }




    /**
     * get column status enum value label
     * @param string $value
     * @return string
     */
    public static function getStatusValueLabel($value): string
    {
        if(!$value){
            return '';
        }
        $labels = self::optsStatus();
        return $labels[$value] ?? $value;
    }

    /**
     * column status ENUM value labels
     * @return array
     */
    public static function optsStatus(): array
    {
        return [
            self::STATUS_PLANNED => Yii::t('d3acc', 'Planned'),
            self::STATUS_ACTIVE => Yii::t('d3acc', 'Active'),
            self::STATUS_CLOSED => Yii::t('d3acc', 'Closed'),
        ];
    }
    /**
    * ENUM field values
    */
    /**
     * @return bool
     */
    public function isStatusPlanned(): bool
    {
        return $this->status === self::STATUS_PLANNED;
    }
    /**
     * @return bool
     */
    public function isStatusActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    /**
     * @return bool
     */
    public function isStatusClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

}
