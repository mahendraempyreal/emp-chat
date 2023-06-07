<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="base-url" content="{{ url('/') }}" />
	<title>Chat</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="{{ asset('chat/style.css') }}">
  <link rel="stylesheet" href="{{ asset('chat/loader.css') }}">

</head>
<body>
	
	<main class="content">
		<span class="loader"></span>
    <div class="container p-0">
			@if(auth()->check())
				<h3>Logged In As :: {{ auth()->user()->name }}</h3>
			@else
			<form action="{{ route('emp-chat.login') }}" method="get">
				<div class="input-group">
					<input type="text" name="id" class="form-control" placeholder="Enter User ID" required/>
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</form>
			@endif

		<h1 class="h3 mb-3">Messages</h1>

		<div class="card">
			<div class="row g-0">
				<div class="col-12 col-lg-5 col-xl-3 border-right left-contact">

					<div class="px-4 d-none d-md-block">
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								<input type="text" id="search" class="form-control my-3" placeholder="Search...">
							</div>
						</div>
					</div>

					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="badge bg-success float-right">5</div>
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle mr-1" alt="Vanessa Tucker" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Vanessa Tucker
								<div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="badge bg-success float-right">2</div>
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle mr-1" alt="William Harris" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								William Harris
								<div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Sharon Lessman
								<div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle mr-1" alt="Christina Mason" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Christina Mason
								<div class="small"><span class="fas fa-circle chat-offline"></span> Offline</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle mr-1" alt="Fiona Green" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Fiona Green
								<div class="small"><span class="fas fa-circle chat-offline"></span> Offline</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle mr-1" alt="Doris Wilder" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Doris Wilder
								<div class="small"><span class="fas fa-circle chat-offline"></span> Offline</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle mr-1" alt="Haley Kennedy" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Haley Kennedy
								<div class="small"><span class="fas fa-circle chat-offline"></span> Offline</div>
							</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action border-0">
						<div class="d-flex align-items-start">
							<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Jennifer Chang" width="40" height="40">
							<div class="flex-grow-1 ml-3">
								Jennifer Chang
								<div class="small"><span class="fas fa-circle chat-offline"></span> Offline</div>
							</div>
						</div>
					</a>

					<hr class="d-block d-lg-none mt-1 mb-0">
				</div>
				<div class="col-12 col-lg-7 col-xl-9 right-info">
					<div class="py-2 px-4 border-bottom d-none d-lg-block">
						<div class="d-flex align-items-center py-1">
							<div class="position-relative">
								<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1 usr-img" alt="Sharon Lessman" width="40" height="40">
							</div>
							<div class="flex-grow-1 pl-3">
								<strong class="usr-name">Sharon Lessman</strong>
								<div class="text-muted small typing d-none" style="color:green;"><em>Typing...</em></div>
							</div>
							<div>
								<!-- <button class="btn btn-primary btn-lg mr-1 px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone feather-lg"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></button> -->
								<!-- <button class="btn btn-info btn-lg mr-1 px-3 d-none d-md-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video feather-lg"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></button> -->
								<button class="btn btn-light border btn-lg px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
							</div>
						</div>
					</div>

					<div class="position-relative">
						<div class="chat-messages p-4">

							<div class="chat-message-right pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:33 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Lorem ipsum dolor sit amet, vis erat denique in, dicunt prodesset te vix.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:34 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Sit meis deleniti eu, pri vidit meliore docendi ut, an eum erat animal commodo.
								</div>
							</div>

							<div class="chat-message-right mb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:35 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Cum ea graeci tractatos.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:36 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Sed pulvinar, massa vitae interdum pulvinar, risus lectus porttitor magna, vitae commodo lectus mauris et velit.
									Proin ultricies placerat imperdiet. Morbi varius quam ac venenatis tempus.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:37 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Cras pulvinar, sapien id vehicula aliquet, diam velit elementum orci.
								</div>
							</div>

							<div class="chat-message-right mb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:38 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Lorem ipsum dolor sit amet, vis erat denique in, dicunt prodesset te vix.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:39 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Sit meis deleniti eu, pri vidit meliore docendi ut, an eum erat animal commodo.
								</div>
							</div>

							<div class="chat-message-right mb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:40 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Cum ea graeci tractatos.
								</div>
							</div>

							<div class="chat-message-right mb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:41 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Morbi finibus, lorem id placerat ullamcorper, nunc enim ultrices massa, id dignissim metus urna eget purus.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:42 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Sed pulvinar, massa vitae interdum pulvinar, risus lectus porttitor magna, vitae commodo lectus mauris et velit.
									Proin ultricies placerat imperdiet. Morbi varius quam ac venenatis tempus.
								</div>
							</div>

							<div class="chat-message-right mb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:43 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Lorem ipsum dolor sit amet, vis erat denique in, dicunt prodesset te vix.
								</div>
							</div>

							<div class="chat-message-left pb-4">
								<div>
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:44 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">Sharon Lessman</div>
									Sit meis deleniti eu, pri vidit meliore docendi ut, an eum erat animal commodo.
								</div>
							</div>

						</div>
					</div>

					<div class="flex-grow-0 py-3 px-4 border-top">
						
						<div class="input-group">
							<div class="input-group-prepend mr-1">
								<label for="choose_file">
								<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 50 50">
									<path d="M 25 2 C 12.309295 2 2 12.309295 2 25 C 2 37.690705 12.309295 48 25 48 C 37.690705 48 48 37.690705 48 25 C 48 12.309295 37.690705 2 25 2 z M 25 4 C 36.609824 4 46 13.390176 46 25 C 46 36.609824 36.609824 46 25 46 C 13.390176 46 4 36.609824 4 25 C 4 13.390176 13.390176 4 25 4 z M 24 13 L 24 24 L 13 24 L 13 26 L 24 26 L 24 37 L 26 37 L 26 26 L 37 26 L 37 24 L 26 24 L 26 13 L 24 13 z"></path>
								</svg>
								</label>
								<input type="file" name="choose_file" id="choose_file" class="d-none" />
							</div>
							<input type="text" class="form-control" name="message" placeholder="Type your message">
							<button type="button" class="btn btn-primary msg">Send</button>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>
