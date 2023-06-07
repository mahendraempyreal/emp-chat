//hostURL = "http://localhost:3000";
hostURL = "https://drlogy.com:3003";
// creating io instance
var io = io(hostURL);
var glbl_chat_id = "";
var glbl_recever_id = "";
var glbl_sender_id = $("#glbl_sender_id").val();
var pdfimgurl = $("#pdfimgurl").val();
var receiver = ""; var sender = "";
var defltIMG=$(".pc-clickableshow-right .pc-perticuler-indiv .pc-perticuler-img img").attr('src');

//Init Socket
setTimeout(function(){
	io.emit("user_connected", glbl_sender_id);
}, 2000);
$("body").on("click","#list_chat_fs .pc-chatingbyperson, #list_chat_ss .pc-chatingbyperson", function(e){
	_th = $(this);
	clfs = 'pc-clickable-show'; mid = 'list_chat_fs';
	targetDv = $(".pc-clickable-show").find('.pc-perticuler-indiv');
	nmcl = 'pc-perticuler-fullmiddle';
	if(_th.closest('#list_chat_ss').length){
		clfs = 'pc-clicabale-hide'; mid = 'list_chat_ss';
		targetDv = $(".pc-clicabale-hide").find('.pc-chatdis-perticuler');
		nmcl = 'pc-perticuler-middle';
	}
	$("#"+mid+" .pc-chatingbyperson").removeClass('pc-fullledchatong-curser');
	_th.addClass('pc-fullledchatong-curser');
	chat_id = _th.data("chat_id");
	user_id = _th.data("user_id");
	receiver = _th.find('.pc-chpero-middel').find('h6 b').text();

	is_span = false;
	if(_th.find('.pc-chper-yes').find('p:eq(1)').find('span').length > 0){
		is_span = true;
		_th.find('.pc-chper-yes').find('p:eq(1)').find('span').remove();
	}

	jsonstsp = _th.find('.cht_dt').html().trim();
	jsonstsp = $.parseJSON(jsonstsp);
	gender = '';dob = ''; city = ''; img = defltIMG;
	if(typeof jsonstsp.gender !== 'undefined'){
		gender = jsonstsp.gender;
		dob = jsonstsp.dob;
		city = jsonstsp.city;
		img = jsonstsp.img;
	}
	targetDv.find('.'+nmcl).find('h6 b').text(receiver);
	targetDv.find('p.pc-chat-fullgendc').find('span:eq(0)').text(gender);
	targetDv.find('p.pc-chat-fullgendc').find('span:eq(1)').text(dob);
	targetDv.find('p.pc-chat-fullgendc').find('span:eq(2)').text(city);
	targetDv.find('.pc-perticuler-img img').attr('src', img);
	
	$("."+clfs).find('.pc-clickableshow-right').find('#chat_sel_user').val(user_id);

	glbl_recever_id = user_id;
	glbl_chat_id = chat_id;
	objct = { user_id : user_id, chat_id : chat_id };
	if(clfs == 'pc-clicabale-hide'){
		objct.is_small = 1;
	}
	if(is_span){
		objct.set_read = '1';
	}
	//io.emit("user_connected", glbl_recever_id);
	//io.emit("user_connected", glbl_sender_id);
	$.when(chat_ajax_req("Chat/get_sin_chat_det", objct)).done(function(data){
		$("."+clfs).find("input[name='ct_mn_tp']").val(data.total_pages);
		$("."+clfs).find("input[name='ct_mn_p']").val(data.page);
		if(data.is_set_read){
			readdd = { is_all : 1, sender_id : user_id };
			io.emit("read_message", readdd);
		}
		$("."+clfs).find('.pc-chatingmain-bottom').show();
		$("."+clfs).find('.pc-fullsendrec-bottom').show();
		if(data.is_cond){
			if(data.is_chat == 0){
				if(clfs == 'pc-clicabale-hide'){
					$("."+clfs).find('.pc-chatingmain-bottom').hide();
				} else {
					$("."+clfs).find('.pc-fullsendrec-bottom').hide();
				}
			}
		}
		$("."+clfs+" .pc-chatincontent-person").html(data.htm);
		setTimeout(function(){
			hight = $('.'+clfs+' .pc-seding-recivinghidet-set')[0].scrollHeight;
			$('.'+clfs+' .pc-seding-recivinghidet-set').scrollTop(hight);
		}, 700);
	});
});
$('.pc-seding-recivinghidet-set').scroll(function(){
	_th = $(this);
    if (_th.scrollTop() == 0){
    	clfs = 'pc-clickable-show';
		if(_th.closest('.pc-clicabale-hide').length){
			clfs = 'pc-clicabale-hide';
		}
		var total_pages = parseInt($("."+clfs).find("input[name='ct_mn_tp']").val());
	    var page = parseInt($("."+clfs).find("input[name='ct_mn_p']").val())+1;
		
		if(page <= total_pages) {
			sendd = { page : page,clfs:clfs }
			get_chat_det_page(sendd);
		}
    }
});
/* 27-04-2020 */
function get_chat_det_page(sendd = {}) {
	clfs = sendd.clfs;
	sendd.user_id = glbl_recever_id;
	sendd.chat_id = glbl_chat_id;
	if(clfs == 'pc-clicabale-hide'){
		sendd.is_small = 1;
	}
	$.when(chat_ajax_req("Chat/get_sin_chat_det", sendd)).done(function(data){
		$("."+clfs).find("input[name='ct_mn_tp']").val(data.total_pages);
		$("."+clfs).find("input[name='ct_mn_p']").val(data.page);
		
		prevh = $('.'+clfs+' .pc-seding-recivinghidet-set')[0].scrollHeight;
    	$("."+clfs+" .pc-chatincontent-person").prepend(data.htm);
    	prevhaf = $('.'+clfs+' .pc-seding-recivinghidet-set')[0].scrollHeight;
    	nssht = prevhaf - prevh;
    	$('.'+clfs+' .pc-seding-recivinghidet-set').scrollTop(nssht);
	});
}
// listen from server
io.on("user_connected", function (username) {
	//When User Connected
});

