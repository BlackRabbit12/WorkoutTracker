/**
 * Client side validation with Javascript for Registration, finishes on login_validation.js.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-03-15
 */

//grabs the registration form
document.getElementById('formatted-register').onsubmit = validateRegister;

/**
 * Validates the registration form, uses login_validation.js for the username and password.
 * @returns {boolean}
 */
function validateRegister() {
    //first name
    let first = document.getElementById('first-name').value;
    first = first.trim();
    if (first === "") {
        document.getElementById('first-name').style.borderColor = "red";
        return false;
    }
    if (!(/^[0-9a-zA-Z]+$/.test(first))) {
        document.getElementById('first-name').style.borderColor = "red";
        return false;
    }

    //last name
    let last = document.getElementById('last-name').value;
    last = last.trim();
    if (last === "") {
        document.getElementById('last-name').style.borderColor = "red";
        return false;
    }
    if (!(/^[0-9a-zA-Z]+$/.test(last))) {
        document.getElementById('last-name').style.borderColor = "red";
        return false;
    }

    let valid = validateLogin();
    if (!valid) {
        return false;
    }

    //password confirmation
    let passwordConf = document.getElementById('password-confirmation').value;
    passwordConf = passwordConf.trim();
    if (passwordConf === "") {
        document.getElementById('password-confirmation').style.borderColor = "red";
        return false;
    }
    if (!(/^[0-9a-zA-Z]+$/.test(passwordConf))) {
        document.getElementById('password-confirmation').style.borderColor = "red";
        return false;
    }

    return true;
}