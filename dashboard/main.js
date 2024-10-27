document.addEventListener('DOMContentLoaded', function () {
    // Handle active class toggle for navigation links
    let activeClass = document.querySelectorAll(".nav a");
    activeClass.forEach((link) => {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            activeClass.forEach((el) => el.classList.remove("active"));
            link.classList.add("active");
        });
    });

    // First popup elements (Add Movies)
    const popupOverlay = document.getElementById('popupOverlay');
    const closePopup = document.getElementById('closePopup');
    const addMoviesButton = document.getElementById('addmovies');

    // Function to open the first popup (Add Movie)
    function openPopup() {
        console.log("Opening Add Movie popup...");
        popupOverlay.style.display = 'block';
    }

    // Function to close the first popup
    function closePopupFunc() {
        popupOverlay.style.display = 'none';
    }

    // Event listeners for the first popup
    addMoviesButton.addEventListener('click', openPopup);
    closePopup.addEventListener('click', closePopupFunc);
    popupOverlay.addEventListener('click', function (event) {
        if (event.target === popupOverlay) {
            closePopupFunc();
        }
    });

    // Second popup elements (Update Movies)
    const popupOverlay1 = document.getElementById('popupOverlay1');
    const closePopup1 = document.getElementById('closePopup1');
    const titleInput = document.getElementById('title1');
    const descriptionInput = document.getElementById('description1');
    const releaseDateInput = document.getElementById('release_date1');
    const genreInput = document.getElementById('genre1');
    const durationInput = document.getElementById('duration1');
    const updateImgButtons = document.querySelectorAll('.update_img');
    const submitForm1 = document.getElementById('submitForm1');
    
    let currentMovieId = null;

    // Function to open the second popup and fetch movie data
    window.openPopup1 = function (movie_id) { // Make it globally accessible
        fetch(`get_movie.php?id=${movie_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('movie_id').value = movie_id;
                    titleInput.value = data.movie.title;
                    descriptionInput.value = data.movie.description;
                    releaseDateInput.value = data.movie.release_date;
                    genreInput.value = data.movie.genre;
                    durationInput.value = data.movie.duration;
                    document.getElementById('movie_file1').innerText = data.movie.file_path;
                    document.getElementById('cover_image1').innerText = data.movie.cover_image;
                    console.log(data.movie.file_path);
                    console.log(data.movie.cover_image);
                    currentMovieId = movie_id; // Store current movie ID for update
                    popupOverlay1.style.display = 'block'; // Show the popup
                    console.log("Opening Update Movie popup...");
                } else {
                    console.error('Movie fetch failed:', data.message);
                }
            })
            .catch(error => console.error('Error fetching movie data:', error));
    };

    // Function to close the second popup
    function closePopupFunc1() {
        popupOverlay1.style.display = 'none';
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

    // Handle form submission
    submitForm1.addEventListener('click', handleFormSubmission);
});
