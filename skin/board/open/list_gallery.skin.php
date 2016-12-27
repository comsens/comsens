<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<?php if ($is_checkbox) { ?>
    <div id="gall_allchk">
	<label class="btn btn-default btn-sm" for="chkall">
	<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" data-toggle="checkbox"> 전체체크
	</label>
    </div>
    <?php } ?>

    <div id="gall_ul">
        <?php for ($i=0; $i<count($list); $i++) {
            if($i>0 && ($i % $bo_gallery_cols == 0))
                $style = 'clear:both;';
            else
                $style = '';
            if ($i == 0) $k = 0;
            $k += 1;
            if ($k % $bo_gallery_cols == 0) $style .= "margin:0 !important;";
         ?>
        <div class="col-sm-<?php echo $board['bo_gallery_height'] ?> col-md-<?php echo $board['bo_gallery_width'] ?> col-lg-<?php echo $board['bo_gallery_cols'] ?>">
            <?php if ($is_checkbox) { ?>
            <label for="chk_wr_id_<?php echo $i ?>" class="sr-only"><?php echo $list[$i]['subject'] ?></label>
            <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            <?php } ?>
            <span class="sr-only">
                <?php
                if ($wr_id == $list[$i]['wr_id'])
                    echo "<span class=\"bo_current\">열람중</span>";
                else
                    echo $list[$i]['num'];
                 ?>
            </span>
            <div class="thumbnail">
			<a href="<?php echo $list[$i]['href'] ?>" class="thumbnail text-center photo">
                <div class="gall_href text-center">
                    <?php
                    if ($list[$i]['is_notice']) { // 공지사항  ?>
                        <i class="notice fa fa-microphone fa-border tooltip-top" title="공지사항"></i>
                    <?php } else {
                       $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], 500, 350);
					       if($thumb['src']) {
							   $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" class="img-responsive" style="width:100%;">';
                        } else {
                            $img_content = '<span class="fa-stack fa-lg"><i class="fa fa-image fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>';
                        }
                        echo $img_content;
                    }
                     ?>
                </div>
             </a>

                <div class="content caption">
                    <?php
                    // echo $list[$i]['icon_reply']; 갤러리는 reply 를 사용 안 할 것 같습니다. - 지운아빠 2013-03-04
                    if ($is_category && $list[$i]['ca_name']) {
                     ?>
                    <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="cate_label"><?php echo $list[$i]['ca_name'] ?></a>
                    <?php } ?>
                    <a href="<?php echo $list[$i]['href'] ?>">
                        <?php echo  $list[$i]['subject'] = cut_str(strip_tags( $list[$i]['subject']), 15, '…'); ?>
                        <?php if ($list[$i]['comment_cnt']) { ?><span class="sr-only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sr-only">개</span><?php } ?>
                    </a>
                    <?php  if ($list[$i]['icon_new']) {
					echo "<div class='label label-danger new'>NEW</div>";
					 } ?>
                    <?php  if ($list[$i]['icon_hot']) {
					echo  "<div class='label label-info hit'>HOT</div>";
					 } ?>
                </div>
				<hr style="margin:5px 0;">
                <div class="caption clearfix">
				<div class="pull-left namecard"><?php echo $list[$i]['name'] ?></div>
				    <div class="pull-right line-height">
				        <span class="tooltip-top" title="작성일"><i class="fa fa-clock-o fa-fw"></i> <?php echo $list[$i]['datetime2'] ?></span>
						<span class="tooltip-top" title="조회수"><i class="fa fa-comments fa-fw"></i> <?php echo $list[$i]['wr_hit'] ?></span>
				    </div>
				</div>
				<div class="clearfix"></div>
            </div>
        </div>
        <?php } ?>
				<div class="clearfix"></div>
        <?php if (count($list) == 0) { echo "<li class=\"text-center well well-sm\" style=\"list-style:none; margin-top:15px;\">게시물이 없습니다.</li>"; } ?>
    </div>