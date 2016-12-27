<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!$board['bo_use_tag']) return;
include_once(G5_TAG_PATH."/view.tag.update.skin.php");



$webhookurl="https://nexus-mink.ncsoft.com/webhook/f5fe18b4-4317-459f-9db9-2aac42b017f1";
$webhook = $webhookurl . "?text=".urlencode($wr_subject);
//echo $webhook;
getFromUrl($webhook);



?>