<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 가변 파일
$file_script = "";
$file_length = -1;
// 수정의 경우 파일업로드 필드가 가변적으로 늘어나야 하고 삭제 표시도 해주어야 합니다.
if ($w == "u")
{
    for ($i=0; $i<$file[count]; $i++)
    {
        $row = sql_fetch(" select bf_file, bf_content from $g5[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
        if ($row[bf_file])
        {
            $file_script .= "add_file(\"<div class='form-group input-group pull-left col-md-3'><i class='fa fa-reply fa-fw fa-rotate-180'></i><span class='input-group-addon tooltip-top' title='' for='file-del' data-original-title='체크시 첨부된파일 삭제'><input type='checkbox' name='bf_file_del[$i]' value='1' id='file-del' maxlength='255'></span><a href='{$file[$i][href]}' class='btn btn-default file-del tooltip-top' title='현재 첨부된 파일 (다운로드가능)' maxlength='255'>{$file[$i][source]}({$file[$i][size]})</a></div>\");";
        }
        else
            $file_script .= "add_file('');\n";
    }
    $file_length = $file[count] - 1;
}

if ($file_length < 0)
{
    $file_script .= "add_file('');\n";
    $file_length = 0;
}
?>
<link rel="stylesheet" href="<?php echo $board_skin_url;?>/css/style.css">

<section id="bo_w">
      
    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
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
    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= "\n".'<label class="btn btn-default btn-sm popover-top" title="" data-content="글이 작성되면 공지사항으로 표시됩니다." data-original-title="공지사항" for="notice"><input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.' data-toggle="checkbox">'."\n".'공지</label>';
        }

        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= "\n".'<label class="btn btn-default btn-sm popover-top" title="" data-content="글작성시 HTML 태그를 이용하실수 있습니다." data-original-title="HTML 태그허용" for="html"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.' data-toggle="checkbox">'."\n".'html</label>';
            }
        }

        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= "\n".'<label class="btn btn-default btn-sm popover-top" title="" data-content="관리자와 자신만이 확인이 가능합니다." data-original-title="비밀글" for="secret"><input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.' data-toggle="checkbox">'."\n".'비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }

        if ($is_mail) {
            $option .= "\n".'<label class="btn btn-default btn-sm active  popover-top"  title="" data-content="내글에 답변이 달리면 메일로 알림받기" data-original-title="답변메일받기" for="mail"><input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.' data-toggle="checkbox">'."\n".'답변메일받기</label>';
        }
    }

    echo $option_hidden;
    ?>
