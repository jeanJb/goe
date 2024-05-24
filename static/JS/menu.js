let d2=document.querySelector(".d2")
let lists=document.querySelector(".lists")
d2.addEventListener("click", () => {
    lists.classList.toggle("newlist")
})

let logo=document.querySelector(".logo")
let btn=document.querySelector(".btn")
let esc=document.querySelector(".del")

logo.addEventListener("click", () => {
    btn.classList.toggle("vi")
})

esc.addEventListener("click", () => {
    btn.classList.remove("vi")
})