<form id="form_send_image" method="post"></form>
@include('empchat::chat.urls')
<script src="{{ asset('chat/chat.js') }}"></script>
<script src="{{ asset('chat/socket.io.js') }}"></script>
<!-- <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" crossorigin="anonymous"></script> -->
<script>
	var currentUser;
	var currentUserInfo;
	
	let messagesPage = 1; let noMoreMessages = false;
	

	const rightSide = $('.right-info');
	var messagesElement = rightSide.find('.chat-messages');
	const messagesContainer = messagesElement;
	let socket;

	$(document).ready(function() {
		@if(auth()->check())
			const hostURL = "http://localhost:3003";
			// hostURL = "http://192.168.1.92:3003";
			// creating io instance
			socket = io(hostURL);
			const glbl_recever_id = "";
			const glbl_sender_id = "{{ auth()->user()->id }}";

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
			//send message
			const sendMessage = (data) => {
				$.when(ajaxPostRequest(frontUrls.sendMessage, data)).done(function(data){
					$('.chat-messages').append(data.message);
					
					const sendd = { receiver_id: currentUser, sender_id: glbl_sender_id, message: $('input[name="message"]').val(), messageHtml : data.receiver_message };
					socket.emit("send_message", sendd);
					$('input[name="message"]').val("");
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
			};
			function actionOnScroll(selector, callback, topScroll = false) {
				$(selector).on("scroll", function () {
					let element = $(this).get(0);
					const condition = topScroll
						? element.scrollTop == 0
						: element.scrollTop + element.clientHeight >= element.scrollHeight;
					if (condition) {
						callback();
					}
				});
			}
			actionOnScroll(
				".right-info .chat-messages",
				function () {
					getMessageList(currentUser);
				},
				true
			);
		@endif
	});
	
	const getContactList = () => {
		$.when(ajaxGetRequest(frontUrls.getContacts))
		.then((data)=>{
			$('.left-contact').find("a.list-group-item").remove();
			$('.left-contact').append(data.contacts);
			setTimeout(() => {
				$('.left-contact').find('.user-info').first().click();
			}, 300);
		});
	};
	const getMessageList = (id, newFetch = false) => {
		console.log('before :: ', messagesPage, newFetch);
		if (newFetch) {
			messagesPage = 1;
			noMoreMessages = false;
		}
		console.log('after :: ', messagesPage);
		if(!noMoreMessages){
			$.when(ajaxPostRequest(frontUrls.fetchMessages, { id, page: messagesPage }))
			.then((data)=>{
				if (messagesPage == 1) {
					messagesElement.html(data.messages);
					scrollToBottom(messagesContainer);
					markAsSeen();
				} else {
					const lastMsg = messagesElement.find(
						messagesElement.find("div")[0]
					);
					const curOffset =
						lastMsg.offset().top - messagesContainer.scrollTop();
					messagesElement.prepend(data.messages);
					messagesContainer.scrollTop(lastMsg.offset().top - curOffset);
				}
				// trigger seen event
				// Pagination lock & messages page
				noMoreMessages = messagesPage >= data?.last_page;
				console.log('noMoreMessages', noMoreMessages, messagesPage, data?.last_page);
				if (!noMoreMessages) messagesPage += 1;
					
			});
		}
	};
	const markAsSeen = () => {
		$.when(ajaxPostRequest(frontUrls.markAsSeen, { id: currentUser }))
		.then((data)=>{
			$('.left-contact').find("a.list-group-item.active").find('.unseen').remove();
		});
	};
	function scrollToBottom(container) {
		$(container)
			.stop()
			.animate({
				scrollTop: $(container)[0].scrollHeight,
			});
}
</script>
</body>
</html>