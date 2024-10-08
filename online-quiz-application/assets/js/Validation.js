class Validator {
    constructor() {
        this.signupBtn = document.getElementById('signupBtn');
        this.postBtn = document.getElementById('blogBtn');
        this.disableButton(this.signupBtn);
        this.disableButton(this.postBtn);
    }

    validateUsername(field) {
        if (this.isAlphaNumeric(field) && this.isWithinLength(field, 1, 25)) {
            this.setValid(field);
            return true;
        } else {
            return false;
        }
    }

    validatePassword(field) {
        if (this.isPasswordStrong(field) && this.isWithinLength(field, 8, 25)) {
            this.setValid(field);
            return true;
        } else {
            return false;
        }
    }

    validatePasswordMatch(password, confirmPassword) {
        if (password.value === confirmPassword.value) {
            this.setValid(confirmPassword);
            return true;
        } else {
            this.setInvalid(confirmPassword, 'Passwords must match');
            return false;
        }
    }

    validateTitle(field) {
        if (this.containsLettersSpacesNumbers(field) && this.isWithinLength(field, 1, 31)) {
            this.setValid(field);
            return true;
        } else {
            return false;
        }
    }

    isAlphaNumeric(field) {
        if (/^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/.test(field.value)) {
            return true;
        } else {
            this.setInvalid(field, `${field.name} must only contain letters, numbers, and _.`);
            return false;
        }
    }

    isPasswordStrong(field) {
        if (/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/.test(field.value)) {
            return true;
        } else {
            this.setInvalid(field, `${field.name} must contain one letter, one number, and one special character.`);
            return false;
        }
    }

    isWithinLength(field, minLength, maxLength) {
        if (field.value.length >= minLength && field.value.length <= maxLength) {
            return true;
        } else if (field.value.length < minLength) {
            this.setInvalid(field, `${field.name} must be at least ${minLength} character(s) long.`);
            return false;
        } else {
            this.setInvalid(field, `${field.name} must be shorter than ${maxLength} characters.`);
            return false;
        }
    }

    containsLettersSpacesNumbers(field) {
        if (/^[a-zA-Z0-9 ]*$/.test(field.value)) {
            return true;
        } else {
            this.setInvalid(field, `${field.name} can only contain letters, spaces and numbers.`);
            return false;
        }
    }

    setInvalid(field, message) {
        field.nextElementSibling.innerHTML = message;
        field.style.border = "medium solid red";
        this.disableButton(this.signupBtn);
        this.disableButton(this.postBtn);
    }

    setValid(field) {
        field.nextElementSibling.innerHTML = '';
        field.style.border = "medium solid green";
        this.enableButton(this.signupBtn);
        this.enableButton(this.postBtn);
    }

    disableButton(button) {
        if (button != null)
            button.disabled = true;
    }

    enableButton(button) {
        if (button != null)
            button.disabled = false;
    }

    validateEmail(field) {
        const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if (regex.test(field.value)) {
            this.setValid(field);
            return true;
        } else {
            this.setInvalid(field, 'Please enter a valid email address.');
            return false;
        }
    }

    validateNumberRange(field, min, max) {
        const value = Number(field.value);
        if (value >= min && value <= max) {
            this.setValid(field);
            return true;
        } else {
            this.setInvalid(field, `Value must be between ${min} and ${max}.`);
            return false;
        }
    }

    validateDate(field) {
        const regex = /^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/;
        if (regex.test(field.value)) {
            this.setValid(field);
            return true;
        } else {
            this.setInvalid(field, 'Date must be in format YYYY-MM-DD.');
            return false;
        }
    }

    validatePhoneNumber(field) {
        const regex = /^\+[1-9]{1}[0-9]{3,14}$/;
        if (regex.test(field.value)) {
            this.setValid(field);
            return true;
        } else {
            this.setInvalid(field, 'Please enter a valid phone number with country code.');
            return false;
        }
    }

    validateCheckbox(field) {
        if (field.checked) {
            this.setValid(field);
            return true;
        } else {
            this.setInvalid(field, 'This box must be checked.');
            return false;
        }
    }
    
    validateRadioButtons(name) {
        const radios = document.getElementsByName(name);
        for (let radio of radios) {
            if (radio.checked) {
                this.setValid(radio);
                return true;
            }
        }
        this.setInvalid(radios[0], 'Please select an option.');
        return false;
    }
      /**
     * Asynchronously validates a username by checking its availability against a server.
     * 
     * @param {HTMLElement} field The input field containing the username.
     * @param {string} apiUrl The URL to which the username will be sent for validation.
     * @returns {Promise<boolean>} A promise that resolves to true if the username is available, false otherwise.
     */
      async validateUsernameAvailability(field, apiUrl) {
        try {
            // Construct the URL with the username query parameter.
            const url = `${apiUrl}?username=${encodeURIComponent(field.value)}`;

            // Send a GET request to the server.
            const response = await fetch(url);

            // Parse the JSON response from the server.
            const data = await response.json();

            // Check if the 'isAvailable' flag is true in the response.
            if (data.isAvailable) {
                // If the username is available, set the field as valid.
                this.setValid(field);
                return true;
            } else {
                // If the username is not available, set the field as invalid and display an error message.
                this.setInvalid(field, 'Username is already taken.');
                return false;
            }
        } catch (error) {
            // In case of any errors during the request or response handling, log the error and set the field as invalid.
            console.error('Error validating username:', error);
            this.setInvalid(field, 'Error validating username.');
            return false;
        }
    }
}

