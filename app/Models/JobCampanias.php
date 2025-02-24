<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCampanias extends Model
{
    private static $tabla = 'jobs';
    private static $tabla_failed = 'failed_jobs';
    private static $timestamp_max = '1799999999';
    private static $timestamp_base = '200000000';
    private static $version = "v17.0";
    
    public static function getCampaniaStr($campania_id) {
        return '%Jobs%SendHistoryEmails%'.'\\\"campania_id\\\";i:'.$campania_id.';'.'%';
    }
    public static function first($campania_id) {
        $job = \DB::table(self::$tabla)
            ->where('payload', 'LIKE', self::getCampaniaStr($campania_id))
            ->latest()
            ->first();
        if(!$job)return 0;
        return $job->available_at > self::$timestamp_max ? 2: 1;
    }
    public static function toggle($campania_id) {
        return \DB::table(self::$tabla)
            ->where('payload', 'LIKE', self::getCampaniaStr($campania_id))
            ->update([
                'available_at' => \DB::raw('IF( available_at > '.self::$timestamp_max.', available_at - '.self::$timestamp_base.', available_at + '.self::$timestamp_base.' )')
            ]);
    }
    public static function pause($campania_id) {
        return self::operation($campania_id, 1);
    }
    public static function play($campania_id) {
        return self::operation($campania_id, 2);
    }
    public static function operation($campania_id, $tipo) {
        //tipo: 1 - pause, 2 - continue
        $compare_op = '';
        $op = '';
        if($tipo == 1){
            $compare_op = '<=';
            $op = '+';
        }
        if($tipo == 2){
            $compare_op = '>';
            $op = '-';
        }
        if($op== '')return null;
        return \DB::table(self::$tabla)
            ->where('payload', 'LIKE', self::getCampaniaStr($campania_id))
            ->where('available_at', $compare_op, self::$timestamp_max)
            ->update([
                'available_at' => \DB::raw('available_at '.$op.' '.self::$timestamp_base)
            ]);
    }
    public static function retry($campania_id) {
        $operator = '';
        if($campania_id === 'NOT'){
            $operator = 'NOT ';
            $campania_id = '%';
        }
        $queueList = \DB::table(self::$tabla_failed)
            ->where('payload', $operator.'LIKE', self::getCampaniaStr($campania_id))
            ->get();
        if($queueList->count()){
            foreach($queueList as $queue) {
                \Artisan::call('queue:retry '.$queue->uuid);
            }
        }
        return $queueList->count();
    }
    public static function retryAll() {
        return self::retry('%');
    }
    public static function retryNoCampanias() {
        return self::retry('NOT');
    }
}
