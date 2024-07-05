document.addEventListener("DOMContentLoaded", function() {
    const editBtn = document.getElementById("edit-btn");
    const backBtn = document.getElementById("back-btn");

    editBtn.addEventListener("click", function() {
        window.location.href = "edit_profile.php";
    });

    backBtn.addEventListener("click", function() {
        window.location.href = "mypage.php";
    });
});