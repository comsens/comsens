<?php
include_once("./_common.php");
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

use IPTools\Range;
use IPTools\Network;
use IPTools\IP;

?>

<?php if($is_dhtml_editor) { ?>
	<style>
		#wr_content { border:0; display:none; }
	</style>
<?php } ?>


<?php

//print_r($_POST);

//$bo_table = $_GET['bo_table'];
$write_table = $g5['write_prefix'].$bo_table;
//$wr_id = $_POST['event_id'];



$contents =$_POST['wr_import'];
define("SEPERATOR_ROW", "\n");



if(($contents = trim($contents)) !=""){
	//
	$chk_cnt  = 0;
	$data = explode(SEPERATOR_ROW, $contents);

	foreach($data as $key => $value){

	    $aRow = explode("	", $value);
	    foreach($aRow as $sKey => $sValue) {

		    //$sValue         = str_replace('"', "", $sValue);
		    $sValue         = addslashes($sValue);
		    $aRow[$sKey]    = $sValue;

	    }



	list($ca_name,$wr_link1,$wr_link2) = $aRow;
 

	// 중복체크
	$sql = " select * from {$write_table} where wr_link1 ='{$wr_link1}' and wr_link2 ='{$wr_link2}'  ";

	$row = sql_fetch($sql);
	if ($row['wr_link1']) {//중복확인
		echo $row['wr_link1']." 중복<br>";
	}else{	//중복이 없다면 입력
		echo $ip_raw." 입력<br>";
 

		if ($member[mb_id])
		{
			$mb_id = $member[mb_id];
			$wr_name = $board[bo_use_name] ? $member[mb_name] : $member[mb_nick];
			$wr_password = $member[mb_password];
			$wr_email = $member[mb_email];
			$wr_homepage = $member[mb_homepage];
		}else{
			$mb_id = "";
			// 비회원의 경우 이름이 누락되는 경우가 있음
			$wr_name = strip_tags(sql_escape_string($_POST['wr_name']));
		if (!trim($wr_name))
			alert("이름은 필히 입력하셔야 합니다.");
			$wr_password = sql_password($wr_password);
		}

		if($w == "r")
		{
			// 답변의 원글이 비밀글이라면 패스워드는 원글과 동일하게 넣는다.
			if ($secret)
			$wr_password = $wr[wr_password];
			$wr_id = $wr_id.$reply;
			$wr_num = $write[wr_num];
			$wr_reply = $reply;
		}else{
			$wr_num = get_next_num($write_table);
			$wr_reply = "";
		}
		
		$wr_subject= $wr_link2;
		$wr_content= $wr_link1."<br>".$wr_link2;
		$html= "html1";

		$wr_datetime = ($_POST['wr_datetime']) ? $_POST['wr_datetime'].' '.date('H:i:s') : G5_TIME_YMDHIS ;

		$str  = " insert into $write_table
		set wr_num = '$wr_num',
		wr_reply = '$wr_reply',
		wr_is_comment = 0,
		wr_comment = 0,
		ca_name = '$ca_name',
		wr_option = '$html,$secret,$mail',
		wr_subject = '$wr_subject',
		wr_content = '$wr_content',
		wr_link1 = '$wr_link1',
		wr_link2 = '$wr_link2',
		wr_link1_hit = 0,
		wr_link2_hit = 0,
		wr_hit = 0,
		wr_good = 0,
		wr_nogood = 0,
		mb_id = '$mb_id',
		wr_password = '$wr_password',
		wr_name = '$wr_name',
		wr_email = '$wr_email',
		wr_homepage = '$wr_homepage',
		wr_datetime = '$wr_datetime',
		wr_last = '".G5_TIME_YMDHIS."',
		wr_ip = '{$_SERVER['REMOTE_ADDR']}',
		wr_1 = '$wr_1',
		wr_2 = '$wr_2',
		wr_3 = '$wr_3',
		wr_4 = '$wr_4',
		wr_5 = '$wr_5',
		wr_6 = '$wr_6',
		wr_7 = '$wr_7',
		wr_8 = '$wr_8',
		wr_9 = '$wr_9',
		wr_10 = '$wr_10' 

		";
		//echo $str ;
 
		$result = sql_query($str);
		//echo $str;


		//$wr_id = @mysql_insert_id(); //그누보드 g5 구버전에서 입력 오류시 사용
		$wr_id = sql_insert_id();

		// 부모 아이디에 UPDATE
		sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

		// 새글 INSERT
		//sql_query(" insert into $g5[board_new_table] ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$member['mb_id']}' ) ");

		// 게시글 1 증가
		sql_query("update $g5[board_table] set bo_count_write = bo_count_write + 1 where bo_table = '$bo_table'");

		//echo json_encode(array('success'=>true, 'event'=>$list));exit;

 
		$chk_cnt++;


		}//if(foreach


	}//중복 체크


}//if(contents!=""

