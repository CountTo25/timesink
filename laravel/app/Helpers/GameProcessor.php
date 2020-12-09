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
      $rx = '/localStorage\.setItem\((?!('."'".'|"))/';
      $nstring = 'localStorage.setItem("timesink__'.$shortlink.'_"+';
      $isolate = preg_replace($rx, $nstring, $isolate);
      $rx = '/localStorage\.getItem\((?!('."'".'|"))/';
      $nstring = 'localStorage.getItem("timesink__'.$shortlink.'_"+';
      $isolate = preg_replace($rx, $nstring, $isolate);
      return $isolate;
    }

    public static function addAPI($gfile) {
      $gfile = view('parts.apiwrapper').$gfile;
      return $gfile;
    }

    public static function unzipGame($f, $shortlink, $ver, &$messages) {
      //TODO: return true/false;
      //TODO: remove git (X-master);
      $fref = "/$shortlink/$ver";
      Storage::disk('games')->makeDirectory($fref);
      $z = new ZipArchive;
      if ($z->open($f) === true) {
        $z->extractTo(Storage::disk('games')->getDriver()->getAdapter()->getPathPrefix().$shortlink.'/'.$ver);
        $z->close();

        $hasAPIsave = false;

        $all = Storage::disk('games')->allFiles("$shortlink/$ver/");
        foreach ($all as $gamefile) {
          if (strpos($gamefile, 'index.html')!==false) {
            $messages[] = 'Found index.html';
            $toFix = Storage::disk('games')->get($gamefile);
            $toFix = GameProcessor::addAPI($toFix);
            $messages[] = '   Injected API @ index.html';
            $toFix = GameProcessor::processLS($toFix, $shortlink);
            $messages[] = '   Isolated localStorage @ index.html';
            Storage::disk('games')->put($gamefile, $toFix);
          }
          if (strpos($gamefile, '.js')!==false) {
            $messages[] = 'Found '.basename($gamefile);
            $toFix = Storage::disk('games')->get($gamefile);
            $toFix = GameProcessor::processLS($toFix, $shortlink);
            $messages[] = '   Isolated localStorage @'.basename($gamefile);
            Storage::disk('games')->put($gamefile, $toFix);
          }
        }
        $messages[] = 'Game uploaded @ '.$shortlink;
      }
    }

    public static function cleanupgit($shortlink, $version, &$messages) {
        $messages[] = 'cleaning up github stuff';
        $dir = Storage::disk('games')->directories("$shortlink/$version/");
        $trashfolder = basename($dir[0]);
        $messages[] = 'found directory named '.$trashfolder;
        print_r('found directory named '.$trashfolder);
        print_r($dir);
        $all = Storage::disk('games')->allFiles("$shortlink/$version/");
          foreach ($all as $gamefile) {
            $clean = str_replace("/$trashfolder", "", $gamefile);
            $messages[] = "Moving $gamefile to $clean";
            Storage::disk('games')
              ->move($gamefile, $clean);
          }
        Storage::disk('games')->deleteDirectory($dir[0]);
    }
}
