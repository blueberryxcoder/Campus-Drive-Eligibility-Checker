function setUserType(type) {
    document.getElementById('userType').value = type;

    let studentBtn = document.getElementById('student-btn');
    let recruiterBtn = document.getElementById('recruiter-btn');
    let usernameLabel = document.getElementById('username-label');
    let usernameInput = document.getElementById('username');

    if (type === 'student') {
        studentBtn.classList.add('active');
        recruiterBtn.classList.remove('active');
        usernameLabel.innerText = "Student Username";
        usernameInput.placeholder = "Enter your student username";
    } else {
        recruiterBtn.classList.add('active');
        studentBtn.classList.remove('active');
        usernameLabel.innerText = "Recruiter Username";
        usernameInput.placeholder = "Enter your recruiter username";
    }
}
