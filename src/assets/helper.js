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