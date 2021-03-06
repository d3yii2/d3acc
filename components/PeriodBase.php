<?php

namespace d3acc\components;

use d3acc\models\AcPeriod;
use \d3acc\models\AcPeriodBalance;
use d3acc\models\AcPeriodBalanceDim;
use DateTime;
use Exception;
use Yii;

class PeriodBase
{

    /**
     * creat first record for period
     * @param string $date date format yyyy-mm-dd
     * @param int $periodType
     * @param int $sysCompanyId
     * @return AcPeriod
     * @throws Exception
     */
    public static function init(string $date, int $periodType, int $sysCompanyId): AcPeriod
    {

        if (AcPeriod::findAll([
            'period_type' => $periodType,
            'sys_company_id' => $sysCompanyId
        ])) {
            throw new Exception('Period type '.$periodType.' already exist for sysCompanyid: ' .$sysCompanyId);
        }

        $period              = new AcPeriod();
        $period->sys_company_id = $sysCompanyId;
        $period->period_type = $periodType;
        $period->from        = static::getFrom($date);
        $period->to          = static::getTo($date);
        $period->status      = AcPeriod::STATUS_ACTIVE;
        if (!$period->save()) {
            throw new Exception('Can not init period: '.json_encode($period->getErrors()));
        }

        return $period;
    }

    /**
     * add next period and calculate balance and dim balance for closed period
     *
     * @param int $periodType
     * @param int $sysCompanyId
     * @return AcPeriod
     * @throws \yii\db\Exception
     */
    public static function close(int $periodType, int $sysCompanyId): AcPeriod
    {

        if (!$lastPeriod = AcPeriod::find()
            ->where([
                'sys_company_id' => $sysCompanyId,
                'period_type' => $periodType,
                'status' => AcPeriod::STATUS_ACTIVE
                ])
            ->orderBy(['from' => SORT_ASC])
            ->one()
        ) {
            throw new Exception('Period type '.$periodType.' do not exist');
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        if(!$r = AcPeriodBalance::savePeriodBalance($lastPeriod)){
            $transaction->rollback();
            throw new Exception('Can not create balance: '.json_encode($r));
        }

        if(!$r = AcPeriodBalanceDim::saveDimPeriodBalance($lastPeriod)){
            $transaction->rollback();
            throw new Exception('Can not create dim balance: '.json_encode($r));
        }



        if(!$lastPeriod->next_period){
            $date = new DateTime($lastPeriod->to);
            $date->modify('+1 day');
            $from = $date->format('Y-m-d');

            $period              = new AcPeriod();
            $period->sys_company_id = $sysCompanyId;
            $period->period_type = $periodType;
            $period->from        = $from;
            $period->to          = static::getTo($from);
            $period->status      = AcPeriod::STATUS_ACTIVE;
            $period->prev_period = $lastPeriod->id;
            if (!$period->save()) {
                throw new Exception('Can not add next period: '.json_encode($period->getErrors()));
            }
            $lastPeriod->next_period = $period->id;
        }else{
            $period = AcPeriod::findOne($lastPeriod->next_period);
        }

        $lastPeriod->status = AcPeriod::STATUS_CLOSED;

        if (!$lastPeriod->save()) {
            $transaction->rollback();
            throw new Exception('Can not close prev. period: '.json_encode($period->$lastPeriod()));
        }

        $transaction->commit();
        
        return $period;
    }

    /**
     * @param int $periodType
     * @param int $sysCompanyId
     * @return AcPeriod
     * @throws \yii\db\Exception
     */
    public static function addNewPeriod(int $periodType, int $sysCompanyId): AcPeriod
    {
        if (!$lastPeriod = AcPeriod::find()
            ->where([
                'sys_company_id' => $sysCompanyId,
                'period_type' => $periodType
            ])
            ->orderBy(['from' => SORT_DESC])
            ->one()
        ) {
            throw new Exception('Period type '.$periodType.' do not exist');
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();


        $date = new DateTime($lastPeriod->to);
        $date->modify('+1 day');
        $from = $date->format('Y-m-d');

        $period              = new AcPeriod();
        $period->sys_company_id = $sysCompanyId;
        $period->period_type = $periodType;
        $period->from        = $from;
        $period->to          = static::getTo($from);
        $period->status      = AcPeriod::STATUS_ACTIVE;
        $period->prev_period = $lastPeriod->id;
        if (!$period->save()) {
            throw new Exception('Can not add next period: '.json_encode($period->getErrors()));
        }
        $lastPeriod->next_period = $period->id;

        if (!$lastPeriod->save()) {
            $transaction->rollback();
            throw new Exception('Can not close prev. period: '.json_encode($period->$lastPeriod()));
        }

        $transaction->commit();

        return $period;

    }

}