// listen from server
io.on("new_message", function (data) {
	console.log(data);
	//console.log(glbl_recever_id);
	//console.log(data.sender_id);
	if(glbl_recever_id != "" && parseInt(data.sender_id) == glbl_recever_id){
		html = get_htm(data,'');
		$(".pc-chatincontent-person").append(html);
		io.emit("read_message", data);
	} else {
		nmsgdv = $("#list_chat_ss .pc-chatingbyperson[data-user_id='"+data.sender_id+"']").find('.pc-chper-yes');
		if(nmsgdv.length > 0){
			if(nmsgdv.find('span').length > 0){
				var cmsg = nmsgdv.find('span').text().trim();
				cmsg = parseInt(cmsg) + 1;
				nmsgdv.find('span').text(cmsg);
			} else {
				nmsgdv.append('<span>1</span>');
			}
		}
	}
});
/* Typing */
io.on("is_typing", function (data) {
	pdv = $(".pc-chatdis-perticuler").find('.pc-perticuler-middle');
	name = pdv.find('h6 b').text().trim();
	pdv.find('p.typing').removeClass('hidden');
	pdv.find('p.pc-chat-genderc').addClass('hidden');
	pdv.find('p.typing').text("typing...");//name+
});
io.on("stop_typing", function (data) {
	pdv = $(".pc-chatdis-perticuler").find('.pc-perticuler-middle');
	pdv.find('p.pc-chat-genderc').removeClass('hidden');
	pdv.find('p.typing').addClass('hidden');
	pdv.find('p.typing').text("");
});
/* 28-04-2020 */
io.on("read_message", function (data) {
	if(glbl_sender_id != "" && parseInt(data.sender_id) == glbl_sender_id){
		
		if(typeof data.is_all !== 'undefined' && data.is_all != ""){//[data-read="0"]
			pdv = $(".pc-chatincontent-person").find('.cht-dv-11').find('span').find('.material-icons');
			pdv.each(function(ind, el) {
				$(el).text('done_all');
			});
		} else {
			id = data.id;
			pdv = $(".pc-chatincontent-person").find('.cht-dv-11[data-id="'+id+'"]').find('span').find('.material-icons');
			if(pdv.length > 0 && (pdv.text().trim() == 'done' || pdv.text().trim() == 'donedone')){
				pdv.text('done_all');
			}
			$.when(chat_ajax_req("Chat/read_message", { id:data.id })).done(function(data2){
				//Success
			});
		}
	} else if(glbl_sender_id != "" && glbl_sender_id != data.receiver_id){

	}
});

