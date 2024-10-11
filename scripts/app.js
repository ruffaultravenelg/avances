//////////////
//// BACK ////
//////////////
const back = document.getElementById('back');

// Show back
function showBack(){

    back.style.display = 'initial'
    setTimeout(() => {
        back.className = 'back-showed';
    }, 1);

}

// Hide back
function hideBack(){

    back.className = '';
    setTimeout(() => {
        back.style.display = 'none';
    }, 600);

}

///////////////
//// POPUP ////
///////////////
const popup = document.getElementById('popup');
function showPopup(id){

    // Hide each child
    for (let i = 0; i < popup.children.length; i++) {
        const child = popup.children[i];
        child.hidden = child.id !== id;
    }

    // Show animation
    if (popup.className == 'popup-showed'){
        
    } else {
        showBack();
        popup.className = 'popup-showed';
    }
}

// Close popup
function closePopup(){
    popup.className = '';
    hideBack();
}

//////////////////////
//// LOAD CLIENTS ////
//////////////////////
const clientsSection = document.querySelector('.clients');

// Fonction pour récupérer les avances et mettre à jour l'affichage
const fetchAvances = async () => {
    try {
        const response = await fetch('avances.php'); // Appel AJAX à avances.php
        const result = await response.json();

        if (result.success) {
            updateClientsList(result.avances); // Appel de la fonction pour mettre à jour l'affichage
        } else {
            console.error("Erreur lors de la récupération des avances :", result.message);
        }
    } catch (error) {
        console.error("Erreur réseau ou de serveur :", error);
    }
};

// Fonction pour mettre à jour la liste des clients
const updateClientsList = (avances) => {
    // Vider la section des clients avant de la mettre à jour
    clientsSection.innerHTML = '';

    // Parcourir chaque avance et générer le HTML correspondant
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

        clientDiv.onclick = ()=>{
            showValidatePopup(avance.nom, avance.somme);
        };
    });
};

// Fonction pour capitaliser la première lettre
const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};
fetchAvances();

//////////////////////////
//// ADD AVANCE: NAME ////
//////////////////////////
const popup_addavance_name_value = document.getElementById('popup_addavance_name_value');
const popup_addavance_name_cancel = document.getElementById('popup_addavance_name_cancel');
const popup_addavance_name_continue = document.getElementById('popup_addavance_name_continue');
const addAvanceBtn = document.getElementById('addAvanceBtn');

// Open avance menu
addAvanceBtn.addEventListener('click', ()=>{
    popup_addavance_name_value.value = '';
    popup_addavance_name_value.focus();
    showPopup('popup_addavance_name');
});

// Cancel
popup_addavance_name_cancel.addEventListener('click', ()=>{
    closePopup();
});


///////////////////////////
//// ADD AVANCE: SOMME ////
///////////////////////////
const popup_addavance_somme_value = document.getElementById('popup_addavance_somme_value');
const popup_addavance_somme_cancel = document.getElementById('popup_addavance_somme_cancel');
const popup_addavance_somme_validate = document.getElementById('popup_addavance_somme_validate');

// Continue
popup_addavance_name_continue.addEventListener('click', ()=>{
    popup_addavance_somme_value.value = '';
    popup_addavance_somme_value.focus();
    showPopup('popup_addavance_somme');
});

// Cancel
popup_addavance_somme_cancel.addEventListener('click', ()=>{
    closePopup();
});

// Validate
popup_addavance_somme_validate.onclick = () => {

    // Récupérer les valeurs du formulaire
    const nom = popup_addavance_name_value.value;
    const somme = popup_addavance_somme_value.value;

    // Vérifier si les champs sont remplis
    if (nom === '' || somme === '') {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    // Create form
    var formData = new FormData(this);
    formData.append('nom', nom);
    formData.append('somme', somme);

    // Send request to server
    fetch('add_avance.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
            closePopup();
            fetchAvances();
          } else {
              alert("Erreur: " + data.message);
          }
      });

};

//////////////////
//// VALIDATE ////
//////////////////
const popup_validate_title = document.getElementById('popup_validate_title');
const popup_validate_cancel = document.getElementById('popup_validate_cancel');
const popup_validate_validate = document.getElementById('popup_validate_validate');

function showValidatePopup(nom, somme){

    // Set popup title
    popup_validate_title.textContent = `${nom} doit ${somme}€`;

    // Show popup
    showPopup('popup_validate');

    // Set validate button action
    popup_validate_validate.onclick = ()=>{
        deleteAvance(nom);
        closePopup();
    };

}

// Cancel
popup_validate_cancel.addEventListener('click', ()=>{
    closePopup();
});

///////////////////////
//// DELETE AVANCE ////
///////////////////////

// Fonction pour supprimer une avance
async function deleteAvance(nom) {
    try {
        const response = await fetch('validate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `nom=${encodeURIComponent(nom)}`
        });

        const result = await response.json();

        if (result.success) {
            fetchAvances();  // Recharger la liste des avances
        } else {
            console.error(result.message);  // Afficher un message d'erreur
        }
    } catch (error) {
        console.error('Erreur lors de la suppression de l\'avance:', error);
    }
};