const hostURL = socketUri;
	// hostURL = "http://192.168.1.92:3003";
	// creating io instance
	socket = io(hostURL);

	setTimeout(function(){
		socket.emit("user_connected", glbl_sender_id);
	}, 1000);

	// listen from server
	socket.on("new_message", function (data) {
		console.log('new_message', data);
		if(currentUser != "" && parseInt(data.sender_id) == currentUser){
			html = data.messageHtml;
			$(".chat-messages").append(html);
			socket.emit("read_message", data);
		} else {
			nmsgdv = $(".left-contact").find("a.user-info[data-id='"+data.sender_id+"']");
			if(nmsgdv.length > 0){
				if(nmsgdv.find('.unseen').length > 0){
					var cmsg = nmsgdv.find('.unseen').text().trim();
					cmsg = parseInt(cmsg) + 1;
					nmsgdv.find('.unseen').text(cmsg);
				} else {
					nmsgdv.prepend("<div class='badge bg-success float-right unseen'>1</div>");
				}
			}
		}
	});
	/* Typing */
	socket.on("is_typing", function (data) {
		const pdv = $(".right-info").find('.typing');
		console.log('currentUser', currentUser, data.sender_id, parseInt(data.sender_id) == currentUser)
		if(currentUser != "" && parseInt(data.sender_id) == currentUser){
			pdv.text("typing...");
			pdv.removeClass('d-none');
		} else {
			nmsgdv = $(".left-contact").find("a.user-info[data-id='"+data.sender_id+"']");
			if(nmsgdv.length > 0){
				nmsgdv.find('.typing').removeClass('d-none');
			}
		}
	});
	socket.on("stop_typing", function (data) {
		pdv = $(".right-info").find('.typing');
		if(currentUser != "" && parseInt(data.sender_id) == currentUser){
			pdv.text("");
			pdv.removeClass('d-none');
		} else {
			nmsgdv = $(".left-contact").find("a.user-info[data-id='"+data.sender_id+"']");
			if(nmsgdv.length > 0){
				nmsgdv.find('.typing').removeClass('d-none').addClass('d-none');
			}
		}
	});

	getContactList();

	$("input[name='message']").keyup(function(e) {
		if(!e.shiftKey && e.which == 13){
			if($(this).val().trim().length > 0){
				const data = {
					id: currentUser, 
					message: $(this).val()
				};
				sendMessage(data);
				sendd = { sender_id:glbl_sender_id, receiver_id:currentUser };
				socket.emit("stop_typing", sendd);
			}
		} else {
			sendd = { sender_id:glbl_sender_id, receiver_id:currentUser };
			socket.emit("is_typing", sendd);
		}
	});
	$("input[name='message']").blur(function(e) {
		sendd = { sender_id:glbl_sender_id, receiver_id:currentUser };
		socket.emit("stop_typing", sendd);
	});
	
	$('.msg').click(async () => {
		const data = {
			id: currentUser, 
			message: $('input[name="message"]').val()
		};
		sendMessage(data);
	});
	$('body').on('keyup','.left-contact #search', function (e) {
		const search = $(this).val().trim();
		if(search && search.length > 0){
			$('.left-contact').find('.user-info').removeClass('d-none').addClass('d-none');
			const elements = $('.left-contact').find('.user-info');
			const searValue = search.toLowerCase();
			for (i = 0; i < elements.length; i++) {
				if (!$(elements[i]).find('.uname').data('name').toLowerCase().includes(searValue)) {
					$(elements[i]).addClass('d-none');
				}
				else {
					$(elements[i]).removeClass('d-none');                 
				}
			}
		} else {
			$('.left-contact').find('.user-info').removeClass('d-none');
		}
	});
	$('body').on('click','.left-contact .user-info', function (e) {
		const _this = $(this);
		const id = _this.data('id');
		const info = _this.data('info');
		currentUserInfo = info;
		currentUser = id;
		$('.left-contact').find('.user-info').removeClass('active');
		_this.addClass('active');
		rightSide.find('.usr-img').attr('src', currentUserInfo?.avatar);
		rightSide.find('.usr-name').html(currentUserInfo?.name);
		
		getMessageList(id, true);
	});

	$("input[name='choose_file']").change(function(e) {
		const filess = e.target.files[0];
		
		//var formData = new FormData($(this).closest('form')[0]);
		const formData = new FormData(document.getElementById("form_send_image"));
		formData.append('id', currentUser);
		formData.append('message', $('input[name="message"]').val());
		if(filess){
			formData.append('file', filess);
		}
		$.when(ajaxPostRequestImage(frontUrls.sendMessage, formData)).done(function(data){
			console.log('ret data', data.message);
			$('.chat-messages').append(data.message);
			/* const sendd = {
				sender_id: glbl_sender_id,
				receiver_id: currentUser,
				message: $('input[name="message"]').val(),
				id: data?.id,
				created_at: data?.created_at,
				imgs: data?.imgs
			};
			socket.emit("send_message", sendd); */
		});
	});