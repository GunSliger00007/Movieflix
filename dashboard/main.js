let activeClass=document.querySelectorAll(".nav a");
activeClass.forEach((e)=>{
    e.addEventListener("click",(event)=>{
        event.preventDefault;
        activeClass.forEach((el)=>el.classList.remove("active"));
        e.classList.add("active");
    })
})
document.addEventListener('DOMContentLoaded', function () {
    // First popup
    const popupOverlay = document.getElementById('popupOverlay');
    const closePopup = document.getElementById('closePopup');
    const emailInput = document.getElementById('emailInput');
    const addMoviesButton = document.getElementById('addmovies'); // Button that triggers the first popup

    // Function to open the first popup
    function openPopup() {
        popupOverlay.style.display = 'block';
    }

    // Function to close the first popup
    function closePopupFunc() {
        popupOverlay.style.display = 'none';
    }

    // Function to submit the signup form for the first popup
    function submitForm() {
        const email = emailInput.value;
        console.log(`Email submitted (popup 1): ${email}`);
        closePopupFunc(); // Close the popup after form submission
    }

    // Event listeners for the first popup
    addMoviesButton.addEventListener('click', openPopup);
    closePopup.addEventListener('click', closePopupFunc);
    popupOverlay.addEventListener('click', function (event) {
        if (event.target === popupOverlay) {
            closePopupFunc();
        }
    });

    // Second popup for multiple "update" buttons
    const popupOverlay1 = document.getElementById('popupOverlay1');
    const closePopup1 = document.getElementById('closePopup1');
    const emailInput1 = document.getElementById('emailInput1');
    const updateImgButtons = document.querySelectorAll('.update_img'); // All buttons with class 'update_img'

    // Function to open the second popup
    function openPopup1() {
        popupOverlay1.style.display = 'block';
    }

    // Function to close the second popup
    function closePopupFunc1() {
        popupOverlay1.style.display = 'none';
    }

    // Function to submit the signup form for the second popup
    function submitForm1() {
        const email = emailInput1.value;
        console.log(`Email submitted (popup 2): ${email}`);
        closePopupFunc1(); // Close the popup after form submission
    }

    // Add event listener to each update image button
    updateImgButtons.forEach(function (button) {
        button.addEventListener('click', openPopup1);
    });

    // Close the second popup when the close button is clicked
    closePopup1.addEventListener('click', closePopupFunc1);

    // Close the second popup when clicking outside the popup content
    popupOverlay1.addEventListener('click', function (event) {
        if (event.target === popupOverlay1) {
            closePopupFunc1();
        }
    });
});
