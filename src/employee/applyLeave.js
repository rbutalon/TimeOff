document.addEventListener('DOMContentLoaded', function() {

    const textarea = document.querySelectorAll('textarea[data-counter]');
    const reason = document.getElementById('reason');
    const message = document.getElementById('message');
    
    const leaveForm = document.getElementById('leaveForm');
    
    textarea.forEach(input => {
        input.addEventListener('input', function() {
            const counter = document.querySelector(`#${input.id} + span`);
            if (counter) {
                counter.textContent = `${input.value.length}/100`;
            }
            
            if (input.value.length < 10) {
                reason.style.borderColor = "#ff4545";
            } else {
                reason.style.borderColor = "#00ff99";
            }
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === "Backspace" || e.key === "ArrowLeft" || e.key === "ArrowRight" || e.key === "Tab" || e.key === "Delete" || e.key === "Ctrl") {
                return; // allow these keys
            }
            
            if (input.value.length >= 100) { // prevent typing if length is 100
                e.preventDefault();
            }
        });
    });
    
    function validateInput() {
        const reasonText = reason.value.trim();

        if(reasonText.length < 10) {
            reason.setAttribute("class", "invalid")
            message.setAttribute("class", "error");
            message.innerHTML = "Reason should be at least 10 characters.";
            return false;
        }

        return true;
    }

    leaveForm.addEventListener("submit", function (e) {
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
                const formData = new FormData(leaveForm);

                fetch(leaveForm.action, {
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
                        leaveForm.reset();
                        reason.style.borderColor = "#f9f8d6";
                    } else {
                        message.textContent = data.message;
                        message.setAttribute("class", "error");
                        
                        const startDate = document.getElementById('start_date');
                        const endDate = document.getElementById('end_date');

                        startDate.classList.add("invalid");
                        endDate.classList.add("invalid");
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