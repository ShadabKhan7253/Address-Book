$(function () {
  $('.modal').modal();
  $('.delete-contact').click(function () {
    var id = $(this).data('id');
    $('#modal-agree').attr('href',`delete-contact.php?id=${id}`)
  }) 

  function getUrlQueryParams() {
    const queryParams = window.location.search.substring(1).split('&');
    let vars = [],hash;
    for (let i = 0; i < queryParams.length; i++) {
      hash = queryParams[i].split('=');
      vars[hash[0]] = hash[1];
    }
    return vars;
  }

  const queryParams = getUrlQueryParams();
  const operation = queryParams['op'];
  const status = queryParams['status'];
  if (operation === 'add' && status === 'success') {
    M.toast({
      html: 'Contact Added Successfully!',
      classes: 'green darken-1',
    });
  } else if (operation === 'edit' && status === 'success') {
    M.toast({
      html: 'Contact Edit Successfully!',
      classes: 'green darken-1',
    });
  } else if (operation === 'delete' && status === 'success') {
    M.toast({
      html: 'Contact Deleted Successfully!',
      classes: 'green darken-1',
    });
  } 
});
