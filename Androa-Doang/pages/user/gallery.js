document.addEventListener("click", function(e){
    if (e.target.classList.contains("like")) {
        e.target.classList.toggle("liked");
    }
    if (e.target.classList.contains("comment")) {
        e.target.classList.toggle("active");
    }
    if (e.target.classList.contains("share")) {
        e.target.classList.toggle("active");
    }
});

// Validasi form upload
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".upload-section form");
    const photo = document.getElementById("photo");
    const desc = document.getElementById("desc");
    const style = document.getElementById("style");

    form.addEventListener("submit", function (e) {
        let errorMessage = "";

        // 1. PHOTO CHECK
        if (photo.files.length === 0) {
            errorMessage += "- Please upload a photo.\n";
        } else {
            const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
            if (!allowedTypes.includes(photo.files[0].type)) {
                errorMessage += "- Photo must be JPG or PNG.\n";
            }
        }

        // 2. DESCRIPTION CHECK
        if (desc.value.trim() === "") {
            errorMessage += "- Description cannot be empty.\n";
        }

        // 3. DESCRIPTION LENGTH LIMIT
        if (desc.value.length > 200) {
            errorMessage += "- Description must be under 200 characters.\n";
        }

        // 4. STYLE CATEGORY CHECK
        if (style.value === "") {
            errorMessage += "- Please choose a style category.\n";
        }

        // BLOCK SUBMIT JIKA ADA ERROR
        if (errorMessage !== "") {
            e.preventDefault();
            alert("Upload failed:\n\n" + errorMessage);
        }
    });
});
