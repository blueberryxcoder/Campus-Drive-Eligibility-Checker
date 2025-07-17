document.getElementById("jobForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("../backend/post_job.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("status").innerText = data;
        document.getElementById("jobForm").reset();
    })
    .catch(error => console.error("Error:", error));
});
