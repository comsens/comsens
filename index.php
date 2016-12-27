<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

include_once(G5_PATH.'/head.php');
?>

      <div class="row">
        <div class="col-xs-4 col-md-4 col-sm-4">
		<?php echo latest("basic", "board01", 4, 34); ?>
        </div>
        <div class="col-xs-4 col-md-4 col-sm-4">
		<?php echo latest("basic", "board02", 4, 34); ?>
        </div>
        <div class="col-xs-4 col-md-4 col-sm-4">
		<?php echo latest("basic", "board03", 4, 34); ?>
        </div>
      </div>

<?php
include_once(G5_PATH.'/tail.php');
?>

테스트스 ㅁㄴㅇㄹㄹㄴㅇㄹㄴㅇㄹㄴㅇㄹ