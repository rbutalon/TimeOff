document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".auth__form");
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const terms = document.getElementById("terms");
  const messageSpan = document.querySelector(".message");

  const inputUserWrapper = document.querySelector(".inputUser");
  const inputPassWrapper = document.querySelector(".inputPass");

  function validateInput() {
    // Reset invalid classes and message
    inputUserWrapper.classList.remove("invalid");
    inputPassWrapper.classList.remove("invalid");
    messageSpan.innerText = "";
  
    let hasError = false;
  
    // Username space check
    if (!validateSpaces(username.value)) {
      inputUserWrapper.classList.add("invalid");
      if (!hasError) {
        messageSpan.innerText = "Username cannot start or end with spaces.";
        hasError = true;
      }
    }
  
    // Password space check
    if (!validateSpaces(password.value)) {
      inputPassWrapper.classList.add("invalid");
      if (!hasError) {
        messageSpan.innerText = "Password cannot start or end with spaces.";
        hasError = true;
      }
    }
  
    // Password must contain number
    if (!containsNumber(password.value)) {
      inputPassWrapper.classList.add("invalid");
      if (!hasError) {
        messageSpan.innerText = "Password must contain at least one number.";
        hasError = true;
      }
    }
  
    if (!terms.checked) {
      if (!hasError) {
        messageSpan.innerText = "You must agree to the Terms and Conditions.";
        hasError = true;
      }
    }
  
    return !hasError;
  }
  
  
  function validateSpaces(input) {
    return !input.startsWith(" ") && !input.endsWith(" ");
  }

  function containsNumber(input) {
    return /\d/.test(input); 
  }

  const inputs = document.querySelectorAll("input");
  inputs.forEach(input => {
    input.addEventListener("keydown", function (e) {
      const allowedKeys = ["Backspace", "ArrowLeft", "ArrowRight", "Tab", "Delete", "Control", "Shift", "Escape"];
      if (allowedKeys.includes(e.key)) return;
      
      if (input.value.length >= 30) {
        e.preventDefault();
        messageSpan.innerText = "Maximum of 30 characters only.";
      }
    });

    input.addEventListener("paste", function (e) {
      const pastedText = (e.clipboardData || window.clipboardData).getData("text");
      if (input.value.length + pastedText.length > 30) {
        e.preventDefault();
        messageSpan.innerText = "Maximum of 30 characters only.";
      }
    });
  });
 

  function getRedirectURL(role) {
    switch (role) {
      case "admin":
        return "../../admin_dashboard.php";
      case "employee":
        return "../../employee_dashboard.php";
      case "approver":
        return "../../hr_dashboard.php";
      default:
        return "login.php";
    }
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault(); 
  
    if (!validateInput()) return; 
  
    const formData = new FormData(form);
  
    fetch("http://localhost:3000/src/login/php/login.php", {
      method: "POST",
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          messageSpan.innerText = data.error; 
        } else if (data.success) {
          window.location.href = getRedirectURL(data.role);
        }
      })
      .catch(() => {
        messageSpan.innerText = "Something went wrong. Please try again.";
      });
  });
  
});
