<?php
// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is a plugin for Dotclear 2.
// 
// Copyright (c) 2013 FredM
// Licensed under the GPL version 2.0 license.
// A copy of this license is available in LICENSE file or at
// http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
// -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_RC_PATH')) {return;}

$core->addBehavior('publicBeforeContentFilter',array('InsertVideobehaviorContent','publicBeforeContentFilter'));
$core->addBehavior('publicHeadContent',array('InsertVideobehaviorContent','publicHeadContent'));

class InsertVideobehaviorContent {

	public static function publicHeadContent($core)
	{
			// Enregistrement ou non en session du mot de passe pour l'affichage des videos perso
		if (isset($_REQUEST['showprivatevideo'])) {
			if ($_REQUEST['showprivatevideo']) {
				@session_start();
				$_SESSION['sess_blog_videoinsertmdp'] = md5($_REQUEST['showprivatepassword']);
			} else {
				@session_start();
				unset($_SESSION['sess_blog_videoinsertmdp']);
			}
		}
	}

	public static function publicBeforeContentFilter ($core,$tag,$args)
	{
		global $_ctx;
		if ($tag == "EntryContent" || $tag == "EntryExcerpt") {
			$video_reg = "|::video.*id=\'(.*)\'.*::|Ui";
			$args[0] = preg_replace_callback($video_reg, array('self','InsertVideo'), $args[0]);
		}
	}
	
	static $videoinsertnumber = 0;
	
	protected static function InsertVideo($m)
	{
			// Charge les liste des providers
		require dirname(__FILE__).'/providers.php';
		
		$texte = $m[0];
		$id    = $m[1];
		self::$videoinsertnumber = self::$videoinsertnumber + 1;
		
		$w = 425;
		$h = 350;
		if (preg_match('|width=\'(.*)\'|Ui', $texte, $args))
			$w = $args[1];
		if (preg_match('|height=\'(.*)\'|Ui', $texte, $args))
			$h = $args[1];
		
		$start = 0;
		if (preg_match('|start=\'(.*)\'|Ui', $texte, $args))
			$start = $args[1];

		$align= 'center';
		if (preg_match('|align=\'(.*)\'|Ui', $texte, $args))
			$align= $args[1];
			
		$s = "\n".'<a name="videoinsert'.self::$videoinsertnumber.'"></a>';
		
					// Si protection par mot de passe
		if (preg_match('|password=\'(.*)\'|Ui', $texte, $args)) {
			$pass = $args[1];
			@session_start();
				// Si on est logué (bon mdp enregistré en session)
			if (isset($_SESSION['sess_blog_videoinsertmdp']) && $_SESSION['sess_blog_videoinsertmdp'] == md5($pass)) {
				// Affichage du bouton pour cacher les vidéos privées
				$formname = 'videoinsertmdp' . self::$videoinsertnumber;
				$s .= '
				<p><a href="javascript:document.getElementById(\''.$formname.'\').submit();">'.__('Hide private video').'</a></p>
				<form id="'.$formname.'" method="post" action="#videoinsert'.self::$videoinsertnumber.'"><p>' . $GLOBALS['core']->formNonce() . form::hidden('showprivatevideo','0') . '</p></form>';
			} else {
				// Affichage du formulaire de mdp
				$formname = 'videoinsertmdp' . self::$videoinsertnumber;
				$s .= '
				<div class="videoinsert" style="margin: 1em auto;">
				<p><a href="#" onclick="$(\'#P_'.$formname.'\').toggle(\'fast\');return false;">'.__('+ Show private video').'</a></p>
				<form id="'.$formname.'" method="post" action="#videoinsert'.self::$videoinsertnumber.'"><p id="P_'.$formname.'" style="display:none">
				' . $GLOBALS['core']->formNonce() . form::hidden('showprivatevideo','1') .
				__('In order to show private video you have to enter a password.').'<br />
				<input size="20" name="showprivatepassword" id="showprivatepassword" maxlength="50" type="password" />
				<a href="javascript:document.getElementById(\''.$formname.'\').submit();">OK</a>
				</p></form>
				</div>'."\n";
				return $s;
			}
		}
		
		$s .= "\n".'<div class="videoinsert" style="margin: 1em auto; text-align: '.$align.';">';
		foreach($provider as $k => $v)
			if (preg_match('|'.$k.'|Ui', $texte)) {
				$t = $template;
				$t = str_replace('$$url$$'     , $v['url'] , $t);
				$t = str_replace('$$link$$'    , $v['link'], $t);
				$t = str_replace('$$provider$$', $k        , $t);
				$t = str_replace('$$id$$'      , $id       , $t);
				//$t = str_replace('$$link$$'    , $w        , $t);
				$t = str_replace('$$width$$'   , $w        , $t);
				$t = str_replace('$$height$$'  , $h        , $t);
				
				//if ($start != 0 && $v['enablestart']) {
				if ($start != 0) {
					$t = str_replace('$$start$$', '&start='.$start, $t);
				} else {
					$t = str_replace('$$start$$', '', $t);
				}
				
				$s .= $t;
			}
		$s .= '</div>'."\n";
		
		return $s;
	}

}

?>