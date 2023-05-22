const showConfirmButtons = document.querySelectorAll(".show-confirm-btn")
const modalWindowContainer = document.querySelector('.modal-window-container')
const deleteConfirmButton = document.querySelector('.delete-confirm-btn')
const usernameSpan = document.querySelector('.delete-user-text')

modalWindowContainer.addEventListener('click', (event) => {
    console.log(event.target);
    if (event.target.classList.contains('modal-window-container') || event.target.classList.contains('cancel-delete')) {
        modalWindowContainer.style.display = 'none'
        deleteConfirmButton.href = ""
        usernameSpan.textContent = ""
    }
})

showConfirmButtons.forEach(button => {
    const id = button.dataset.id
    const name = button.dataset.name
    button.addEventListener('click', event => {
        modalWindowContainer.style.display = 'block'
        deleteConfirmButton.href = `/user/admin/delete/${id}`
        usernameSpan.textContent = name
    })
})