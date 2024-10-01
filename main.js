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



