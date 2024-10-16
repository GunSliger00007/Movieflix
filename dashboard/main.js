let activeClass=document.querySelectorAll(".nav a");
activeClass.forEach((e)=>{
    e.addEventListener("click",(event)=>{
        event.preventDefault;
        activeClass.forEach((el)=>el.classList.remove("active"));
        e.classList.add("active");
    })
})
document.addEventListener('DOMContentLoaded', function () {

    const popupOverlay = document.getElementById('popupOverlay');
    const popup = document.getElementById('popup');
    const closePopup = document.getElementById('closePopup');
    const emailInput = document.getElementById('emailInput');
    const addMoviesButton = document.getElementById('addmovies'); // Button that triggers the popup

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
        // Add your form submission logic here
        console.log(`Email submitted: ${email}`);
        closePopupFunc(); // Close the popup after form submission
    }

    // Event listeners

    // Open the popup when the 'addmovies' button is clicked
    addMoviesButton.addEventListener('click', openPopup);

    // Close the popup when the close button is clicked
    closePopup.addEventListener('click', closePopupFunc);

    // Close the popup when clicking outside the popup content
    popupOverlay.addEventListener('click', function (event) {
        if (event.target === popupOverlay) {
            closePopupFunc();
        }
    });

});
