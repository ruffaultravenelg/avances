import { fetchPOST } from './fetcher.js';

/////////////
/// BACK ////
/////////////

const back = document.getElementById('back');

/**
 * Show the back overlay with a fade-in effect.
 */
function showBack() {
    back.style.display = 'initial';
    setTimeout(() => {
        back.className = 'back-showed';
    }, 1);
}

/**
 * Hide the back overlay with a fade-out effect.
 */
function hideBack() {
    back.className = '';
    setTimeout(() => {
        back.style.display = 'none';
    }, 600);
}

//////////////
/// POPUP ////
//////////////
const popup = document.getElementById('popup');

/**
 * Show a popup with the specified content by its ID.
 * Hides other popup content before displaying the chosen one.
 * @param {string} id - The ID of the popup content to display.
 */
function showPopup(id) {
    // Hide each child
    for (let i = 0; i < popup.children.length; i++) {
        const child = popup.children[i];
        child.hidden = child.id !== id;
    }

    // Show animation
    if (popup.className !== 'popup-showed') {
        showBack();
        popup.className = 'popup-showed';
    }
}

/**
 * Close the popup and hide the back overlay.
 */
function closePopup() {
    popup.className = '';
    hideBack();
}

/////////////////////
/// LOAD CLIENTS ////
/////////////////////

const clientsSection = document.querySelector('.clients');

/**
 * Fetches the list of advances from the server and updates the client display.
 */
const fetchAvances = async () => {
    const result = await fetchPOST('avances.php', null);

    if (result.success) {
        updateClientsList(result.avances);
    } else {
        console.error("Error fetching advances:", result.message);
    }
};

/**
 * Update the client list with the fetched advances.
 * @param {Array} avances - The list of advances to display.
 */
const updateClientsList = (avances) => {
    // Clear the client section before updating
    clientsSection.innerHTML = '';

    // Iterate over each advance and generate HTML
    avances.forEach((avance) => {
        avance.nom = capitalizeFirstLetter(avance.nom);

        const clientDiv = document.createElement('div');
        clientDiv.className = 'client';

        const clientNameP = document.createElement('p');
        clientNameP.className = 'client-name';
        clientNameP.textContent = avance.nom;

        const clientEuroP = document.createElement('p');
        clientEuroP.className = 'client-euro';
        clientEuroP.textContent = `${parseFloat(avance.somme).toFixed(2)}€`;

        clientDiv.appendChild(clientNameP);
        clientDiv.appendChild(clientEuroP);

        clientsSection.appendChild(clientDiv);

        clientDiv.onclick = () => {
            showValidatePopup(avance.nom, avance.somme);
        };
    });
};

/**
 * Capitalizes the first letter of a string.
 * @param {string} string - The string to capitalize.
 * @returns {string} - The capitalized string.
 */
const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

fetchAvances();

/////////////////////////
/// ADD ADVANCE: NAME ///
/////////////////////////
const popup_addavance_name_value = document.getElementById('popup_addavance_name_value');
const popup_addavance_name_cancel = document.getElementById('popup_addavance_name_cancel');
const popup_addavance_name_continue = document.getElementById('popup_addavance_name_continue');
const addAvanceBtn = document.getElementById('addAvanceBtn');

// Open the add advance name popup
addAvanceBtn.addEventListener('click', () => {
    popup_addavance_name_value.value = '';
    showPopup('popup_addavance_name');
});

// Cancel the popup
popup_addavance_name_cancel.addEventListener('click', () => {
    closePopup();
});

////////////////////////
/// ADD ADVANCE: SUM ///
////////////////////////
const popup_addavance_somme_value = document.getElementById('popup_addavance_somme_value');
const popup_addavance_somme_cancel = document.getElementById('popup_addavance_somme_cancel');
const popup_addavance_somme_validate = document.getElementById('popup_addavance_somme_validate');

// Continue to the sum popup
popup_addavance_name_continue.addEventListener('click', () => {
    popup_addavance_somme_value.value = '';
    showPopup('popup_addavance_somme');
});

// Cancel the popup
popup_addavance_somme_cancel.addEventListener('click', () => {
    closePopup();
});

// Validate the advance
popup_addavance_somme_validate.onclick = async () => {
    const nom = popup_addavance_name_value.value;
    const somme = popup_addavance_somme_value.value;

    // Check if fields are filled
    if (nom === '' || somme === '') {
        alert("Please fill out all fields.");
        return;
    }

    // Create form data
    const formData = new FormData();
    formData.append('nom', nom);
    formData.append('somme', somme);

    const result = await fetchPOST('add_avance.php', formData);

    if (result.success) {
        closePopup();
        fetchAvances();
    } else {
        alert("Error: " + result.message);
    }

};

////////////////
/// VALIDATE ///
////////////////
const popup_validate_title = document.getElementById('popup_validate_title');
const popup_validate_cancel = document.getElementById('popup_validate_cancel');
const popup_validate_validate = document.getElementById('popup_validate_validate');

/**
 * Show the validate popup with the provided client name and amount.
 * @param {string} nom - The client's name.
 * @param {number} somme - The amount owed.
 */
function showValidatePopup(nom, somme) {
    popup_validate_title.textContent = `${nom} owes ${somme}€`;

    showPopup('popup_validate');

    popup_validate_validate.onclick = async () => {
        await deleteAvance(nom);
        closePopup();
    };
}

// Cancel the validate popup
popup_validate_cancel.addEventListener('click', () => {
    closePopup();
});

//////////////////////
/// DELETE ADVANCE ///
//////////////////////

/**
 * Sends a request to delete an advance by client name.
 * @param {string} nom - The name of the client whose advance to delete.
 */
async function deleteAvance(nom) {
    const formData = new FormData();
    formData.append('nom', nom);

    const result = await fetchPOST('validate.php', formData);

    if (result.success) {
        fetchAvances(); // Reload the list of advances
    } else {
        console.error("Error while validating avance: " + result.message); // Display error message
    }
}

//////////////
//// MENU ////
//////////////
const menu = document.getElementById('menu');
const openMenu = document.getElementById('openMenu');
const closeMenu = document.getElementById('closeMenu');

// Open menu
openMenu.onclick = () => {
    setTimeout(() => {
        menu.className = 'menu-showed';
    }, 1);
};

// Close menu
closeMenu.onclick = () => {
    menu.className = '';
};