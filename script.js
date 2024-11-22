let activeClass = document.querySelectorAll(".nav ul a");
activeClass.forEach((element) => {
    element.addEventListener("click", () => {
        activeClass.forEach((el) => el.classList.remove("active"));
        element.classList.add("active");
    });
});



let activeclass = document.querySelectorAll(".main .categories_link a");
activeclass.forEach((element) => {
    element.addEventListener("click", (event) => {
        event.preventDefault();
        activeclass.forEach((el) => el.classList.remove("active1"));
        element.classList.add("active1");
    });
});


var header = document.getElementById("myHeader");

window.onscroll = function () {
    myFunction();
};

function myFunction() {
    if (window.scrollY >= 100) {
        header.classList.add("fixed");
    } else {
        header.classList.remove("fixed");
    }
}

const searchInput = document.getElementById('search');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function () {
    const query = searchInput.value.trim();

    if (query.length > 0) {
        // Create an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'live_search.php?q=' + encodeURIComponent(query), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                displayResults(response);
            }
        };
        xhr.send();
    } else {
        searchResults.innerHTML = ''; // Clear results when the input is empty
    }
});

// Function to display search results
function displayResults(results) {
    searchResults.innerHTML = ''; // Clear previous results

    if (results.length > 0) {
        results.forEach(function (result) {
            const li = document.createElement('li');
            li.textContent = result.title;
            li.addEventListener('click', function () {
                searchInput.value = result.title; // Set the input value to the selected result
                searchResults.innerHTML = ''; // Clear the results
            });
            searchResults.appendChild(li);
        });
    } else {
        searchResults.innerHTML = '<li>No results found</li>';
    }
}
// Get elements
// Get elements
const popupOverlay = document.getElementById('popupOverlay');
const popup = document.getElementById('popup');
const closePopup = document.getElementById('closePopup');
const emailInput = document.getElementById('emailInput');
const loginEmailInput = document.getElementById('loginEmailInput');
const passwordInput = document.getElementById('passwordInput1');
const openPopupBtn = document.getElementById('signup');
const submitFormBtn = document.getElementById('submitFormBtn');

const loginLink = document.getElementById('loginLink');
const signupLink = document.getElementById('signupLink');
const signupForm = document.getElementById('signupForm');
const loginForm = document.getElementById('loginForm');
const popupTitle = document.getElementById('popupTitle');
const popupSubtitle = document.getElementById('popupSubtitle');

// Function to open the popup
function openPopup() {
    popupOverlay.style.display = 'block';
}

// Function to close the popup
function closePopupFunc() {
    popupOverlay.style.display = 'none';
}

// Function to submit the signup form
function submitSignUpForm() {
    const email = emailInput.value;
    console.log(`Email submitted for Sign Up: ${email}`);
    closePopupFunc(); // Close the popup after form submission
}


// Function to submit the login form


// Event listeners
// Open the popup when the button is clicked
openPopupBtn.onclick = openPopup;

// Close the popup when the close button is clicked
closePopup.onclick = closePopupFunc;

// Close the popup when clicking outside the popup content
popupOverlay.onclick = function (event) {
    if (event.target === popupOverlay) {
        closePopupFunc();
    }
};

// Handle form submission for signup
submitFormBtn.onclick = submitSignUpForm;



// Switch to the login form when "Please login" is clicked
loginLink.onclick = function (event) {
    event.preventDefault();
    signupForm.style.display = 'none';
    loginForm.style.display = 'block';

    popup.style.width = '23%';
};

// Switch to the signup form when "Sign Up" is clicked
signupLink.onclick = function (event) {
    event.preventDefault();
    loginForm.style.display = 'none';
    signupForm.style.display = 'block';
    popupTitle.textContent = 'Welcome to our website!';
    popupSubtitle.textContent = 'Sign up to receive exclusive offers:';
    popup.style.width = '35%';
};
// Get elements
const emailinput = document.getElementById('emailInput');
const userInput = document.getElementById('userInput');
const passwordinput = document.getElementById('passwordInput');
const emailInput1 = document.getElementById("loginEmailInput");
const passwordInput1 = document.getElementById('passwordInput1');
const submitformBtn = document.getElementById('submitFormBtn');
const responseMessage = document.getElementById('responseMessage');
const loginResponseMessage = document.getElementById('loginResponseMessage')
const loginFormBtn = document.getElementById('loginFormBtn');
// Regex patterns for validation
const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const usernamePattern = /^[a-zA-Z0-9_-]{3,16}$/; // Username: 3-16 chars, letters, numbers, _ or -
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/; // Password: at least 8 chars, 1 letter and 1 number

// Function to validate the form
function validateForm() {
    const email = emailinput.value;
    const username = userInput.value;
    const password = passwordinput.value;
    const confirmPassword = passwordInput1.value;

    // Validate email
    if (!emailPattern.test(email)) {
        responseMessage.textContent = "Please enter a valid email!";
        responseMessage.style.color = 'red';
        return false;
    }

    // Validate username
    if (!usernamePattern.test(username)) {
        responseMessage.textContent = "Username must be 3-16 characters and can include letters, numbers, underscores, or hyphens.";
        responseMessage.style.color = 'red';
        return false;
    }

    // Validate password
    if (!passwordPattern.test(password)) {
        responseMessage.textContent = "Password must be at least 8 characters long and contain at least one letter and one number.";
        responseMessage.style.color = 'red';
        return false;
    }

    // Validate confirm password
    if (password !== confirmPassword) {
        responseMessage.textContent = "Passwords do not match!";
        responseMessage.style.color = 'red';
        return false;
    }

    return true;
}
function validateForm1() {
    const email = emailInput1.value;
    const password = passwordInput1.value;
    if (!emailPattern.test(email)) {
        loginResponseMessage.textContent = "please enter a vaild email!";
        loginResponseMessage.style.color = 'red';
        return false;
    }
    if (!passwordPattern.test(password)) {
        loginResponseMessage.textContent = "Password must be at least 8 characters long and contain at least one letter and one number.";
        loginResponseMessage.style.color = 'red'
        return false;
    }

}
// Handle the form submission for login
;

loginForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    if (validateForm1()) {
        const formData = new FormData(document.getElementById('loginForm1'));

        // Send data via AJAX (fetch)
        fetch('login.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json()) // Parse JSON response
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'index.php'; // Redirect on success
                } else {
                    loginResponseMessage.textContent = data.message + data.password + data.email; // Show error message
                }
            })
            .catch(error => {
                loginResponseMessage.textContent = 'An error occurred. Please try again later.';
            });
    }
});


// Handle form submission
submitformBtn.onclick = function (event) {
    console.log('clicked')
    event.preventDefault(); // Prevent the form from submitting

    // Validate the form before submission
    if (validateForm()) {
        // Create FormData object to send the form data
        const formData = new FormData(document.getElementById('registerForm'));

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {

                if (data.status === 'error') {
                    responseMessage.textContent = data.message; // Show error message
                    responseMessage.style.color = 'red';
                } else if (data.status === 'success') {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseMessage.textContent = "An error occurred while processing your request.";
                responseMessage.style.color = 'red';
            });
    }
};
const video = document.getElementById('myVideo');
const playButton = document.getElementById('playButton');

playButton.addEventListener('click', function () {
    video.play(); // Start the video
    playButton.style.display = 'none'; // Hide the play button after clicking
});
video.addEventListener('pause', function () {
    playButton.style.display = 'block'; // Show the play button when the video is paused
});

// Hide the play button when the video starts playing again
video.addEventListener('play', function () {
    playButton.style.display = 'none'; // Ensure play button is hidden when video is playing
});