/* 25-04-2020 */
io.on("delete_message", function (data) {
	if(glbl_recever_id != "" && parseInt(data.sender_id) == glbl_recever_id){
		id = data.id;
		pdv = $(".pc-chatdis-perticuler").find('.pc-chatincontent-person').find('.cht-dv-11[data-id="'+id+'"]');
		if(pdv.length > 0){
			pdv.remove();
		}
	}
});

$("#chat_msg_fs, #chat_msg_ss").keyup(function(e) {
	$(this).closest('div').find('span.invalid').remove();
	if(!e.shiftKey && e.which == 13){
		if($(this).val().trim().length > 0){
			if($(this).val().trim().length <= 10000){
				sendMessage($(this).attr('id'));
			} else {
				$(this).after('<span class="invalid">Max 10000 Character Allowed</span>');
				return false;
			}
		}
	} else {
		sendd = { sender_id:glbl_sender_id, receiver_id:glbl_recever_id };
		io.emit("is_typing", sendd);
	}
});
$("#chat_msg_fs, #chat_msg_ss").blur(function(e) {
	sendd = { sender_id:glbl_sender_id, receiver_id:glbl_recever_id };
	io.emit("stop_typing", sendd);
});
$(".btn_msg_send").click(function(e) {
	if($(this).closest('form').find('#chat_msg_fs').length > 0 && $(this).closest('form').find('#chat_msg_fs').val().trim() != ""){
		sendMessage("chat_msg_fs");
	} else if($(this).closest('form').find('#chat_msg_ss').length > 0 && $(this).closest('form').find('#chat_msg_ss').val().trim() != ""){
		sendMessage("chat_msg_ss");
	}
});
function sendMessage(inpnm = 'chat_msg') {
	var message = $("#"+inpnm).val().trim();
	/*sendd = {
	  chat_id: glbl_chat_id,
	  sender_id: glbl_sender_id,
	  receiver_id: glbl_recever_id,
	  message: message,
	  type: "0"
	};*/
	var formData = new FormData();
	
	formData.append('chat_id', glbl_chat_id);
	formData.append('sender_id', glbl_sender_id);
	formData.append('receiver_id', glbl_recever_id);
	formData.append('type', '0');
	formData.append('message', message);

	$.when(ajax_request_post("Chat/send_message", formData)).done(function(data){
		html = get_htm(data,'pc-perticuler-reciving');
		$(".pc-chatincontent-person").append(html);
		io.emit("send_message", data);
		$("#"+inpnm).val("");
		$("#"+inpnm).css("height","20px");
	});
	
	// prevent form from submitting
	return false;
}
var imgpath = $("#chtimgurl").val();
var pdffilepath = $("#chtfileurl").val();
function get_htm(data , cl='') {
	console.log("");
	var html = "";
	created_at = (typeof data.created_at !== 'undefined')?data.created_at:get_cur_time(true);
	id = (typeof data.id !== 'undefined')?data.id:"";
	html += '<div class="cht-dv-11" data-id="'+id+'"><div class="pc-perticuler-sending pc-perticuler-sendingfull jut-card '+cl+' clearfix">';
		if(data.type == "0"){
			message = data.message;
			message = message.toString();
			message = message.replace("\r\n", "<br />\r\n");
			html += '<p>'+message+'</p>';
		} else if(data.type == "1"){
			imgs = data.message;
			imgs = imgs.toString();
			var img_strig = imgs;
			imgs = imgs.split("|");

			if(imgs.length > 0){

				$toti = $maxlp = imgs.length;$rem = 0;
				$class1 = "ct-ig-out2"; $class2 = "ct-ig-inn2";
				if($toti >= 4){
					$class1 = "ct-ig-out1"; $class2 = "ct-ig-inn1";
					$rem = $toti - 4;
					$maxlp = 4;
				}
				html += '<div class="igdis '+$class1+'" data-img="'+img_strig+'">';
				for (var i = 0; i < $maxlp; i++) {
					html += '<div class="'+$class2+'">';

					ext = imgs[i].substring(imgs[i].indexOf(".")+1);
					if(ext == 'pdf'){
						html += '<a href="'+imgpath+imgs[i]+'" target="_blank"><img class="is_pdf" src="'+pdfimgurl+'" /></a>';
					} else {
						html += '<img src="'+imgpath+imgs[i]+'" />';
					}
					html += '</div>';
				}
				if($rem > 0){
					html += '<span class="mor_img">+'+$rem+'</span>';
				}
				html += '</div>';
			}
		} else {
			html += '<p><a href="'+pdffilepath+data.message+'" target="_blank">'+data.message+'</a></p>';
		}
		//ctime = moment().fromNow();
		html += '<span>'+created_at+' ';
			if(cl != ""){
				html += '<i class="material-icons">done</i>';
			}
		html += '</span>';
		if(cl != ""){
			html += '<div class="chp-03">';
			html += '<i class="material-icons">keyboard_arrow_down</i>';
			html += '<div class="jut-card chp-02">';
				html += '<ul>';
					//html += '<li>Reply</li>';
					html += '<li class="del_msg">Delete</li>';
				html += '</ul>';
			html += '</div></div>';
		}
	html += '</div></div>';
	return html;
}

