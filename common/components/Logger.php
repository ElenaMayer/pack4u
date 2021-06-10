<?php

namespace common\components;

use yii\base\Component;
use Yii;

//Yii::$app->logger->log('/test/', json_encode($res));

class Logger extends Component
{
    public $defaultPath = "/../logs";
    protected $handle = null;
    protected $logPath = null;
    protected $logName = null;
    protected $dirSeparator = DIRECTORY_SEPARATOR;
    protected $options = [];

    public function init(){}

    /**
     *
     * @param string $path
     * @param string $logName
     * @param array $options
     * Available options
     * absolute_path  - handler script will use $path as is.
     */
    public function createHandler($path, $logName = '', $options = [])
    {
        $this->options = $options;
        $this->logPath = $this->normalizePath($path);
        $this->logName = $this->normalizeLogName($logName);
        $this->handle = fopen($this->logPath.$this->logName, 'a');
    }

    /**
     * @return null | string
     * @author n.volkov
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * @return null | string
     * @author n.volkov
     */
    public function getLogName()
    {
        return $this->logName;
    }

    protected function normalizePath($path)
    {
        $path = trim($path);
        $path = trim($path, $this->dirSeparator);
        if(!empty($this->options['absolute_path'])){
            $path = $this->dirSeparator.$path.$this->dirSeparator;
        }
        else{
            $path = Yii::$app->basePath.$this->defaultPath.$this->dirSeparator.$path.$this->dirSeparator;
            if(!file_exists($path)){
                $this->createDir($path);
            }
        }
        return $path;
    }

    /**
     * Запись а лог файл
     * @param $text
     * @return bool
     */
    public function writeToLog($message, $withFilePath = true, $withLineNumber = true, $withFunctionName = true)
    {
        $wrapper = @date('Y-m-d H:i:s',strtotime('now'))." $message";

        if ($withFilePath || $withFunctionName || $withLineNumber) {
            $debugBacktrace = debug_backtrace();
            if (is_array($debugBacktrace)) {
                if ($withFilePath && count($debugBacktrace) > 1) {
                    $wrapper .= '; File: ' . $debugBacktrace[1]['file'];
                }
                if ($withLineNumber && count($debugBacktrace) > 1) {
                    $wrapper .= '; Line: ' . $debugBacktrace[1]['line'];
                }
                if ($withFunctionName && count($debugBacktrace) > 2) {
                    $wrapper .= '; Function: ' . $debugBacktrace[2]['function'];
                }
            }
        }

        $wrapper .= "\n";

        if($this->handle){
            $wrapper = (mb_detect_encoding($wrapper) != 'UTF-8') ? utf8_encode($wrapper) : $wrapper;
            fwrite($this->handle, $wrapper);

            print_r($message);die();
            $return = true;
        } else {
            $return = false;
        }

        return $return;
    }

    public function closeHandler()
    {
        if($this->handle){
            fclose($this->handle);
        }
    }

    protected function normalizeLogName($logName)
    {
        $logName = trim($logName);
        $logName = rtrim($logName,'_');
        if ($logName) {
            $logName = $logName . '_' . date('Y-m-d') . '.txt';
        } else {
            $logName = date('Y-m-d') . '.txt';
        }

        return $logName;
    }

    protected function createDir($path)
    {
        mkdir($path, 0777, true);
    }

    /**
     * Write one message into the log
     * @param $handler log file
     * @param $message log message
     */
    public function log($handler, $message, $withFilePath = true, $withLineNumber = true, $withFunctionName = true)
    {
        $this->createHandler($handler);
        $this->writeToLog($message, $withFilePath, $withLineNumber, $withFunctionName);
        $this->closeHandler();
    }

    public function writeProfile($handler, $token)
    {
        $time = 'Time: ' . round(Yii::getLogger()->getExecutionTime(), 3) . 's';
        $memory = 'Max memory: ' . number_format(memory_get_peak_usage()/1024) . 'Kb';

        Yii::app()->logger->log($handler, $token . ' (' . $time . ', ' . $memory . ')' . "\n\r");

        $profiles = $this->getDbProfiles();
        foreach ($profiles as $profile){
            $query = str_replace('end:system.db.CDbCommand.query', '', $profile['query']);
            $title = 'Count: ' . $profile['count'];
            if($profile['count'] == 1){
                $title .= ', Time ' . round($profile['maxTime'],3) . 's.';
            } else {
                $title .= ', Max time: ' . round($profile['maxTime'],3) . 's, Min time: ' . round($profile['minTime'],3) . 's.';
            }
            Yii::app()->logger->log($handler, $title . "\n\r" . $query);
        }
    }

    private function getDbProfiles()
    {
        $profiles = Yii::getLogger()->getProfilingResults();
        $results = [];
        foreach ($profiles as $profile){
            if($profile[1] == 'system.db.CDbCommand.query'){
                if($key = array_search($profile[0], array_column($results, 'query'))){
                    $results[$key]['count']++;
                    if(isset($results[$key]['minTime'])){
                        $results[$key]['minTime'] = $results[$key]['minTime'] < $profile[2] ? $results[$key]['minTime'] : $profile[2];
                    } else {
                        $results[$key]['minTime'] = $results[$key]['maxTime'] < $profile[2] ? $results[$key]['maxTime'] : $profile[2];
                    }
                    $results[$key]['maxTime'] = $results[$key]['maxTime'] > $profile[2] ? $results[$key]['maxTime'] : $profile[2];
                } else {
                    $results[] = [
                        'query' => $profile[0],
                        'maxTime' => $profile[2],
                        'count' => 1,
                    ];
                }
            }
        }
        return $results;
    }
}