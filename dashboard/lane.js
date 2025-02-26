document.addEventListener('DOMContentLoaded', function () {
    // Handle active class toggle for navigation links


    let activeClass = document.querySelectorAll(".nav a");

    activeClass.forEach((link) => {
        link.addEventListener("click", (event) => {
            // Remove 'active' class from all links
            activeClass.forEach((el) => el.classList.remove("active"));

            // Add 'active' class to the clicked link
            link.classList.add("active");

            // Allow default href behavior (navigation)
            // No event.preventDefault() here, so it should navigate
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
                    document.getElementById('movie_id1').value = movie_id;
                    console.log(movie_id)
                    console.log(data);
                    titleInput.value = data.movie.title;
                    descriptionInput.value = data.movie.description;
                    releaseDateInput.value = data.movie.release_date;
                    durationInput.value = data.movie.duration;
                    const categorySelect = document.getElementById("Categories1");

                    // Clear any existing options in the select dropdown
                    categorySelect.innerHTML = '';

                    // Add the related categories first
                    data.related_categories.forEach(function (category) {
                        const option = document.createElement("option");
                        option.value = category.category_id;
                        option.textContent = category.category_name;
                        categorySelect.appendChild(option);
                    });

                    // Then add the non-related categories
                    data.non_related_categories.forEach(function (category) {
                        const option = document.createElement("option");
                        option.value = category.category_id;
                        option.textContent = category.category_name;
                        categorySelect.appendChild(option);
                    });

                    document.getElementById('moviefile_name').innerText = data.movie.file_path;
                    document.getElementById('cover_image_name').innerText = data.movie.cover_image;
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



});


// Movie file selection logic (using div)
document.getElementById('moviefile_name').addEventListener('click', function () {
    const movieFileInput = document.getElementById('movie_file1');

    // Only trigger the dialog if no file has been selected yet
    if (!movieFileInput.files.length) {
        movieFileInput.click();  // Trigger file input
    }
});

document.getElementById('movie_file1').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const allowedTypes = ['video/mp4','video/mkv'];  // Example allowed video file types
    const maxSize = 2.5 * 1024 * 1024 * 1024;  // Max file size 2.5GB (in bytes)

    // Validate file type
    if (file && !allowedTypes.includes(file.type)) {
        alert('Invalid file type. Please select a video file (mp4, webm, avi).');
        event.target.value = '';  // Clear the input
        return;  // Stop further execution
    }

    // Validate file size
    if (file && file.size > maxSize) {
        alert('File size exceeds the limit of 2.5GB.');
        event.target.value = '';  // Clear the input
        return;  // Stop further execution
    }

    // If file is valid, display the name in the div
    if (file) {
        document.getElementById('moviefile_name').innerText = file.name;
    }
});

// Cover image selection logic (using div)
document.getElementById('cover_image_name').addEventListener('click', function () {
    const coverImageInput = document.getElementById('cover_image1');

    // Only trigger the dialog if no file has been selected yet
    if (!coverImageInput.files.length) {
        coverImageInput.click();  // Trigger file input
    }
});

document.getElementById('cover_image1').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];  // Example allowed image file types
    const maxSize = 5 * 1024 * 1024;  // Max file size 5MB (in bytes)

    // Validate file type
    if (file && !allowedTypes.includes(file.type)) {
        alert('Invalid file type. Please select an image file (jpg, png, gif).');
        event.target.value = '';  // Clear the input
        return;  // Stop further execution
    }

    // Validate file size
    if (file && file.size > maxSize) {
        alert('File size exceeds the limit of 5MB.');
        event.target.value = '';  // Clear the input
        return;  // Stop further execution
    }

    // If file is valid, display the name in the div
    if (file) {
        document.getElementById('cover_image_name').innerText = file.name;
    }
});

// Form submission logic (for retaining cover image if not changed)
document.getElementById('updateform').addEventListener('submit', function (event) {
    const coverImageInput = document.getElementById('cover_image1');
    const oldCoverImageInput = document.getElementById('old_cover_image');

    // If no new cover image is selected, send the old cover image path
    if (!coverImageInput.files.length) {
        coverImageInput.disabled = true;  // Disable the file input to avoid sending empty file data
        coverImageInput.value = oldCoverImageInput.value;  // Retain old cover image
    }
});

