<?php

namespace Illuminate\Validation;

use Closure;
use DateTime;
use Countable;
use Exception;
use DateTimeZone;
use RuntimeException;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use BadMethodCallException;
use InvalidArgumentException;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CustomValidator extends \Illuminate\Validation\Validator
{
  /**
  * 「ファイルを選択する」を選択したときのアップロードファイルの有無
  *
  * @param $attribute
  * @param $value
  * @return bool
  */
    public function validateUploadFile($attribute, $value, $parameters){
      $skillsheet = $this->getValue('skillsheet.0');
      if($this->getValue('resume') == 'now' && $this->getValue('file_type') == 'fe'){
       
          if (!is_null($skillsheet)) {


            $this->validateMax($attribute, $value, $parameters);
            return true;
          }
        
      }else{
        return true;
      }
      return false;
    }









      
}