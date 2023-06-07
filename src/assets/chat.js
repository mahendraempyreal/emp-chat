const baseUrl = $("meta[name='base-url']").attr("content");

const ajaxGetRequest = (url, data) => {
  return $.ajax({
		type: 'GET',
		url: url,
		data:data,
		dataType: "json",
		encode: true
	});
};

const ajaxPostRequest = (url, data) => {
  return $.ajax({
		type: 'POST',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
		url: url,
		data:data,
		dataType: "json",
		encode: true
	});
};
const ajaxPostRequestImage = (url,formData = {}) =>{
	return $.ajax({
		url:  url,
		type: "POST",
		headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
		data: formData,
		dataType : "json",
		processData: false,
		contentType: false,
		encode: true
	}); 
};