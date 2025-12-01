document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".dropdown");
    const icon = document.getElementById("profileIcon");

    dropdown.addEventListener("show.bs.dropdown", function () {
        icon.classList.remove("bi-caret-up-square");
        icon.classList.add("bi-caret-down-square");
    });

    dropdown.addEventListener("hide.bs.dropdown", function () {
        icon.classList.remove("bi-caret-down-square");
        icon.classList.add("bi-caret-up-square");
    });
});
