<script>
  const frontUrls = {
    getContacts: "{{ route(config('eichat.routes.as').'contacts.get') }}",
    sendMessage: "{{ route(config('eichat.routes.as').'send.message') }}",
    fetchMessages: "{{ route(config('eichat.routes.as').'fetch.messages') }}",
    markAsSeen: "{{ route(config('eichat.routes.as').'messages.seen') }}",
    getUsers: "{{ route(config('eichat.routes.as').'users') }}",
    getUserCard: "{{ route(config('eichat.routes.as').'users.card') }}",
    removeMsg: "{{ route(config('eichat.routes.as').'message.delete') }}",
    removeConv: "{{ route(config('eichat.routes.as').'conversation.delete') }}",
  };
</script>