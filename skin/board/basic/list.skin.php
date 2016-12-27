<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

//모바일제목 필드를 사용합니다. 게시판관리에서 첫출력설정하세요. list,webzine,gallery
if(!$board_type){$board_type = $board['bo_mobile_subject'];}

if($bo_type){
set_session('basic_type', $bo_type);
   $basic_type = get_session('basic_type');
}else{
	if($basic_type){
		$basic_type = $basic_type;
	}else{
		$basic_type = $board_type;
	}
}
?>
<link rel="stylesheet" href="<?php echo $board_skin_url;?>/css/style.css">
<div class="panel panel-default">
<div class="panel-heading">
		<h5><i class="fa fa-bar-chart-o"></i> <?php echo $board['bo_subject'] ?><span class="sr-only"> 목록</span></h5>
</div>
<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>" class="panel-body table-content">
    
    <!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
    <nav id="bo_cate ">
        <ol class="breadcrumb hidden-xs" id="bo_cate_ul">
            <?php echo $category_option ?>
        </ol>
	<?php	$category_option = get_category_option($bo_table); ?>
          <form name="fcategory" method="get" action="<?=$_SERVER[PHP_SELF]?>" class="form-group visible-xs">
		<div class="input-group">
	    <input type="hidden" name="bo_table" value="<?=$bo_table?>">
		    <select name="sca" class="input-sm form-control">
            <?php echo $category_option ?>
			</select>
          <span class="input-group-btn">
			<input type="submit" value="확인" class="btn btn-default btn-sm">
          </span>
        </div>
	</form>
    </nav>
    <?php } ?>
    <!-- } 게시판 카테고리 끝 -->

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
	<div class="clearfix" style="height:45px;">
	    <div class="pull-left">
		<div class="btn-group" role="group" aria-label="게시판 설정">
		    <span class="btn btn-default btn-sm tooltip-top" title="전체 글수" data-original-title=" 전체 글수 ">
		    <i class="fa fa-tags fa-lg"></i> <?php echo number_format($total_count) ?> 개</span>
			<span class="btn btn-default btn-sm tooltip-top" title="현재 쪽수" data-original-title="현재 쪽수">
			<?php echo $page ?> 페이지</span>
				<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table; ?>&bo_type=list" class="btn btn-default btn-sm tooltip-top" title="리스트형"><i class="fa fa-list"></i></a>
				<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table; ?>&bo_type=webzine" class="btn btn-default btn-sm tooltip-top" title="웹진형"><i class="fa fa-list-alt"></i></a>
				<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table; ?>&bo_type=gallery" class="btn btn-default btn-sm tooltip-top" title="갤러리형"><i class="fa fa-th"></i></a>
		</div>
	    </div>

		<div class="pull-right">
			<div class="btn-group">
		    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
			<i class="fa fa-cog"></i> &nbsp;&nbsp;<span class="caret"></span>
			</button>
			<?php if ($rss_href || $write_href) { ?>
			<ul class="dropdown-menu pull-right" role="menu">
                <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>"><i class="fa fa-wrench"></i> 관리자</a></li><?php } ?>
                <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>"><i class="fa fa-edit"></i> 글쓰기</a></li><?php } ?>
				<?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>"><i class="fa fa-rss"></i> RSS</a></li><?php } ?>
			</ul>
			<?php } ?>
			</div>
		</div>
	</div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post" class="form-inline">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">
   
   
    <?php include_once($board_skin_path.'/list_'.$basic_type.'.skin.php');?>

    <?php if ($list_href || $is_checkbox || $write_href) { ?>

        <?php if ($list_href || $write_href) { ?>
        <div class="btn-group pull-right">
        <?php if ($list_href) { ?><a href="<?php echo $list_href ?>" class="btn btn-default btn-sm"><i class="fa fa-list"></i> 목록</a><?php } ?>
        <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> 글쓰기</a><?php } ?>
        </div>
        <?php } ?>

        <?php if ($is_checkbox) { ?>
        <div class="btn-group pull-left">
            <input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default btn-sm">
            <input type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn-default btn-sm">
            <input type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn-default btn-sm">
        </div>
        <?php } ?>
    <?php } ?>
    </form>
</div>
<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>
<!-- 페이지 -->
<?php echo $write_pages;  ?>
</div>

<!-- 게시판 검색 시작 { -->
<fieldset id="bo_sch" class="well well-sm text-center">
    <form name="fsearch" method="get" class="form-inline">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sop" value="and">

	<select name="sfl" id="sfl"  class="input-sm form-control" style="margin:5px 0px;">
        <option value="wr_subject"<?php echo get_selected($sfl, 'wr_subject', true); ?>>제목</option>
        <option value="wr_content"<?php echo get_selected($sfl, 'wr_content'); ?>>내용</option>
        <option value="wr_subject||wr_content"<?php echo get_selected($sfl, 'wr_subject||wr_content'); ?>>제목+내용</option>
        <option value="mb_id,1"<?php echo get_selected($sfl, 'mb_id,1'); ?>>회원아이디</option>
        <option value="mb_id,0"<?php echo get_selected($sfl, 'mb_id,0'); ?>>회원아이디(코)</option>
        <option value="wr_name,1"<?php echo get_selected($sfl, 'wr_name,1'); ?>>글쓴이</option>
        <option value="wr_name,0"<?php echo get_selected($sfl, 'wr_name,0'); ?>>글쓴이(코)</option>
    </select>
	<div class="input-group" style="margin:5px 0px;">
          <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="input-sm form-control required" size="30" maxlength="30" placeholder="검색어를 입력하세요">
          <div class="input-group-btn">
            <button type="submit"  class="btn btn-default btn-sm">검색</button>
		  </div>
        </div>

    </form>
</fieldset>
<!-- } 게시판 검색 끝 -->

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
