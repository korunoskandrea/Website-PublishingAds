const showPopUpBtn = document.querySelector('.select-add-btn')
const popupDialog = document.querySelector('.popup-dialog') // getElementByClasName querySekector vrne prvi el
const categoryButtons = document.querySelectorAll('.select-btn')
const addCategoriesBtn = document.querySelector('.popup-add-btn')
const selectedItemsList = document.querySelector('.selected-items-list');
const addedCategoriesRemoveButtons = document.querySelectorAll('.select-remove-btn');

let selectedCategoriesIds = [];

showPopUpBtn.addEventListener('click', () => {
    popupDialog.style.display = 'block'
});

for (const removeButton of addedCategoriesRemoveButtons) {
    removeButton.addEventListener('click', onRemoveButtonClicked);
}
popupDialog.addEventListener('click', (event) => {
    if(event.target.classList.contains('popup-dialog')) {
        popupDialog.style.display = 'none'
        selectedCategoriesIds = [];
    }
})

for (const categoryButton of categoryButtons) {
    categoryButton.addEventListener('click', (event) => {
        const id = event.target.dataset.id;
        const indexOfId = selectedCategoriesIds.indexOf(id);
        if (indexOfId >= 0) {
            event.target.classList.remove('select')
            selectedCategoriesIds.splice(indexOfId, 1)
        } else {
            event.target.classList.add('select')
            selectedCategoriesIds.push(id)
        }
    })
}

addCategoriesBtn.addEventListener('click', () => {
   for (const id of selectedCategoriesIds) {
       const categoryButton = document.querySelector(`#cat-${id}`)
       selectedItemsList.appendChild(createSelectedItem(id, categoryButton.textContent));
       categoryButton.disabled = true;
   }
    popupDialog.style.display = 'none'
    selectedCategoriesIds = [];
});

function createSelectedItem(id, name) {
    const cardContainer = document.createElement('div')
    cardContainer.className = 'selected-item card';

    const hiddenIdInput = document.createElement('input');
    hiddenIdInput.type = 'checkbox';
    hiddenIdInput.name = 'categories[]';
    hiddenIdInput.hidden = true;
    hiddenIdInput.checked = true;
    hiddenIdInput.value = id;

    const spanItemTitle = document.createElement('span');
    spanItemTitle.className = 'selected-item-title';
    spanItemTitle.textContent = name;

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-danger select-remove-btn';
    removeButton.textContent = 'x';
    removeButton.dataset.id = id;
    removeButton.addEventListener('click', onRemoveButtonClicked)

    cardContainer.appendChild(hiddenIdInput);
    cardContainer.appendChild(spanItemTitle);
    cardContainer.appendChild(removeButton);

    return cardContainer;
}

function onRemoveButtonClicked(event) {
    const id = event.target.dataset.id;
    const parent = event.target.parentElement
    selectedItemsList.removeChild(parent)
    const categoryButton = document.querySelector(`#cat-${id}`)
    console.log(categoryButton)
    categoryButton.disabled = false;
    categoryButton.classList.remove('select');
}
//////////////////// IMAGE UPLOAD //////////////////////////////////////////////
const addImageButton = document.querySelector('.add-img-btn');
const imageSelectInput = document.querySelector('#img-select');
const uploadedImagesList = document.querySelector('.uploaded-images-list');
const removeImageButtons = document.querySelectorAll('.remove-img-btn');

for (const removeButton of removeImageButtons) {
    removeButton.addEventListener('click', onRemoveImgPreview);
}

addImageButton.addEventListener('click', () => {
    imageSelectInput.click();
});

imageSelectInput.addEventListener('change', (event) => {
    const files = event.target.files;
    for (const file of files) {
        const imgPreview = createImgPreview(file);
        uploadedImagesList.appendChild(imgPreview);
    }
});

function createImgPreview(file) {
    const container = document.createElement('div');
    container.className = 'img-preview'

    const overlay = document.createElement('div');
    overlay.className = 'overlay';

    const hiddenInput = document.createElement('input')
    hiddenInput.type = 'file';
    hiddenInput.name = 'image[]';
    hiddenInput.hidden = true;

    const uploadedFile = new File([file], file.name, {
        type: file.type,
    });

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(uploadedFile);

    hiddenInput.files = dataTransfer.files;

    const img = document.createElement('img');
    img.className = 'uploaded-img';
    img.src =  URL.createObjectURL(file);

    const removeButton = document.createElement('button');
    removeButton.className = 'btn btn-danger remove-img-btn';
    removeButton.textContent = 'x';
    removeButton.type = 'button';
    removeButton.addEventListener('click', onRemoveImgPreview);

    container.appendChild(overlay);
    container.appendChild(img);
    container.appendChild(hiddenInput);
    container.appendChild(removeButton);
    return container;
}

function onRemoveImgPreview(event) {
    const removeButton = event.target;
    const imgPreviewContainer = removeButton.parentElement;
    // check if images is 'existing'
    if (removeButton.classList.contains('existing-img')) {
        // Add hidden with id of the image that was removed
        const removedIdInput = document.createElement('input');
        removedIdInput.type = 'checkbox';
        removedIdInput.value = removeButton.dataset.id;
        removedIdInput.hidden = true;
        removedIdInput.checked = true;
        removedIdInput.name = 'removedImages[]';
        uploadedImagesList.appendChild(removedIdInput);
    }

    uploadedImagesList.removeChild(imgPreviewContainer);

}