// Instantiate the Validator class
const validator = new Validator();

// Add event listeners
var username = document.getElementById('Username');
if (username != null) {
    username.addEventListener("keyup", function() {
        validator.validateUsername(username);
    });
    username.addEventListener("blur", function() {
        validator.validateUsername(username);
    });
}

var password = document.getElementById('Password');
var rePassword = document.getElementById('RePassword');
if (password != null && rePassword != null) {
    password.addEventListener("blur", function() {
        validator.validatePassword(password);
    });
    rePassword.addEventListener("blur", function() {
        validator.validatePasswordMatch(password, rePassword);
    });
}

var blogTitle = document.getElementById('Title');
if (blogTitle != null) {
    blogTitle.addEventListener("keyup", function() {
        validator.validateTitle(blogTitle);
    });
    blogTitle.addEventListener("blur", function() {
        validator.validateTitle(blogTitle);
    });
}


/*
// Field values.
var signupBtn = document.getElementById('signupBtn');
var postBtn = document.getElementById('blogBtn');
disableButton(signupBtn);
disableButton(postBtn);
// Sign up vars.
var username = document.getElementById('Username');
var password = document.getElementById('Password');
var rePassword = document.getElementById('RePassword');
var blogTitle = document.getElementById('Title');
var blogPost = document.getElementById('Post');

var field6 = document.getElementById('Recyclable');


if (username != null) {
    username.addEventListener("keyup", function() {
        if (checkIfAlphaNumeric(username)) return;
    });
    username.addEventListener("blur", function() {
        if (checkIfAlphaNumeric(username)) {
            if (checkWithinLength(username, 1, 25)) return;
        }
    });
}

if (password != null) {
    password.addEventListener("blur", function() {
        if (checkWithinLength(password, 8, 25)) {
            if (validatePwd(password)) return;
        }
    });
}

if (rePassword != null) {
    rePassword.addEventListener("blur", function() {
        if (checkIfMatch(password, rePassword)) return;
    });
}

if (blogTitle != null) {
    blogTitle.addEventListener("keyup", function() {
        if (lettersSpacesNumbers(blogTitle)) {
            if (checkWithinLength(blogTitle, 1, 31)) return;
        }
    });
    blogTitle.addEventListener("blur", function() {
        if (lettersSpacesNumbers(blogTitle)) {
            if (checkWithinLength(blogTitle, 1, 31)) return;
        }
    });
}

if (blogPost != null) {
    blogPost.addEventListener("keyup", function() {
        if (checkIfEmpty(blogPost)) return;

    });
    blogPost.addEventListener("blur", function() {
        if (checkIfEmpty(blogPost)) return;
    });
}

function validatePwd(field) {
    if (/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must contain one letter, one number, and one special character.`);
        return false;
    }
}

function validEmail(field) {
    let regex = new RegExp(".{1,}@[^.]{1,}");
    if (regex.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must be valid.`);
        return false;
    }
}

function checkIfOnlyNumbers(field) {
    if (/^[0-9]*$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must only contain numbers.`);
        return false;
    }
}

function checkIfOnlyPositive(field) {
    if (/^(?!(?:0|0\.0|0\.00)$)[+]?\d+(\.\d|\.\d[0-9])?$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must be a valid value.`);
        return false;
    }
}

function checkIfOnlyLetters(field) {
    if (/^[a-zA-Z]+$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must only contain letters.`);
        return false;
    }
}

function checkIfEmpty(field) {
    if (isEmpty(field.value.trim())) {
        setInvalid(field, `${field.name} must not be empty.`);
        return true;
    } else {
        setValid(field);
        return false
    }
}

// Returns true if value is empty.
function isEmpty(value) {
    if (value == "" || value == null || value.Length == 0) {
        return true;
    } else {
        return false;
    }
}

function checkIfAlphaNumeric(field) {
    if (/^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must only contain letters, numbers, and _.`);
        return false;
    }
}

function checkIfMatch(username, Password) {
    if (username.value === Password.value) {
        setValid(Password);
        return true;
    } else {
        setInvalid(Password, 'Passwords must match');
        return false;
    }
}

function checkIfMatchTest(username, Password, event) {
    const key = event.key;
    x = username.value.indexOf(key);
    y = Password.value.length - 1;

    if (username.value.indexOf(key) == Password.value.length - 1) {
        setValid(Password);
        return true;
    } else {
        setInvalid(Password, 'Passwords must match');
        return false;
    }
}

function checkWithinLength(field, minLength, maxLength) {
    if (field.value.length >= minLength && field.value.length < maxLength) {
        setValid(field);
        return true;
    } else if (field.value.length < minLength) {
        setInvalid(field, `${field.name} must be at least ${minLength} character(s) long.`);
        return false;
    } else {
        setInvalid(field, `${field.name} must be shorter than ${maxLength - 1} characters.`);
        return false;
    }
}

function checkForSpaces(field) {
    if (/^\S+$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must not contain spaces.`);
        return false;
    }
}

function checkOnlyLettersSpaces(field) {
    if (/^[a-zA-Z\s]*$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} can only contain letters and spaces.`);
        return false;
    }
}

function hideForm(form) {
    if (form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}

function validDate(field) {
    if (/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} must be in format YYYY-MM-DD.`);
        return false;
    }
}

function lettersSpacesNumbers(field) {
    if (/^[a-zA-Z0-9 ]*$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${field.name} can only contain letters, spaces and numbers.`);
        return false;
    }
}

// Sets field to invalid and gives error message.
function setInvalid(field, message) {
    field.nextElementSibling.innerHTML = message;
    field.style.border = "medium solid red";
    disableButton(signupBtn);
    disableButton(postBtn);
}

// Sets field to valid and empties message.
function setValid(field) {
    field.nextElementSibling.innerHTML = '';
    field.style.border = "medium solid green";
    enableButton(signupBtn);
    enableButton(postBtn);
}

function enableButton(button) {
    if (button != null)
        button.disabled = false;
}

function disableButton(button) {
    if (button != null)
        button.disabled = true;
}

*/