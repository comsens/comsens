<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
$mb = get_member($view['mb_id']);
// 회원사진 경로
$mb_1_url = G5_DATA_URL.'/member_image/'.substr($member['mb_id'],0,2).'/'.$member['mb_id'].'.gif';
$mb_2_url = G5_DATA_URL.'/member_image/'.substr($mb['mb_id'],0,2).'/'.$mb['mb_id'].'.gif';
$mb_3_url = G5_DATA_URL.'/member_image/member_photo.gif';
$mb_2_path = G5_DATA_PATH.'/member_image/'.substr($mb['mb_id'],0,2).'/'.$mb['mb_id'].'.gif';
$mb_profile = $mb['mb_profile'] ? conv_content($mb['mb_profile'],0) : '자기소개 내용이 없습니다.';
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
?>
<link rel="stylesheet" href="<?php echo $board_skin_url;?>/css/style.css">

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

    <!-- 게시물 상단 버튼 시작 { -->
    <?php ob_start();?>
    <div id="bo_v_top" class="clearfix" style="height:45px;">

        <div class="btn-group pull-left hidden-xs">
			<?php if ($update_href) { ?><a href="<?php echo $update_href ?>" class="btn btn-default btn-sm tooltip-top" title="해당 글을 수정" ><i class="fa fa-gears fa-fw"></i>수정</a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" class="btn btn-default btn-sm tooltip-top" title="해당 글을 삭제"  onclick="del(this.href); return false;"><i class="fa fa-scissors fa-fw"></i>삭제</a><?php } ?>
            <?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" class="btn btn-danger btn-sm tooltip-top" title="해당 글을 복사" onclick="board_move(this.href); return false;"><i class="fa fa-files-o fa-fw"></i>복사</a><?php } ?>
            <?php if ($move_href) { ?><a href="<?php echo $move_href ?>" class="btn btn-danger btn-sm tooltip-top" title="해당 글을 이동"  onclick="board_move(this.href); return false;"><i class="fa fa-exchange fa-fw"></i>이동</a><?php } ?>
            <?php if ($search_href) { ?><a href="<?php echo $search_href ?>" class="btn btn-default btn-sm tooltip-top" title="검색창" ><i class="fa fa-search fa-fw"></i>검색</a><?php } ?>
        </div>
		
		<div class="dropdown visible-xs pull-left">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-cog fa-fw"></i><span class="caret"></span>
			</button>
        			<ul class="dropdown-menu" role="menu" aria-labelledby="gears">
					    <?php if ($update_href) { ?><li role="presentation"><a href="<?php echo $update_href ?>"><i class="fa fa-gears fa-fw"></i> 수정</a></li><?php } ?>
						<?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>"  onclick="del(this.href); return false;"><i class="fa fa-scissors fa-fw"></i> 삭제</a></li><?php } ?>
						<?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-files-o fa-fw"></i> 복사</a></li><?php } ?>
						<?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-exchange fa-fw"></i> 이동</a></li><?php } ?>
						<?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>"><i class="fa fa-search fa-fw"></i> 검색</a></li><?php } ?>
					</ul>
			</div>

        <div class="btn-group pull-right">
            <a href="<?php echo $list_href ?>" class="btn btn-default btn-sm tooltip-top" title="글목록" ><i class="fa fa-list-alt fa-fw"></i>목록</a>
            <?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn btn-default btn-sm tooltip-top" title="해당 글에 답변" ><i class="fa fa-reply fa-rotate-180 fa-fw"></i>답변</a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-default btn-sm tooltip-top" title="글작성" ><i class="fa fa-edit fa-fw"></i>글쓰기</a><?php } ?>
		</div>
    </div>
	
        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    <!-- } 게시물 상단 버튼 끝 -->

