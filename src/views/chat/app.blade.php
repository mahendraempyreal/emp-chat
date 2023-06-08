<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="base-url" content="{{ url('/') }}" />
	<title>Chat App Laravel Using Socket IO</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="{{ asset('empchat/style.css') }}">
  <link rel="stylesheet" href="{{ asset('empchat/loader.css') }}">

</head>
<body>
	
	<main class="content">
		<span class="loader"></span>
    <div class="container p-0">
			@if(auth()->check())
				<h3>Logged In As :: {{ auth()->user()->name }}</h3>
			@else
			<form action="" method="get">
				<div class="input-group">
					<input type="text" name="id" class="form-control" placeholder="Enter User ID" required/>
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</form>
			@endif

		<h1 class="h3 mb-3">Messages</h1>
		<div class="row g-0 mb-2">
			<div class="col-12 col-lg-5">
				<div class="input-group">
					<select id="select_user" name="select_user" class="form-control">
					</select>
					<button type="submit" class="btn btn-primary ml-1 start_chat">Start Chat</button>
				</div>
			</div>
		</div>
		<!-- <button type="button" class="btn btn-success getuser">GET</button> -->
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
					<!-- Contact List Appear Here -->

					<hr class="d-block d-lg-none mt-1 mb-0">
				</div>
				<div class="col-12 col-lg-7 col-xl-9 right-info">
					<div class="py-2 px-4 border-bottom d-none d-lg-block">
						<div class="d-flex align-items-center py-1">
							<div class="position-relative">
								<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1 usr-img" alt="User's Name" width="40" height="40">
							</div>
							<div class="flex-grow-1 pl-3">
								<strong class="usr-name">User's Name</strong>
								<div class="text-muted small typing d-none" style="color:green;"><em>Typing...</em></div>
							</div>
							<div>
								<!-- <button class="btn btn-primary btn-lg mr-1 px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone feather-lg"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></button> -->
								<!-- <button class="btn btn-info btn-lg mr-1 px-3 d-none d-md-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video feather-lg"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></button> -->

								<!-- <button class="btn btn-light border btn-lg px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button> -->

								<div class="dropdown">
									<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item rm-conv" href="javascript:;">Delete Conversation</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="position-relative">
						<div class="chat-messages p-4">
							<!-- Messages Appear Here -->
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
<script src="{{ asset('empchat/chat.js') }}"></script>
<script src="{{ asset('empchat/socket.io.js') }}"></script>
<!-- <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" crossorigin="anonymous"></script> -->
<script>
	var currentUser;
	var currentUserInfo;
	
	let messagesPage = 1; let noMoreMessages = false;

	const rightSide = $('.right-info');
	var messagesElement = rightSide.find('.chat-messages');
	const messagesContainer = messagesElement;
	let socket;
	const glbl_recever_id = "";
	const glbl_sender_id = "{{ auth()->user()->id }}";
	const socketUri = "{{config('eichat.socket_url', 'http://localhost:3003')}}";
</script>
@if(auth()->check())
<script src="{{ asset('empchat/helper.js') }}"></script>
<script src="{{ asset('empchat/index.js') }}"></script>
@endif
</body>
</html>