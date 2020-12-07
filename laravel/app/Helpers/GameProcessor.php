<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use ZipArchive;


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

    public static function addAPI($gfile) {
      $gfile = view('parts.apiwrapper').$gfile;
      return $gfile;
    }

    public static function unzipGame($f, $shortlink, $ver) {
      //TODO: return true/false;
      //TODO: remove git (X-master);
      $fref = "/$shortlink/$ver";
      Storage::disk('games')->makeDirectory($fref);
      $z = new ZipArchive;
      if ($z->open($f) === true) {
        for ($i = 0; $i<$z->numFiles; $i++) {
          $member = $z->getStream($z->getNameIndex($i));
          if (!$member) response()->json(['error'=>'ugh']);
          while (!feof($member)) {
            if (substr($z->getNameIndex($i), -1) === '/') {
              Storage::disk('games')->makeDirectory($fref.'/'.$z->getNameIndex($i));
            } else {
              $pos = strpos($z->getNameIndex($i), '/');
              $fixfref = '';
              if ($pos !== false) {
                $rx = '/.*\/(.*)/';
                preg_match($rx, $z->getNameIndex($i), $fname);
                Storage::disk('games')->put($fref.'/'.$fname[0], fread($member, 8192));
                $fixfref = $fref.'/'.$fname[0];
              } else {
                Storage::disk('games')->put($fref.'/'.$z->getNameIndex($i), fread($member, 8192));
                $fixfref = $fref.'/'.$z->getNameIndex($i);
              }
              $toFix = Storage::disk('games')->get($fixfref);
              $toFix = GameProcessor::processLS($toFix, $shortlink);
              $isHTML = strpos($fixfref, 'index.html');
              if ($isHTML !== false) {
                echo 'adding API';
                $toFix = GameProcessor::addAPI($toFix);
              }
              Storage::disk('games')->put($fixfref, $toFix);
              unset($fixfref);
            }
            //I'M CONFUSED, DO NOT TOUCH
            $content = fread($member, 8192);
          }
        }
        echo 'ok';
        return response()->json(['success' => 'Game uploaded @ '.$shortlink]);
      } else {
        return response()->json(['error' => 'Failed to process file']);
      }
    }
}
