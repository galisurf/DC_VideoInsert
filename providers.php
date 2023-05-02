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

// $$url$$ is replaced by video url (exemple "http://www.youtube.com/v/78_ke3rxopc?fs=1")
// $$width$$ is replaced by given width (exemple "425")
// $$height$$ is replaced by given height (exemple "350")
// $$link$$ is replaced by link to the video provider (exemple "http://www.youtube.com/watch?v=78_ke3rxopc")
// $$provider$$ is replaced by video provider name (exemple "YouTube")
// $$id$$ is replaced by video ID (exemple "78_ke3rxopc")
// $$start$$ is replaced by video start time if not 0 (exemple "&start=10")

////////////////////

// $template = '<object type="application/x-shockwave-flash" data="$$url$$" width="$$width$$" height="$$height$$">
  // <param name="movie" value="$$url$$" />
  // <param name="wmode" value="transparent" />
  // <param name="allowFullScreen" value="true" />
  // <p><a target="_blank" href="$$link$$">'. __('See video on') .' $$provider$$</a></p>
// </object>';

$template = '
<iframe width="$$width$$" height="$$height$$" src="$$url$$" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';

 
// <video width="$$width$$" height="$$height$$" controls>
  // <source src="$$url$$" type="video/mp4">
// Your browser does not support the video tag. 
// </video>';

////////////////////

// URL of the video
$provider['YouTube']['url'] = 'https://www.youtube-nocookie.com/embed/$$id$$?start=$$start$$';
// Link to the video page (if no flash on device)
$provider['YouTube']['link'] = 'http://www.youtube.com/watch?v=$$id$$';
// Provider favicon url 
$provider['YouTube']['favicon'] = 'http://s.ytimg.com/yt/favicon.ico';
// Example of url string for help
$provider['YouTube']['urlexample'][0] = 'http://www.youtube.com/watch?v=-F_ke3rxopc&amp;feature=topvideos_science';
$provider['YouTube']['urlexample'][1] = 'http://www.youtube.com/watch?v=78_ke3rxopc';
$provider['YouTube']['urlexample'][2] = 'http://youtu.be/OdBP4WdymK0';
// Example of video id string for help
$provider['YouTube']['idexample'][0] = 'OdBP4WdymK0';
$provider['YouTube']['idexample'][1] = '-F_ke3rxopc';
// Regexp used to recognize video ID
$provider['YouTube']['idrecognize']='#^.{11}$#';
// Regexp used to extract video ID from URL
$provider['YouTube']['urlidextract'][0]='#^http://.*youtube.com/.*v=(.{11})#';
$provider['YouTube']['urlidextract'][1]='#^http://.*youtu.be/(.{11})#';

////////////////////

$provider['DailyMotion']['url'] = 'http://www.dailymotion.com/swf/video/$$id$$?$$start$$';
$provider['DailyMotion']['link'] = 'http://www.dailymotion.com/video/$$id$$';
$provider['DailyMotion']['favicon'] = 'http://www.dailymotion.com/images/favicon.ico';
$provider['DailyMotion']['urlexample'][0] = 'http://www.dailymotion.com/video/xlsuix_cyril-dumoulin-sauve-chambery-contre-selestat_sport#hp-v-v3';
$provider['DailyMotion']['urlexample'][1] = 'http://www.dailymotion.com/video/xlsuix';
$provider['DailyMotion']['idexample'][0] = 'xlsuix';
$provider['DailyMotion']['idrecognize']='#^.{6,7}$#';
$provider['DailyMotion']['urlidextract'][0]='#^http://.*dailymotion.com/video/(.{6,7})#';

////////////////////

$provider['Vimeo']['url'] = 'http://www.vimeo.com/moogaloop.swf?clip_id=$$id$$';
$provider['Vimeo']['link'] = 'http://www.vimeo.com/$$id$$';
$provider['Vimeo']['favicon'] = 'http://vimeo.com/favicon.ico';
$provider['Vimeo']['urlexample'][0] = 'http://vimeo.com/29289993';
$provider['Vimeo']['idexample'][0] = '29289993';
$provider['Vimeo']['idrecognize']='#^[0-9]{8}$#';
$provider['Vimeo']['urlidextract'][0]='#^http://.*vimeo.com/([0-9]{8})#';

////////////////////

//http://video.google.fr/videoplay?docid=-4729226390349679247&emb=1&hl=fr
$provider['GoogleVideo']['url'] = 'http://video.google.com:80/googleplayer.swf?docid=$$id$$&amp;fs=true';
$provider['GoogleVideo']['link'] = 'http://video.google.com/videoplay?docid=$$id$$';
$provider['GoogleVideo']['favicon'] = 'http://www.google.com/favicon.ico';
$provider['GoogleVideo']['urlexample'][0] = 'http://video.google.com/videoplay?docid=6965180024813873134#';
$provider['GoogleVideo']['urlexample'][1] = 'http://video.google.fr/videoplay?docid=-4729226390349679247&amp;emb=1&amp;hl=fr';
$provider['GoogleVideo']['idexample'][0] = '6965180024813873134';
$provider['GoogleVideo']['idrecognize']='#^[-]?[0-9]{19}$#';
$provider['GoogleVideo']['urlidextract'][0]='#^http://.*video.google.*/.*docid=([-]?[0-9]{19})#';

////////////////////

//$provider['Generic']['url'] = '$$id$$';
//$provider['Generic']['link'] = '$$id$$';
//$provider['Generic']['favicon'] = '';
//$provider['Generic']['urlexample'][0] = 'http://generic.com/v/2928ae-d993';
//$provider['Generic']['idexample'][0] = 'http://generic.com/v/2928ae-d993';
//$provider['Generic']['idrecognize']='#.*#';
//$provider['Generic']['urlidextract'][0]='#(.*)#';

////////////////////

?>