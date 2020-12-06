<?php

namespace App\Helpers;

class GameProcessor
{
    public static function processLS($gfile, $shortlink)
    {
      $isolate = $gfile;
      $isolate = str_replace('localStorage.setItem(\'',
                           'localStorage.setItem(\'timesink_'.$shortlink.'__',
                           $isolate);
      $isolate = str_replace('localStorage.getItem(\'',
                           'localStorage.getItem(\'timesink_'.$shortlink.'__',
                           $isolate);
      $isolate = str_replace('localStorage.setItem("',
                           'localStorage.setItem("timesink_'.$shortlink.'__',
                           $isolate);
      $isolate = str_replace('localStorage.getItem("',
                           'localStorage.getItem("timesink_'.$shortlink.'__',
                           $isolate);
      $rx = '/localStorage\.(?!(setItem|getItem))/';
      $nstring = "localStorage.timesink__$shortlink"."_";
      $isolate = preg_replace($rx, $nstring, $isolate);
      return $isolate;
    }
}