<div class="panel panel-default">
    <div class="panel-heading">
		<h5><i class="fa fa-bar-chart-o"></i> <?php echo $g5['title'] ?></h5>
    </div>
    <div class="panel-body">
        <table style="width:100%;">
        <tbody>

        <?php if ($option) { ?>
		        <div class="form-group"><?php echo $option ?></div>
        <?php } ?>

			<?php if ($is_name) { ?>
			   <div class="form-group input-group  col-md-3 pull-left">
			      <span class="input-group-addon tooltip-top" title="이름"  for="wr_name"><i class="fa fa-user"></i><strong class="sr-only">이름 필수</strong></span>
			      <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="form-control required" size="10" maxlength="20"  placeholder="이름">
			   </div>
            <?php } ?>
			<?php if ($is_password) { ?>
			   <div class="form-group input-group  col-md-3 pull-left">
			      <span class="input-group-addon tooltip-top" title="패스워드"  for="wr_password"><i class="fa fa-lock"></i><strong class="sr-only">패스워드 필수</strong></span>
                  <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="form-control <?php echo $password_required ?> pc-input" maxlength="20"  placeholder="패스워드">
			   </div>
            <?php } ?>
			<?php if ($is_email) { ?>
			   <div class="form-group input-group  col-md-3 pull-left">
			      <span class="input-group-addon tooltip-top" title="이메일"  for="wr_email"><i class="fa fa-envelope-o"></i><strong class="sr-only">이메일</strong></span>
                  <input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="form-control email pc-input" size="50" maxlength="100"  placeholder="이메일">
			   </div>
            <?php } ?>
            <?php if ($is_homepage) { ?>
			   <div class="form-group input-group  col-md-3 pull-left">
			      <span class="input-group-addon tooltip-top" title="홈페이지"  for="wr_homepage"><i class="fa fa-home"></i><strong class="sr-only">홈페이지</strong></span>
                  <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="form-control pc-input" size="50"  placeholder="홈페이지">
			   </div>
            <?php } ?>

			<?php if ($is_category) { ?>
			   <div class="form-group input-group  col-md-3 pull-left">
			      <span class="input-group-addon tooltip-top" title="분류"   for="ca_name"><i class="fa fa-th-list"></i><strong class="sr-only">분류 필수</strong></span>
                  <select name="ca_name" id="ca_name" required class="form-control required pc-input">
                    <option value="">선택하세요</option>
                    <?php echo $category_option ?>
                    </select>
			   </div>
            <?php } ?>

			   <div id="autosave_wrapper" class="form-group input-group  col-md-12">
			      <span class="input-group-addon tooltip-top" title="제목" for="wr_subject"><i class="fa fa-keyboard-o"></i><strong class="sr-only">제목 필수</strong></span>
                  <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="form-control required" size="50" maxlength="255" placeholder="제목" >
                    <?php if ($is_member) { // 임시 저장된 글 기능 ?>
                    <span class="input-group-addon tooltip-top btn btn-default" title="임시저장된글 <?php echo $autosave_count; ?>개" for="wr_subject" id="btn_autosave" style="width:120px;">
					<script src="<?php echo G5_JS_URL; ?>/autosave.js"></script><i class="fa fa-paste"></i>
					<?php } ?>
					</span>
				</div>
                
					<div id="autosave_pop" style="display: none;">
                        <div class="alert alert-info clearfix"><strong>임시 저장된 글 목록</strong>
						<button type="button" class="autosave_close close" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                        <ul class="list-group"></ul>
                    </div>
					
        <div class="row">
		        <div class="form-group col-md-12">
                <?php if($write_min || $write_max) { ?>
                <!-- 최소/최대 글자 수 사용 시 -->
                <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                <?php } ?>
                <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                <?php if($write_min || $write_max) { ?>
                <!-- 최소/최대 글자 수 사용 시 -->
                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                <?php } ?>
                </div>
        </div>
		<?php include_once(G5_TAG_PATH."/view.tag.write.skin.php");?>
		
		<div class="clearfix"></div>

			<?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
			   <div class="form-group input-group  col-md-6 pull-left">
			      <span class="input-group-addon tooltip-top" title="링크 <?php echo $i ?>번째"  for="wr_link<?php echo $i ?>"><i class="fa fa-link fa-fw"></i>#<?php echo $i ?><strong class="sr-only">링크</strong></span>
                  <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo$write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="form-control"placeholder="링크주소" maxlength="255">
				</div>
            <?php } ?>

		<div id="variableFiles"></div>
        <?php if ($is_guest) { //자동등록방지  ?>
        <div class="row">
		        <div class="form-group col-md-12">
                <?php //echo $captcha_html ?>
				</div>
		</div>
        <?php } ?>

        </tbody>
        </table>
    </div>

    <div class="text-center" style="margin:20px;">
        <input type="submit" value="확인" id="btn btn-primary" accesskey="s" class="btn btn-default btn-sm">
        <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn btn-default btn-sm">취소</a>
    </div>
    </form>
</div>
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
    function html_auto_br(obj)
    {
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

    function fwrite_submit(f)
    {
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

        <?php //echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  
		?>

        document.getElementById("btn btn-primary").disabled = "disabled";

        return true;
    }
	
var flen = 0;
function add_file(delete_code)
{
	 var upload_count = <?=(int)$file_count?>;
	if (upload_count && flen >= upload_count)
	{
		alert("이 게시판은 "+upload_count+"개 까지만 파일 업로드가 가능합니다.");
		return;
	}

	var objDiv = null;
	var objLi = null;
	objDiv = document.getElementById("variableFiles");
	objLi = document.createElement("dt");

	objLi.innerHTML = "<div class=\"form-group input-group pull-left col-md-12\"><span class=\"input-group-addon tooltip-top\" title=\"파일첨부 "+(flen+1)+"번째\" for=\" "+(flen+1)+"\"><i class=\"fa fa-floppy-o fa-fw\"></i>#"+(flen+1)+"<strong class=\"sr-only\">파일첨부</strong></span><input type=\"file\" name=\"bf_file[]\" class=\"form-control file-input\" onKeyDown=\"return false\" title=\"파일첨부 "+(flen+1)+" : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능\" maxlength='255'><span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"button\" onclick=\"add_file();\"><i class=\"fa fa-plus-square\"></i></button></span></div>"; 

	if (delete_code)
		objLi.innerHTML += ""+delete_code;
	objDiv.appendChild(objLi);

	flen++;
}

	<?=$file_script; //수정시에 필요한 스크립트?>

function del_file()
{
	// file_length 이하로는 필드가 삭제되지 않아야 합니다.
	var file_length = <?=(int)$file_length?>;
	var objDiv = document.getElementById("variableFiles");
	if (objDiv.childNodes.length - 1 > file_length)
	{
		objDiv.removeChild(objDiv.lastChild);
		flen--;
	}
}
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->