?>

<div id="bo_w" class="write-wrap<?php echo (G5_IS_MOBILE) ? ' font-14' : '';?>">
    <div class="well">
		<h2><?php echo $g5['title'] ?></h2>
	</div>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" role="form" class="form-horizontal">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

	<div class="form-group">
		<div class="col-sm-12">
			<textarea id="wr_import" name="wr_import" style="width:100%;height:400px;"></textarea>
		</div>
	</div>


    <div class="text-center write-btn pull-center">
        <button type="submit" id="btn_submit" accesskey="s" class="btn btn-default btn-sm"><i class="fa fa-check"></i> <b>작성완료</b></button>
        <a href="./board.php?bo_table=<?php echo $bo_table ?>"  class="btn btn-default btn-sm" role="button">취소</a>
    </div>
 
	<div class="clearfix"></div>

	</form>

    <script>
    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });
    <?php } ?>

	function html_auto_br(obj) {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f) {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }

	$(function(){
		$("#wr_content").addClass("form-control input-sm write-content");
	});




	//URL 미리보기 API
    function get_ip_meta(val, handler) {
        $.ajax({
            url: "<?php echo G5_URL;?>/ajax/get_ip_meta.php",
            dataType: "JSON",
            type: "POST",
            data: "val="+val,
            /*
             data: {
             url: url,
             mode: mode
             },
             */
            success: function (result, textStatus, jqXHR) {
                var code = result.result_code;
                handler(result);




            },
            error: function (jqXHR, textStatus) {
                console.log( -1, textStatus, jqXHR);
            }
        });
    }

    var addLinkKeyStatus = true;
//URL 미리보기 이벤트
    function getUrlSummaryEvent(s, e) {

        var $input = $(s);


        addLinkKeyTimer = setTimeout(function () {

            if (addLinkKeyStatus == true) {

                var ip_val= $input.val().trim();

                //addLinkKeyStatus = false;
                console.log(ip_val);

                //$loader.show();
                //console.log($input.val().length );
                console.log(addLinkKeyStatus);
                console.log('URL LODING.....')


                get_ip_meta(encodeURIComponent(ip_val), function (result) {
                    //$rss_subject.val("");
                    console.log(result);
                    if (result.code == true) {


                        $('#ip_start').val(result.summary.firstIP);
                        $('#ip_end').val(result.summary.lastIP);
                        $('#ip_start_long').val(result.summary.firstIPLong);
                        $('#ip_end_long').val(result.summary.lastIPLong);
                        addLinkKeyStatus = true;
                        //$loader.hide();

                        $('#ip_info_section').show();


                    } else {
                    	if (result.status) {
                    		$('#resultview').html(result.status);
                    	}else {
                        	$('#resultview').html("미리보기를 불러올수 없습니다");
                    	}
                        addLinkKeyStatus = true;
                        //$loader.hide();
                        $input.removeClass("fa-spinner");
                    }
                });


            }
        }, 1000);
    }








    </script>
</div>
<!-- } 게시물 작성/수정 끝 -->
