// const routes = ['index.php', 'user-ads.view.php', 'register.view.php', 'publishedad.view.php' ,'login.view.php', 'publishad.view.php', 'deleted-ads.view.php'];
// const navigationButtons = document.querySelectorAll('.nav-btn');
// const currentLocation = window.location.href;
//
// routes.forEach((route) => {
//     if (currentLocation.includes(route)) {
//         navigationButtons.forEach(button => {
//             if (button.href.includes(route)) {
//                 button.classList.add('nav-btn-active');
//             } else {
//                 button.classList.remove('nav-btn-active')
//             }
//         });
//         return;
//     }
// })
const drawerButton = document.querySelector('#drawerButton')
const drawerContainer = document.querySelector('.drawer-container')

drawerButton.addEventListener('click', (event) => {
    drawerContainer.style.display = 'block'
})

drawerContainer.addEventListener('click', (event) => {
    if (event.target.classList.contains('drawer-container')) {
        drawerContainer.style.display = 'none'
    }
})