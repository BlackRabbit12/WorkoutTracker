/**
 * Client side validation with Javascript for Login and part of Register.
 *
 * @author Bridget Black
 * @author Chad Drennan
 * @version 1.0
 * 2020-03-15
 */

//grabs the login form
document.getElementById('formatted-login').onsubmit = validateLogin;

/**
 * Validates the login and the username + password for registration.
 * @returns {boolean}
 */
function validateLogin() {
    //user name
    let user = document.getElementById('user-name').value;
    user = user.trim();
    if (user === "") {
        document.getElementById('user-name').style.borderColor = "red";
        return false;
    }
    if (!(/^[0-9a-zA-Z]+$/.test(user))) {
        document.getElementById('user-name').style.borderColor = "red";
        return false;
    }

    //password
    let password = document.getElementById('password').value;
    password = password.trim();
    if (password === "") {
        document.getElementById('password').style.borderColor = "red";
        return false;
    }
    if (!(/^[0-9a-zA-Z]+$/.test(password))) {
        document.getElementById('password').style.borderColor = "red";
        return false;
    }

    return true;
}