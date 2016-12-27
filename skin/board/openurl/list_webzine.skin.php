<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
        <table class="panel-body table table-bordered media-table">
        <thead>
        <tr>
            <th scope="col" class="text-center hidden-xs" style="width:60px;">번호</th>
            <th scope="col" class="text-center table_photo">
				<?php if ($is_checkbox) { ?>
                <label for="chkall" class="sr-only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
				<?php } ?>
				<span class="hidden-xs">이미지</span></th>
            <th scope="col" class="text-center hidden-xs" style="width:70%;">내용</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
         ?>
        <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
            <td class="text-center hidden-xs">
            <?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong>공지</strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\">열람중</span>";
            else
                echo $list[$i]['num'];
             ?>
            </td>
			<td>
            <?php if ($is_checkbox) { ?>
                <label for="chk_wr_id_<?php echo $i ?>" class="sr-only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            <?php } ?>
				<a href="<?php echo $list[$i]['href'] ?>" class="thumbnail text-center photo">
				<?php
				$thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], 400, 250);
                if($thumb['src']) {
					$img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" style="width:100%;">';
                } else {
                            $img_content = '<span class="fa-stack fa-lg"><i class="fa fa-image fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>';
                }
				echo $img_content;
				?>
				</a>
				
				<div class="visible-xs">
				<hr style="margin:10px 0;">
				   <div class="well well-sm">
                <?php
                echo $list[$i]['icon_reply'];
                if ($is_category && $list[$i]['ca_name']) {
                 ?>
                <span class="cate_label"><a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a></span>
                <?php } ?>

                <a href="<?php echo $list[$i]['href'] ?>">
                    <?php echo $list[$i]['subject'] ?>
                    <?php if ($list[$i]['comment_cnt']) { ?><span class="sr-only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sr-only">개</span><?php } ?>
                </a>

                <?php
                if (isset($list[$i]['icon_new'])) echo $list[$i]['icon_new'];
                if (isset($list[$i]['icon_hot'])) echo $list[$i]['icon_hot'];
                if (isset($list[$i]['icon_file'])) echo $list[$i]['icon_file'];
                if (isset($list[$i]['icon_link'])) echo $list[$i]['icon_link'];
                if (isset($list[$i]['icon_secret'])) echo $list[$i]['icon_secret'];
                 ?>
				 </div>
				 <p><?php echo $wr_content = cut_str(strip_tags($list[$i][wr_content]), 200, '…'); ?></p>
				 <?php echo $list[$i]['name'] ?>
				 <span class="pull-right">
					<span class="tooltip-top" title="" data-original-title="작성일"><i class="fa fa-calendar-o fa-fw"></i> <?php echo $list[$i]['datetime2'] ?></span>
					<span class="tooltip-top" title="" data-original-title="조회수"><i class="fa fa-eye fa-fw"></i> <?php echo $list[$i]['wr_hit'] ?></span>
	                <?php if ($is_good) { ?>
					<span class="tooltip-top" title="" data-original-title="추천수">
					<i class="fa fa-thumbs-o-up"></i> <strong><?php echo $list[$i]['wr_good'] ?></strong></span>
					<?php } ?>
		            <?php if ($is_nogood) { ?>
					<span class="tooltip-top" title="" data-original-title="비추천수">
					<i class="fa fa-thumbs-o-down"></i> <strong><?php echo $list[$i]['wr_nogood'] ?></strong></span>
					<?php } ?>
				 </span>
				</div>
			</td>
            <td class="td_subject hidden-xs">
				   <div class="well well-sm">
                <?php
                echo $list[$i]['icon_reply'];
                if ($is_category && $list[$i]['ca_name']) {
                 ?>
                <span class="cate_label"><a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a></span>
                <?php } ?>

                <a href="<?php echo $list[$i]['href'] ?>">
                    <?php echo $list[$i]['subject'] ?>
                    <?php if ($list[$i]['comment_cnt']) { ?><span class="sr-only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sr-only">개</span><?php } ?>
                </a>

                <?php
                if (isset($list[$i]['icon_new'])) echo $list[$i]['icon_new'];
                if (isset($list[$i]['icon_hot'])) echo $list[$i]['icon_hot'];
                if (isset($list[$i]['icon_file'])) echo $list[$i]['icon_file'];
                if (isset($list[$i]['icon_link'])) echo $list[$i]['icon_link'];
                if (isset($list[$i]['icon_secret'])) echo $list[$i]['icon_secret'];
                 ?>
				 </div>
				 <?php echo $wr_content = cut_str(strip_tags($list[$i][wr_content]), 200, '…'); ?>
				 <hr style="margin:10px 0;">
				    <div class="pull-left">
					<?php echo $list[$i]['name'] ?>
					</div>

					<div class="pull-right">
					<span class="tooltip-top" title="" data-original-title="작성일">
					<i class="fa fa-clock-o fa-fw"></i> <?php echo $list[$i]['datetime2'] ?></span>
					<span class="tooltip-top" title="" data-original-title="조회수">
					<i class="fa fa-eye fa-fw"></i> <?php echo $list[$i]['wr_hit'] ?></span>
	                <?php if ($is_good) { ?>
					<span class="tooltip-top" title="" data-original-title="추천수">
					<i class="fa fa-thumbs-o-up"></i> <strong><?php echo $list[$i]['wr_good'] ?></strong></span>
					<?php } ?>
		            <?php if ($is_nogood) { ?>
					<span class="tooltip-top" title="" data-original-title="비추천수">
					<i class="fa fa-thumbs-o-down"></i> <strong><?php echo $list[$i]['wr_nogood'] ?></strong></span>
					<?php } ?>
					</div>
            </td>
        </tr>
        <?php } ?>
        
        </tbody>
        </table>
		<?php if (count($list) == 0) { echo '<p  class="text-center well well-sm" style="list-style:none">게시물이 없습니다.</p>'; } ?>
<!-- } 게시판 목록 끝 -->
