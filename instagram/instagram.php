<?php
/*
Plugin Name: instagram
Author: Arata Urano
*/

add_action( 'plugins_loaded', array( 'InstaGramShortCode', 'setup' ) );

class InstaGramShortCode {
  const CODE = 'instagramshortcode';
  const URL = 'https://api.instagram.com/v1/users/self/media/recent?access_token=';
  const MAXCNT = 8;
  protected static $dir;
  protected static $url;
  public static $instaData;

  private function __construct () {}

  static public function setup() {
    self::$dir = dirname( __FILE__ ); // /var/www/wordpress/wp-content/plugins/instagram

    add_shortcode( 'instaCode',  array( 'InstaGramShortCode', 'tag_echo' ) );
  }

  static public function tag_echo( $args = null ) {
    if ( $args == null || $args["token"] == null ) {
      return;
    }
    $token = $args["token"];
    $instaData = self::getData( $token );
    return $instaData;
  }

  static public function getData ( $token ) {
    $url = self::URL . $token;
    $ans = @file_get_contents( $url );
    if ( $ans != false ) {
      self::$instaData = json_decode( $ans );
      return self::create_insta_thumbnail( self::$instaData );
    }
    return;
  }

  static public function create_insta_thumbnail ( $instaData ) {
    $photo_cnt = count( $instaData->data );
    $fileName = $fileName . '.php';

    $html = '<div class="notice notice-field">';
    for ( $i=0; $i<$photo_cnt; $i++ ) {

      if ( $i >= self::MAXCNT ) { break; }

      if ( self::$instaData->data[$i]->type != 'image') { continue; }
      $thumbnail 	= self::$instaData->data[$i]->images->standard_resolution->url;
      $user 	= self::$instaData->data[$i]->user->full_name;
      $text 	= self::$instaData->data[$i]->caption->text;

      $link				= self::$instaData->data[$i]->link;
      $html .= '<article class="notice-article">';
      $html .= '<a class="notice-link" href="' . $link .'">';
      $html .= '<p class="notice__img">';
      $html .= '<img src="'. $thumbnail .'" alt="Demo暮らし.net インスタグラム対応" />';
      $html .= '</p>';
      $html .= '<div class="notice-article-info">';
      $html .= '<h1>'. $user .'</h1>';
      $html .= '<h2>'. $text .'</h2>';
      $html .= '';
      $html .= '</div>';
      $html .= '</a>';
      $html .= '</article>';
    }
    $html .= '</div>';
    $html .= self::createCSS();
    return $html;
  }

  static public function createCSS () {
    $css = <<< __CSS__
    <style style="display:none;">
    	@charset "UTF-8";
    	article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block}
    	audio,canvas,video{display:inline-block}
    	audio:not([controls]){display:none;height:0}[hidden],template{display:none}

    	.notice-field{zoom:1}
    	.notice-field:before,.notice-field:after{display:table;content:""}
    	.notice-field:after{clear:both}

    	.notice{margin-bottom:1em}
    	.notice-article{
    		overflow:hidden;position:relative;
    		width:100%;margin:0 0 25px;
    		border-radius:2px;
    		background:#fff;
    		box-shadow:0 0 3px 0 rgba(0,0,0,.12),0 2px 3px 0 rgba(0,0,0,.22);
    		cursor:pointer;transition:.2s ease-in-out
    	}
    	.notice-article:hover{
    		box-shadow:0 15px 30px -5px rgba(0,0,0,.15),0 0 5px rgba(0,0,0,.1);transform:translateY(-4px)
    	}
    	.notice-link{
    		display:block;
    		color:#555;
    		text-decoration:none;
    		cursor:pointer
    	}
    	.notice-link:hover{
    		color:#555;text-decoration:none
    	}
    	.notice__img{
    		margin:0;overflow:hidden;position:relative;height:0;padding-bottom:57.7%
    	}
    	.notice-link{padding-bottom:25px}
    	.notice h2{margin:8px 13px 0;font-size:17px}
    	.notice time,.notice h1{
    		display:block;
    		margin:13px 13px 8px;
    		color:#b5b5b5;
    		font-size:13px;
    		font-weight:bold
    	}
    	.notice time:before{
    		content:'\f017';
    		font-family:FontAwesome;
    		padding-right:4px;
    		font-weight:normal
    	}
    	.cat-name{
    		display:inline-block;
    		overflow:hidden;
    		position:absolute;
    		top:13px;left:13px;height:22px;margin:0;padding:0 10px;border-radius:14px;
    		color:#fff;font-size:11px;font-weight:bold;vertical-align:middle;line-height:22px;
    	}
    	.cat-name:hover{
    		text-decoration:none;
    		background:silver;
    	}

    	.notice-article,.sidelong__article{animation:fadeIn 1.1s ease 0s 1 normal}
    	.notice-article:first-child,
    	.notice-article:nth-child(2){
    		animation:fadeIn .7s ease 0s 1 normal
    	}

    @media only screen and (min-width:481px){
    	.notice{display:-webkit-flex;display:-ms-flexbox;display:-moz-box;display:flex;flex-direction:row;-webkit-flex-direction:row;-webkit-flex-wrap:wrap;flex-wrap:wrap}
    	.notice-article{float:left;width:46%;margin:0 1.8% 25px}
    	.notice-link{padding-bottom:15px}
    	.notice-article h2{margin:8px 13px 0}
    	.notice-article time{margin:13px 13px 8px}
    }

    @media only screen and (min-width:1030px){
    	.notice-article{width:45%;margin:0 4% 25px 0}
    	.notice-link{padding-bottom:25px}
    	.notice h2{margin:8px 13px 0;font-size:18px}
    	.notice time{margin:13px 13px 8px}

    	h1{font-size:1.5em}.article-header{margin:25px 40px}
    }

    	a{color:#5ba9f7}
    	.cat-name{background-color:#5ba9f7}
    </style>
__CSS__;

  return $css;
  }
}
