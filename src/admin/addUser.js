document.addEventListener('DOMContentLoaded', function() {
    const userName = document.getElementById('userName');
    const fName = document.getElementById('fName'); 
    const lName = document.getElementById('lName'); 
    const role = document.getElementById('role');
    const password = document.getElementById('password'); 
    const confirmPass = document.getElementById('confirmPassword'); 
    const message = document.getElementById('message');

    const inputs = document.querySelectorAll('input[data-counter]');

    const userForm = document.getElementById('userForm');

    function validateInput() {

        if (!validateSpaces(fName.value) || !validateSpaces(lName.value) || !validateSpaces(password.value) || !validateSpaces(confirmPass.value)) {
          message.setAttribute("class", "error");
          message.innerHTML = "Spaces are not allowed before & after the name and password.";
          return false;
        }

        if(!isValidPassword(password.value)) {
            confirmPass.setAttribute("class", "invalid");
            password.setAttribute("class", "invalid");
            message.setAttribute("class", "error");
            message.innerHTML = "Password must be at least 8 characters and contain 3 digits"
            return false;
        }

        if(password.value != confirmPass.value) {
            confirmPass.setAttribute("class", "invalid");
            password.setAttribute("class", "invalid");
            message.setAttribute("class", "error");
            message.innerHTML = "Password not match."
            return false;
        }

        return true;
    }

    function validateSpaces(input) {
      return !input.startsWith(" ") && !input.endsWith(" "); // check lang kung di nagstart or nagend sa space
    }

    function containsNumber(input) {
        return /\d/.test(input); 
    }

    function isValidPassword(password) {
        const regex = /^(?!.*\s)(?=(?:.*\d.*?){3,})(?=.*[a-z]).{8,}$/;
        return regex.test(password);
    }

    // allow only letters & spaces
    fName.addEventListener('input', function() {
       fName.value = fName.value.replace(/[^A-Za-z\s]/g, '');
    });

    lName.addEventListener('input', function() {
       lName.value = lName.value.replace(/[^A-Za-z\s]/g, '');
    });

    // character counter
    inputs.forEach(input => {
     input.addEventListener('input', function() {
        const counter = document.querySelector(`#${input.id} + span`);

        // kung nag eexist yung counter
        if(counter) {
          counter.textContent = `${input.value.length}/50`;
        }
  
        input.classList.remove('valid'); // remove all classes
        
        if (input.value.length > 1 && input.value.length <= 50) {
          input.classList.add('valid'); // green (valid)
        }
      });
  
      
      input.addEventListener('keydown', function(e) {
        if (e.key === "Backspace" || e.key === "ArrowLeft" || e.key === "ArrowRight" || e.key === "Tab" || e.key === "Delete" || e.key === "Ctrl") {
          return; // allow these keys
        }
  
        if (input.value.length >= 50) {
          e.preventDefault();
        }
      });
    });

    userForm.addEventListener("submit", function (e) {
        e.preventDefault();

        if (!validateInput()) return;
        
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to submit this leave request?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, submit it!",
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(userForm);

                fetch(userForm.action, {
                    method: "POST",
                    body: formData,
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        });
                        
                        message.textContent = "";
                        message.removeAttribute("class");
                        userForm.reset();
                        inputs.forEach(input => input.classList.remove("valid", "invalid"));
                    } else {
                        message.textContent = data.message || "Something went wrong :(";
                        message.setAttribute("class", "error");
                    }
                })
                .catch((error) => {
                    message.textContent = "An error occurred. Please try again.";
                    message.style.color = "red";
                    console.error("Error submitting form:", error);
                });
            }
        });
    });
});
