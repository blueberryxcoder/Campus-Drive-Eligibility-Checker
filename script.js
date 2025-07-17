document.getElementById("uploadForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("../backend/upload.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("status").innerText = data;
        document.getElementById("uploadForm").reset();
    })
    .catch(error => console.error("Error:", error));
});
