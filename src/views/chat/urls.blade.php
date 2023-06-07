<script>
  const frontUrls = {
    getContacts: "{{ route(config('eichat.routes.as').'contacts.get') }}",
    sendMessage: "{{ route(config('eichat.routes.as').'send.message') }}",
    fetchMessages: "{{ route(config('eichat.routes.as').'fetch.messages') }}",
    markAsSeen: "{{ route(config('eichat.routes.as').'messages.seen') }}",
  };
</script>