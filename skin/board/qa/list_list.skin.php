<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

        <table class="panel-body table table-bordered media-table">
        <!--<caption><?php echo $board['bo_subject'] ?> 목록</caption>-->
        <thead>
        <tr>
            <th scope="col" class="text-center">번호</th>
            <?php if ($is_checkbox) { ?>
            <th scope="col">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            </th>
            <?php } ?>
            <th scope="col" class="hidden-xs text-center">제목</th>
            <th scope="col" class="visible-xs text-center">컨텐츠</th>
            <th scope="col" class="hidden-xs text-center">글쓴이</th>
            <th scope="col" class="hidden-xs text-center"><?php echo subject_sort_link('wr_datetime', $qstr2, 1) ?>날짜</a></th>
            <th scope="col" class="hidden-xs text-center"><?php echo subject_sort_link('wr_hit', $qstr2, 1) ?>조회</a></th>
            <?php if ($is_good) { ?><th scope="col" class="hidden-xs text-center"><?php echo subject_sort_link('wr_good', $qstr2, 1) ?>추천</a></th><?php } ?>
            <?php if ($is_nogood) { ?><th scope="col" class="hidden-xs text-center"><?php echo subject_sort_link('wr_nogood', $qstr2, 1) ?>비추천</a></th><?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
         ?>
        <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?> text-center">
            <td class="td_num text-center">
            <?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong><i class="fa fa-microphone tooltip-top" title="공지사항"></i></strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\"><i class='fa fa-eye tooltip-top' title='보고 있는 페이지'></i></span>";
            else
                echo $list[$i]['num'];
             ?>
            </td>
            <?php if ($is_checkbox) { ?>
            <td class="td_chk text-left">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            </td>
            <?php } ?>
            <td class="td_subject text-left">
                <?php
                echo $list[$i]['icon_reply'];
                if ($is_category && $list[$i]['ca_name']) {
                 ?>
                <span class="cate_label"><a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a></span>
                <?php } ?>

                <a href="<?php echo $list[$i]['href'] ?>">
                    <?php echo $list[$i]['subject'] ?>
                    <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sound_only">개</span><?php } ?>
                </a>

                <?php
                if (isset($list[$i]['icon_new'])) echo $list[$i]['icon_new'];
                if (isset($list[$i]['icon_hot'])) echo $list[$i]['icon_hot'];
                if (isset($list[$i]['icon_file'])) echo $list[$i]['icon_file'];
                if (isset($list[$i]['icon_link'])) echo $list[$i]['icon_link'];
                if (isset($list[$i]['icon_secret'])) echo $list[$i]['icon_secret'];
                 ?>

				 <span class="visible-xs well well-sm clearfix" style="margin:15px 0 0 0; line-height: 35px;">
				 <i class="fa fa-calendar-o fa-fw"></i><?php echo $list[$i]['datetime2'] ?>&nbsp;&nbsp; 
				 <i class="fa fa-eye fa-fw"></i><?php echo $list[$i]['wr_hit'] ?>
				 <span class="pull-right" style="line-height: 35px;">
				 <?php echo $list[$i]['name'] ?>
				 </span>
				 </span>
			</td>
            </td>
            <td class="td_name sv_use hidden-xs text-left"><?php echo $list[$i]['name'] ?></td>
            <td class="td_date hidden-xs text-left"><?php echo $list[$i]['datetime2'] ?></td>
            <td class="td_num hidden-xs text-left"><?php echo $list[$i]['wr_hit'] ?></td>
            <?php if ($is_good) { ?><td class="td_numbig hidden-xs text-left"><?php echo $list[$i]['wr_good'] ?></td><?php } ?>
            <?php if ($is_nogood) { ?><td class="td_numbig hidden-xs text-left"><?php echo $list[$i]['wr_nogood'] ?></td><?php } ?>
        </tr>
        <?php } ?>
        </tbody>
        </table>
        <?php if (count($list) == 0) { echo '<p class="text-center well well-sm" style="list-style:none">게시물이 없습니다.</p>'; } ?>
<!-- } 게시판 목록 끝 -->