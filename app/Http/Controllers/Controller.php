<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * ログ出力を行う
     *
     * @param string $level
     * @param string $text
     * @param array  $variable
     */
    protected function log($level = '', $text = '', $variable = []) {
        $full_text = $this->getLogPrefix().$text;
        switch ($level) {
            case 'info':  Log::info($full_text, $variable); break;
            case 'error': Log::error($full_text, $variable);break;
            default:      Log::info($full_text, $variable); break;
        }
    }

    /**
     * ログ出力時の接頭語を返す
     *
     * @return string
     */
    private function getLogPrefix() {
        return '['.$this->getCallFunctionName().'#'.$this->getCallFunctionLine().'] ';
    }

    /**
     * ログ出力時の呼び出し元クラスとメソッド名を返す
     *
     * @return string
     */
    private function getCallFunctionName() {
        $backtrace = debug_backtrace(true, 4)[3];
        return $backtrace['class'].$backtrace['type'].$backtrace['function'];
    }

    /**
     * ログ出力時の行番号を返す
     *
     * @return string
     */
    private function getCallFunctionLine() {
        return debug_backtrace(true, 4)[2]['line'];
    }
}