//$(".clk_op_fil").click(function(event) {
$(".csx-lvx2 .chat_image").click(function(event) {
	$(this).closest('.chtat1').find("input[name='file[]']").trigger('click');
});
/* 30-04-2020 Upload PDF File In Chat MSG */
$(".csx-lvx2 .chat_pdf").click(function(event) {
	$(this).closest('.chtat1').find("input[name='file_pdf']").trigger('click');
});
/* 24-04-2020 Upload File In Chat MSG */
$(".wb_cht_pdv input[name='file[]'], .wb_cht_pdv input[name='file_pdf']").change(function(e) {
	var filess = e.target.files;
	var sendimgs = []; var type = "1";
	pdf_msg = "";
	if($(this).attr('name') == "file_pdf"){
		type = "2";
		filess = filess[0];
		pdf_msg = filess;
		if(!filess.type.match('application/pdf')){
			alert("Select PDF Only");
			return false;
		}
	} else {
		if(filess.length > 10){
			alert("Maximum 10 Files Allowed");
			return false;
		}
		for(var i = 0; i< filess.length; i++){
			f = filess[i];
			if(filess[i] && f.type.match('image.*')){
				sendimgs.push(filess[i]);
			}
		}
	}
	
	//var formData = new FormData($(this).closest('form')[0]);
	var formData = new FormData();
	if(type == "1" && sendimgs.length > 0){
    	sendimgs.forEach(function(img, i) {
		    formData.append('file[]', img);
		});
    } else {
    	formData.append('file_pdf', pdf_msg);
    }
	formData.append('chat_id', glbl_chat_id);
	formData.append('sender_id', glbl_sender_id);
	formData.append('receiver_id', glbl_recever_id);
	formData.append('type', type);
	$.when(ajax_request_post("Chat/send_message", formData)).done(function(data){
		if(data.success){
			sendd = {
			  chat_id: glbl_chat_id,
			  sender_id: glbl_sender_id,
			  receiver_id: glbl_recever_id,
			  message: data.url,
			  id: data.id,
			  created_at: data.created_at,
			  imgs: data.imgs,
			  type: type
			};
			io.emit("send_message", sendd);
			html = get_htm(sendd,'pc-perticuler-reciving');
			$(".pc-chatincontent-person").append(html);
			$(".wb_cht_pdv input[name='file[]']").val("");
			$(".wb_cht_pdv input[name='file_pdf']").val("");
		}
	});
});

/* 25-04-2020 */
$("body").on("click",".cht-dv-11 .del_msg",function(e){
	_th = $(this).closest('.cht-dv-11');
	if(confirm("Are You Sure You Want To Delete?")){
		id = _th.data("id");
		sendd = { id : id };
		$.when(chat_ajax_req("Chat/delete_message", sendd)).done(function(data){
			if(data.success){
				_th.remove();
				io.emit("delete_message", data.row);
			}
		});
	}
	return false;
});

