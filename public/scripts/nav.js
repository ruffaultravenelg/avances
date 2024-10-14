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