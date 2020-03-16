/**
 * Toggle checkboxes on Login and Register pages, toggles on and off the password 'hide' in input box.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-02-17
 * Last Updated: 2020-03-15
 * File Name: toggle.js
 */

/**
 * Toggles the password and password confirmation input boxes on Registration Form.
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

/**
 * Toggles the password input box on Login Form.
 */
function loginPasswordToggle() {
    let password = document.getElementById("password");
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}