$('#forgetForm').validate({
  rules:{
    email: {
      required: true,
      email: true
    }
  },
  messages: {
    email: {
      required: 'Please enter your registered email',
      email: 'Please enter a <i>valid</i> email address,'
    }
  }
})
