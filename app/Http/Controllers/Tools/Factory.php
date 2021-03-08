<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BlogDirectory;
use App\BlogContent;
use App\Browse;
use App\Comment;

class Factory extends Controller
{
    //
    static function Get($value){
          switch ($value){
              case 'BlogDirectory':
                   return new BlogDirectory();
                   break;
              case 'BlogContent':
                  return new BlogContent();
                  break;
              case  'Browse':
                  return  new Browse();
                  break;
              case  'Comment':
                  return  new Comment();
                  break;
          }
    }

}
