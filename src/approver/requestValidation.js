document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-approve")) {
    const leaveId = e.target.dataset.id;
    confirmAndSend(leaveId, "approved");
  } else if (e.target.classList.contains("btn-reject")) {
    const leaveId = e.target.dataset.id;
    confirmAndSend(leaveId, "rejected");
  }
});

function confirmAndSend(leaveId, status) {
  const action = status === "approved" ? "approve" : "reject";

  Swal.fire({
    title: `Are you sure?`,
    text: `You are about to ${action} this leave request.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: status === "approved" ? "#3085d6" : "#d33",
    cancelButtonColor: "#aaa",
    confirmButtonText: `Yes, ${action}!`,
  }).then((result) => {
    if (result.isConfirmed) {
      sendStatus(leaveId, status);
    }
  });
}

function sendStatus(leaveId, status) {
  fetch("http://localhost:3000/hr_leaveRequest.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: leaveId,
      status: status,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.message) {
        Swal.fire("Done!", data.message, "success").then(() => {
          location.reload(); // Reload to reflect changes after user clicks OK
        });
      }
    })
    .catch((err) => {
      Swal.fire("Error!", "Something went wrong", "error");
      console.error("Error:", err);
    });
}