<!-- 게시물 읽기 시작 { -->
<div id="bo_v" style="width:<?php echo $width; ?>" class="panel panel-default">
    <div class="panel-heading" style="width: 100%; display: inline-block;">
            <div class="pull-left">
                <h6>
			    <?php
                if ($category_name) echo '<span class="cate_label">'.$view['ca_name'].'</span> '; // 분류 출력 끝
                echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
                ?>
				</h6>
			</div>
			<div class="pull-right">
	     	    <span class="cate_label tooltip-top" title="작성일"><i class="fa fa-clock-o fa-fw"></i> <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></span>
                <span class="cate_label tooltip-top" title="조회수"><i class="fa fa-eye fa-fw"></i> <?php echo number_format($view['wr_hit']) ?>회</span>
		        <span class="cate_label tooltip-top" title="댓글수"><i class="fa fa-comment-o fa-fw"></i> <?php echo number_format($view['wr_comment']) ?>건</span>
			</div>
    </div>
    <div class="panel-body" style="width:100%;">
            <?php if ($view['file']['count']) {
             $cnt = 0;
             for ($i=0; $i<count($view['file']); $i++) {
             if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
             $cnt++;
		     }
		     }?>
		  
		  <?php if($cnt) { ?>

          <!-- 첨부파일 시작 { -->
		   <?php
           // 가변 파일
           for ($i=0; $i<count($view['file']); $i++) {
           if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
           ?>
		   <div class="clearfix well well-sm clearfix">
               <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download tooltip-top" title="다운로드 파일">
                    <i class="fa fa-floppy-o"></i> <?php echo $view['file'][$i]['source'] ?>
                </a>
					<span class="pull-right tooltip-top hidden-xs" title="자료정보">
					<i class="fa fa-download fa-fw"></i><?php echo $view['file'][$i]['download'] ?>회 (<?php echo $view['file'][$i]['size'] ?>)
					<i class="fa fa-calendar"></i> <?php echo $view['file'][$i]['datetime'] ?>
					</span>
		   </div>
           <?php } } ?>
           <!-- } 첨부파일 끝 -->
           <?php } ?>

		   <?php if (implode('', $view['link'])) {?>
		   <ul class="link-group list-group">
           <!-- 관련링크 시작 { -->
		       <?php // 링크
			   $cnt = 0;
			   for ($i=1; $i<=count($view['link']); $i++) {
				   if ($view['link'][$i]) {
					   $cnt++;
					   $link = cut_str($view['link'][$i], 70);
				?>
				<li class="list-group-item">
				    <span class="badge"><strong class="tooltip-top" title="연결 횟수">
					    <i class="fa fa-plus"></i> <?php echo $view['link_hit'][$i] ?></strong>
					</span>
					<a href="<?php echo $view['link_href'][$i] ?>" target="_blank" class="tooltip-top" title="링크주소">
					    <i class="fa fa-link"></i> <?php echo $link ?>
					</a>
				</li>
				<?php } } ?>
			</ul>
			<!-- } 관련링크 끝 -->
			<?php } ?>

    <section id="bo_v_atc" class="panel-body entry-content">
		<?php
        // 파일 출력
        $v_img_count = count($view['file']);
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<=count($view['file']); $i++) {
                if ($view['file'][$i]['view']) {
                    //echo $view['file'][$i]['view'];
                    echo get_view_thumbnail($view['file'][$i]['view']);
                }
            }
            echo "</div>\n";
        } ?>
        <!-- 본문 내용 시작 { -->
        <div id="bo_v_con" style="min-height:200px;">
		    <?php echo get_view_thumbnail($view['content']); ?>
		</div>
        <?php//echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>
        <!-- } 본문 내용 끝 -->
		<div class="clearfix"></div>
		<hr />

		 <div class="well well-sm clearfix">
		     <div class="col-md-4 text-center">
					<!--회원사진-->
						<?php
						//회원사진출력 
						if (file_exists($mb_2_path)) { 
						 echo "<img src='$mb_2_url' alt='회원사진' class='img-circle' style='border:1px solid #ddd;'>";
							 }else{ 
								echo "<img src='$mb_3_url' alt='사진없음' class='img-circle' style='border:1px solid #ddd;'>"; 
						  }
						 ?>
						 <hr style="margin:10px 0;">
							   <?php echo $view['name'] ?><br />
							   <?php if ($is_ip_view) { echo "&nbsp;$ip"; } ?>
							   <div class="clearfix"><br /></div>
								<?php if ($mb['mb_level'])  { ?>
								<span class="cate_label tooltip-top" title="회원레벨">
								Lv : <?php echo $mb['mb_level'] ?>
								</span>&nbsp;
								<?php } else { ?>
								<span class="cate_label tooltip-top" title="비회원">
								Lv : 없음
								</span>&nbsp;
								<?php }?>
								<?php if ($mb['mb_point'])  { ?>
								<span class="cate_label tooltip-top" title="회원포인트">
								Point : <?php echo $mb['mb_point'] ?>
								</span>
								<?php } else { ?>
								<span class="cate_label tooltip-top" title="비회원">
								Point : 없음
								</span>
								<?php }?>
			  </div>
			  <br />

			  <div class="col-md-8">
			  <table class="table table-bordered table-striped">
			      <colgroup>
			    	  <col class="col-xs-3">
		    		  <col class="col-xs-3">
			    	  <col class="col-xs-3">
		    		  <col class="col-xs-3">
				  </colgroup>
				  
				  <tbody>
				  <tr class="hidden-xs">
				     <th scope="row">가입일</th>
				     <td><i class="fa fa-calendar tooltip-top fa-fw" title="회원 가입일" ></i> <?php echo ($mb['mb_level']) ?  substr($mb['mb_datetime'],0,10) ."" : "비회원";  ?></td>
				     <th scope="row">접속일</th>
					 <td><i class="fa fa-calendar-check-o tooltip-top fa-fw" title="마지막 접속일" ></i> <?php echo ($mb['mb_level']) ? substr($mb['mb_today_login'],0,10) : "비회원";?></td>
				  </tr>
				  <tr class="visible-xs">
				     <th scope="row">가입일</th>
				     <td colspan="3"><i class="fa fa-calendar tooltip-top fa-fw" title="회원 가입일" ></i> <?php echo ($mb['mb_level']) ?  substr($mb['mb_datetime'],0,10) ."" : "가입하지 않은 비회원입니다.";  ?></td>
				  </tr>
				  <tr class="visible-xs">
				     <th scope="row">접속일</th>
					 <td colspan="3"><i class="fa fa-calendar tooltip-top fa-fw" title="마지막 접속일" ></i> <?php echo ($mb['mb_level']) ? substr($mb['mb_today_login'],0,10) : "가입하지 않은 비회원입니다.";?></td>
				  </tr>
				  <tr>
				     <th scope="row">서명</th>
					 <td colspan="3"><?php if ($is_signature) { ?><?php echo $signature ?><?php } ?></td>
				  </tr>
				  <tr>
				     <th scope="row">태그</th>
					 <td colspan="3"><?php include_once(G5_TAG_PATH."/view.tag.view.skin.php");?></td>
				  </tr>
				  <tr>
				  <td colspan="4"><?php echo $mb_profile ?></td>
				  </tr>
				  </tbody>
			</table>
			</div>
		</div>

        <?php if ($prev_href || $next_href) { ?>
            <?php if ($prev_href) { ?>
			     <div class="pull-left"><a href="<?php echo $prev_href ?>" class="btn btn-default btn-sm tooltip-top" title="이전글로 이동" ><i class="fa fa-chevron-left fa-fw"></i><span class="hidden-xs">이전글</span></a>
				 </div>
			<?php } ?>
            <?php if ($next_href) { ?>
			     <div class="pull-right"><a href="<?php echo $next_href ?>" class="btn btn-default btn-sm tooltip-top" title="다음글로 이동" ><i class="fa fa-chevron-right fa-fw"></i><span class="hidden-xs">다음글</span></a>
				 </div>
			 <?php } ?>
        <?php } ?>

        <!-- 스크랩 추천 비추천 시작 { -->
        <?php if ($scrap_href || $good_href || $nogood_href) { ?>
        <div id="bo_v_act" class="text-center">
            <?php if ($scrap_href) { ?>
			<a href="<?php echo $scrap_href;  ?>" class="btn btn-default btn-sm tooltip-top" title="이게시물을 보관합니다." onclick="win_scrap(this.href); return false;"><i class="fa fa-archive"></i> <span class="hidden-xs">스크랩</span></a>
			<?php } ?>
            <?php if ($good_href) { ?>
                <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button" class="btn btn-default btn-sm tooltip-top" title="이게시물을 추천합니다"><i class="fa fa-thumbs-o-up"> <span class="hidden-xs">추천</span></i> <span class="badge"><?php echo number_format($view['wr_good']) ?></span></a>
                <b id="bo_v_act_good"></b>
            <?php } ?>
            <?php if ($nogood_href) { ?>
                <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="btn btn-default btn-sm tooltip-top" title="이게시물을 비추천합니다"><i class="fa fa-thumbs-o-down"></i> <span class="hidden-xs">비추천</span> <span class="badge"><?php echo number_format($view['wr_nogood']) ?></span></a>
                <b id="bo_v_act_nogood"></b>
            <?php } ?>
        </div>
        <?php } else {
            if($board['bo_use_good'] || $board['bo_use_nogood']) {
        ?>
        <div id="bo_v_act" class="text-center tooltip-top" title="로그인해야 가능합니다">
            <?php if($board['bo_use_good']) { ?><span class="btn btn-default btn-sm disabled"><i class="fa fa-thumbs-o-up"></i> <span class="hidden-xs">추천</span> <span class="badge"><?php echo number_format($view['wr_good']) ?></span></span><?php } ?>
            <?php if($board['bo_use_nogood']) { ?><span class="btn btn-default btn-sm disabled"><i class="fa fa-thumbs-o-down"></i> <span class="hidden-xs">비추천</span> <span class="badge"><?php echo number_format($view['wr_nogood']) ?></span></span><?php } ?>
        </div>
        <?php
            }
        }
        ?>
        <!-- } 스크랩 추천 비추천 끝 -->
   </div>
</div>

    <?php
    include_once(G5_SNS_PATH."/view.sns.skin.php");
    ?>

    <?php
    // 코멘트 입출력
    include_once('./view_comment.php');
     ?>

    <!-- 링크 버튼 시작 { -->
    <div id="bo_v_bot">
        <?php echo $link_buttons ?>
    </div>
    <!-- } 링크 버튼 끝 -->
    </section>
	<hr>

<!-- } 게시판 읽기 끝 -->

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>
<!-- } 게시글 읽기 끝 -->