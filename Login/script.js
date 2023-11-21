// Darle valor color al input
const textInputs = document.querySelectorAll("input");

textInputs.forEach(textInput => {
    textInput.addEventListener("focus", () => {
        let parent = textInput.parentNode;
        parent.classList.add("active");
    });

    textInput.addEventListener("blur", () => {
        let parent = textInput.parentNode;
        parent.classList.remove("active");
    });
});

// Mostrar/ocultar el boton de ¡ver!contraseña
const passwordInput = document.querySelector(".password-input");
const eyeBtn = document.querySelector(".eye-btn");

eyeBtn.addEventListener("click", () => {
    if(passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeBtn.innerHTML = "<i class='uil uil-eye'></i>";
    }

    else{
        passwordInput.type = "password";
        eyeBtn.innerHTML = "<i class='uil uil-eye-slash'></i>";
    }
});
