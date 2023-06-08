	const hostURL = socketUri;
	// hostURL = "http://192.168.1.92:3003";
	// creating io instance
	socket = io(hostURL);

	/* Socket Events Start */
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
			} else {
				const newUserlId = data.sender_id;
				$.when(ajaxGetRequest(`${frontUrls.getUserCard}?id=${newUserlId}&unseen=1`))
				.then((data)=>{
					$("#select_user").find('option[value="'+newUserlId+'"]').remove();
					$('.left-contact').find('.user-info').first().before(data.html);
				});
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
	/* Stop Typing */
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

	/* Delete Message & Conversations */
	socket.on("delete_message", function (data) {
		getUsers();
		if(currentUser != "" && parseInt(data.sender_id) == currentUser){
			if(data.is_conversation){
				$(".left-contact").find("a.user-info[data-id='"+currentUser+"']").remove();
				setTimeout(() => {
					$('.left-contact').find('.user-info').first().click();
				}, 400);
			} else {
				messagesElement.find(".msg-p-dv[data-id='"+data?.id+"']").remove();
			}
		} else {
			nmsgdv = $(".left-contact").find("a.user-info[data-id='"+data.sender_id+"']");
			if(nmsgdv.length > 0){
				if(data.is_conversation){
					nmsgdv.remove();
				}
			}
		}
	});
	/* Socket Events End */

	getContactList();
	/* Keypress event for typing */
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
	/* Keypress event for stop typing */
	$("input[name='message']").blur(function(e) {
		sendd = { sender_id:glbl_sender_id, receiver_id:currentUser };
		socket.emit("stop_typing", sendd);
	});
	/* On Click Button send message */
	$('.msg').click(async () => {
		const data = {
			id: currentUser, 
			message: $('input[name="message"]').val()
		};
		sendMessage(data);
	});
	/* Search contact list using js */
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
	/* On Click User Info Show Right Side Messages */
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
	/* Send attachment in message */
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
	/* Delete Message & Conversation Click Event Code */
	$('body').on('click', '.msg-p-dv .rm-msg', function(e){
		const msgDiv = $(this).closest('.msg-p-dv');
		const msgId = msgDiv.data('id');
		if(confirm("Are you sure you want remove message?")){
			$.when(ajaxPostRequest(`${frontUrls.removeMsg}`, { id: msgId }))
			.then((data)=>{
				const sendd = { sender_id:glbl_sender_id, receiver_id:currentUser, is_conversation: false, id:msgId };
				socket.emit("delete_message", sendd);
				msgDiv.remove();
			});
		}
	});
	$('body').on('click', '.right-info .rm-conv', function(e){
		if(confirm("Are you sure you want delete conversation?")){
			$.when(ajaxPostRequest(`${frontUrls.removeConv}`, { id: currentUser }))
			.then((data)=>{
				const sendd = { sender_id:glbl_sender_id, receiver_id:currentUser, is_conversation: true, id:'' };
				socket.emit("delete_message", sendd);
				messagesElement.html('<p class="message-hint center-el"><span>Conversation deleted successfully</span></p>');
				getUsers();
				$(".left-contact").find("a.user-info[data-id='"+currentUser+"']").remove();
				setTimeout(() => {
					$('.left-contact').find('.user-info').first().click();
				}, 400);
			});
		}
	});
	/* Delete Message & Conversation Click Event Code End */
	
	/* Start chat button click event */
	$('.start_chat').click(function(){
		selId = $("#select_user").val();
		if(selId){
			$.when(ajaxGetRequest(`${frontUrls.getUserCard}?id=${selId}`))
			.then((data)=>{
				$("#select_user").find('option[value="'+selId+'"]').remove();
				$('.left-contact').find('.user-info').first().before(data.html);
				setTimeout(() => {
					$('.left-contact').find('.user-info').first().click();
				}, 400);
			});
		} else {
			alert("Please select a user");
		}
	});
