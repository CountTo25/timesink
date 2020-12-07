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
        $z->extractTo(Storage::disk('games')->getDriver()->getAdapter()->getPathPrefix().$shortlink.'/'.$ver);
        $z->close();

        $all = Storage::disk('games')->allFiles("$shortlink/$ver/");
        foreach ($all as $gamefile) {
          if (strpos($gamefile, 'index.html')!==false) {
            $toFix = Storage::disk('games')->get($gamefile);
            $toFix = GameProcessor::addAPI($toFix);
            Storage::disk('games')->put($gamefile, $toFix);
            echo 'added API';
          }
          if (strpos($gamefile, '.js')!==false) {
            $toFix = Storage::disk('games')->get($gamefile);
            $toFix = GameProcessor::processLS($toFix, $shortlink);
            Storage::disk('games')->put($gamefile, $toFix);
            echo 'checked JS';
          }
        }
        print_r($all);
        echo 'ok';
        return response()->json(['success' => 'Game uploaded @ '.$shortlink]);
      } else {
        return response()->json(['error' => 'Failed to process file']);
      }
    }
}
