const urlParams = new URLSearchParams(window.location.search);
  const error = urlParams.get('error');

  if (error) {
    const errorDiv = document.createElement('p');
    errorDiv.style.color = 'red';
    errorDiv.style.textAlign = 'center';

    if (error === 'wrong') {
      errorDiv.innerText = '❌ Incorrect password. Try again.';
    } else if (error === 'notfound') {
      errorDiv.innerText = '❌ Email not registered. Please sign up first.';
    }

    document.querySelector('.form-box').prepend(errorDiv);
  }

  