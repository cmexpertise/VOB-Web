$('#loginForm').validate({
  rules:{
    email: {
      required: true,
      email: true
    },
    pass:{
      required: true,
    },
  },
  messages: {
    email: {
      required: 'Please enter an email address',
      email: 'Please enter a valid email address,'
    },
    pass:{
      required: 'Please enter your password',
    } 
  }
})

$(document).ready(function() {
  setTimeout(function () {
    $('.alert').hide();
  }, 3000);
});

$(document).on('click','#eye',function () {
  if($('#exampleInputPassword2').attr('type') == 'password'){
    $('#exampleInputPassword2').attr('type','text');
  }else{
    $('#exampleInputPassword2').attr('type','password');
  }
})
