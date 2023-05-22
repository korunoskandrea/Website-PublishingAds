const commentsContainer = document.querySelector('.comments')
const commentTextField = document.querySelector('#comment-field')
const commentButton = document.querySelector('#comment-submit')
const errorBar = document.querySelector('.alert-danger')

async function fetchComments()  {
    const result = await fetch(`/api/comments/${commentsContainer.dataset.id}`)
    const data = await result.json()
    for (const comment of data['comments']) {
        commentsContainer.appendChild(await createDOMComment(comment))
    }
}

async function createDOMComment(comment) {
    const result = await fetch(`http://ip-api.com/json/${comment["ip"]}`)
    const country = (await result.json())["country"]
    const commentContainer = document.createElement('div')
    commentContainer.className = 'card'
    commentContainer.id = `ad-${comment['id']}`

    const commentParagraph = document.createElement('p')
    const dateParagraph = document.createElement('p')
    const countryText = document.createElement('h6')

    commentParagraph.textContent = comment['text_comment']
    dateParagraph.textContent = comment['created_at']
    countryText.textContent = country

    const removeButton = document.createElement('button');
    removeButton.className = 'btn btn-danger'
    removeButton.textContent = 'Remove Comment'

    commentContainer.appendChild(commentParagraph)
    commentContainer.appendChild(dateParagraph)
    commentContainer.appendChild(countryText)
    if (comment['user_id'] == commentsContainer.dataset.user) {
        removeButton.addEventListener('click', onRemoveComment)
        removeButton.dataset.id = comment['id']
        commentContainer.appendChild(removeButton)
    }
    return commentContainer
}

commentButton.addEventListener('click',async () => {
    const ipResult = await fetch(`https://api.ipify.org/?format=json`)
    const ip = (await ipResult.json())["ip"]
    const response = await fetch(
        `/api/comments/${commentsContainer.dataset.id}`,
        {
            method: "POST",
            mode: 'cors',// pravila,
            headers: { // kaj se poslje
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                comment: commentTextField.value,
                ip: ip
            })
        }
    )
    const data = await response.json()
    if (response.status !== 200) {
        errorBar.textContent = data['error']
        errorBar.style.display = 'block'
    } else {
        errorBar.style.display = 'none'
        commentsContainer.appendChild(await createDOMComment(data['comment']))
    }
})

async function onRemoveComment(event) {
    const result = await fetch(
        `/api/comments/delete/${event.target.dataset.id}`,
        {
            method: "DELETE",
            mode: 'cors',// pravila,
            headers: {
                'Content-Type': 'application/json'
            }
        }
    )
    if (result.status === 200) {
        const removedComment = document.querySelector(`#ad-${event.target.dataset.id}`)
        commentsContainer.removeChild(removedComment)
    }
}

fetchComments()


// fetch(`/api/comments/${commentsContainer.dataset.id}`)
//     .then(result => {
//         return result.json()
//     })
//     .then(data => {
//         data['comments'].forEach(comment => commentsContainer.appendChild(createDOMComment(comment)))
//     })

