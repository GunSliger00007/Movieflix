let activeClass=document.querySelectorAll(".nav a");
activeClass.forEach((e)=>{
    e.addEventListener("click",(event)=>{
        event.preventDefault;
        activeClass.forEach((el)=>el.classList.remove("active"));
        e.classList.add("active");
    })
})
