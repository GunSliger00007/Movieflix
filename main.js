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

  window.onscroll = function() {
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
  
    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();
  
        if (query.length > 0) {
            // Create an AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'live_search.php?q=' + encodeURIComponent(query), true);
            xhr.onload = function() {
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
            results.forEach(function(result) {
                const li = document.createElement('li');
                li.textContent = result.title;
                li.addEventListener('click', function() {
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
const popupOverlay = document.getElementById('popupOverlay');
const popup = document.getElementById('popup');
const closePopup = document.getElementById('closePopup');
const emailInput = document.getElementById('emailInput');
const openPopupBtn = document.getElementById('signup');
const submitFormBtn = document.getElementById('submitFormBtn');

// Function to open the popup
function openPopup() {
    popupOverlay.style.display = 'block';
}

// Function to close the popup
function closePopupFunc() {
    popupOverlay.style.display = 'none';
}

// Function to submit the signup form
function submitForm() {
    const email = emailInput.value;
    console.log(`Email submitted: ${email}`);
    closePopupFunc(); // Close the popup after form submission
}

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

// Handle form submission
submitFormBtn.onclick = submitForm;
