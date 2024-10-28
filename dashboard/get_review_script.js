document.addEventListener('DOMContentLoaded', function () {
    // Navigation link active class handling
    let activeClass = document.querySelectorAll(".nav a");

    activeClass.forEach((link) => {
        link.addEventListener("click", (event) => {
            activeClass.forEach((el) => el.classList.remove("active"));
            link.classList.add("active");
        });
    });

    // First popup (Add Movie)
    const popupOverlay = document.getElementById('popupOverlay');
    const closePopup = document.getElementById('closePopup');
    const addMoviesButton = document.getElementById('addmovies');

    function openPopup() {
        popupOverlay.style.display = 'block';
    }

    function closePopupFunc() {
        popupOverlay.style.display = 'none';
    }

    addMoviesButton.addEventListener('click', openPopup);
    closePopup.addEventListener('click', closePopupFunc);
    popupOverlay.addEventListener('click', function (event) {
        if (event.target === popupOverlay) {
            closePopupFunc();
        }
    });

    // Second popup (Update Movie)
    const popupOverlay1 = document.getElementById('popupOverlay1');
    const closePopup1 = document.getElementById('closePopup1');
    const titleInput = document.getElementById('title1');
    const descriptionInput = document.getElementById('review_text');
    const releaseDateInput = document.getElementById('release_date1');
    const genreInput = document.getElementById('genre1');
    const durationInput = document.getElementById('duration1');
    const submitForm1 = document.getElementById('submitForm1');

    // Ensure currentMovieId is defined globally to use in form submission
    let currentMovieId = null;

    // Make openPopup1 function globally accessible
    window.openPopup1 = function (review_id) {
        console.log(`Fetching movie for review ID: ${review_id}`)
        document.getElementById('review_id').value=review_id;

        fetch(`get_review.php?id=${review_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Check if each element exists before setting its value
                    if (titleInput) {
                        titleInput.value = data.review.title || '';
                    }
                    if (descriptionInput) {
                        descriptionInput.value = data.review.review_text || '';
                    
                    }
                   
                    // Log success and open the popup
                    console.log("Opening Update Movie popup...");
                    popupOverlay1.style.display = 'block';  // Show the popup
                } else {
                    console.error('Movie fetch failed:', data.message);
                }
            })
            .catch(error => console.error('Error fetching movie data:', error));
    };

    // Close the second popup
    function closePopupFunc1() {
        popupOverlay1.style.display = 'none';
        // Reset form fields
        titleInput.value = '';
        descriptionInput.value = '';
        releaseDateInput.value = '';
        genreInput.value = '';
        durationInput.value = '';
    }

    // Close the second popup when the close button is clicked
    closePopup1.addEventListener('click', closePopupFunc1);

    // Close the second popup when clicking outside the popup content
    popupOverlay1.addEventListener('click', function (event) {
        if (event.target === popupOverlay1) {
            closePopupFunc1();
        }
    });

    // Handle form submission for updating the movie
    submitForm1.addEventListener('click', function () {
        console.log("Submitting updated movie information...");
        // Handle form submission logic here
    });
});
