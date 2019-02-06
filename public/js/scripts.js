function addComparedValue() {
  $.ajax({
    url: '/validation/add-compared-value',
    type: 'POST',
    async: true,

    success: function (data) {
      $('#compared-values').append(data);
    },
    error: function(xhr) {
      alert('Ajax request failed.');
      console.log(xhr.responseText);
    }
  });
  return false;
}