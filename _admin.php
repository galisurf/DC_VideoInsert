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
if (!defined('DC_CONTEXT_ADMIN')) { return; }

$core->addBehavior('adminPostHeaders',array('VideoInsertBehaviors','jsLoad'));
$core->addBehavior('adminPageHeaders',array('VideoInsertBehaviors','jsLoad'));
$core->addBehavior('adminRelatedHeaders',array('VideoInsertBehaviors','jsLoad'));
$core->addBehavior('adminDashboardHeaders',array('VideoInsertBehaviors','jsLoad'));

class VideoInsertBehaviors
{
	public static function jsLoad()
	{
		return
		'<script type="text/javascript" src="index.php?pf=VideoInsert/post.js"></script>'.
		'<script type="text/javascript">'."\n".
		"//<![CDATA[\n".
		dcPage::jsVar('jsToolBar.prototype.elements.VideoInsertBehaviors.title','Rep Galerie').
		"\n//]]>\n".
		"</script>\n";
	}
}
?>