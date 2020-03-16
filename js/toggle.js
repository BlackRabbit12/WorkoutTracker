/**
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-02-17
 * Last Updated: 2020-03-14
 * File Name: toggle.js
 */


function passwordToggle() {
    let password = document.getElementById("password");
    let confirmation = document.getElementById("password-confirmation");
    if (password.type === "password" && confirmation.type === "password") {
        password.type = "text";
        confirmation.type = "text";
    } else {
        password.type = "password";
        confirmation.type = "password";
    }
}

function loginPasswordToggle() {
    let password = document.getElementById("password");
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}