$("#modal_chat_img .ct_dn_img_").click(function(e){
	imgsdd = $("#owl-pro-chat").find(".owl-item.active").find("img").attr("src");
	var n = imgsdd.lastIndexOf('/');
	var result = imgsdd.substring(n + 1);
	window.location.href = $("#pedestal").val()+"Chat/down_img?img="+result;
});

$("#modal_chat_img .ct_dn_img_all").click(function(e){
	iddd = $("#modal_chat_img .up-07 .pr-i01").find('input[name="curr_msg_id"]').val();
	window.location.href = $("#pedestal").val()+"Chat/down_all_img?id="+iddd;
});
$(".opn-cht").click(function(e){
	e.preventDefault();
	e.stopPropagation();
	$(".opn-cht-ul").toggle();
});
$('body').click( function() {
	$(".opn-cht-ul").hide();
});

$("body").on("click", ".cht-dv-11 img", function() {
	_th = $(this);
	if(!_th.hasClass('is_pdf')){
		msgID = _th.closest('.cht-dv-11').data("id");
		$("#modal_chat_img .up-07 .pr-i01").find('input[name="curr_msg_id"]').val(msgID);
		indx = parseInt(_th.closest('div').index());
		html = '';
		hname = _th.closest('.pc-chatdis-perticuler').find('.pc-perticuler-middle').find('h6 b').text();
		if(_th.closest('.pc-clickableshow-right').length){
			hname = _th.closest('.pc-clickableshow-right').find('.pc-perticuler-indiv').find('.pc-perticuler-middle').find('h6 b').text();
		}
		var imgs = _th.closest(".sinmsg").find('img');
		$("#modal_chat_img h6.up-07 span").text(hname);
		inm = 1; $dln = 0;
		imgs = _th.closest('.igdis').data("img");
		imgs = imgs.toString();
		imgs = imgs.split("|");
		if(imgs.length > 0){
			$.each(imgs, function(i, v) {
				ext = v.substring(v.indexOf(".")+1);
				if(ext != 'pdf'){
					html += '<div class="item wf-div-mar"><div class="fpge-03"><img src="'+imgpath+v+'"></div><span class="gal-nm"></span></div>';
					inm++; $dln++;
				}
			});
		} else {
			return false;
		}
		/*if(imgs.length > 0){
			imgs.each(function(i, el) {
				html += '<div class="item wf-div-mar"><div class="fpge-03"><img src="'+$(el).attr('src')+'"></div><span class="gal-nm"></span></div>';
				inm++; $dln++;
			});
		}*/
		sld = '<div class="owl-theme owl-carousel fpge-02" id="owl-pro-chat">';
		sld += html;
		sld += '</div>';
		sld += '<div class="counter"></div>';
		sld += '<div class="galnms"></div>';
		$("#modal_chat_img").find('.up-mrcr').html(sld);
		$("#modal_chat_img").modal("show").on("shown.bs.modal", function(){
			$("body").css("overflow","hidden");
			var owl = $('#owl-pro-chat');
			owl.owlCarousel({
			    dots:false,
			    nav:true,
			    margin:10,
			    responsive:{
			        0:{ items:1 },
			    }
			});
			$("#modal_chat_img").find('.fpge-02').find(".fpge-03").css("height", $(window).height() - 160);
			owl.on('mousewheel', '.owl-stage', function (e) {
				if (e.deltaY>0) {
			        owl.trigger('next.owl');
			    } else {
			        owl.trigger('prev.owl');
			    }
			    e.preventDefault();
			});
			indx2 = (parseInt(indx) + 1);
			owl.trigger("to.owl.carousel", [indx, 0]);
			$("#modal_chat_img").find('.counter').text(indx2+ ' / ' + $dln);
			$( "#modal_chat_img .owl-prev").html('<i class="fa fa-chevron-left"></i>');
			$( "#modal_chat_img .owl-next").html('<i class="fa fa-chevron-right"></i>');
			owl.on(' initialized.owl.carousel changed.owl.carousel', function(e) {
				if (!e.namespace)  {
				return;
				}
				var carousel = e.relatedTarget;
				$("#modal_chat_img").find('.counter').text((parseInt(carousel.relative(carousel.current())) + 1 )+ ' / ' + parseInt(carousel.items().length));
			});
		});
	}
});