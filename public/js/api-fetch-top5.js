const commentsContainer = document.querySelector('.comments')

async function fetchTop5Comments() {
    const result = await fetch(`/api/comments`)
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

    const commentParagraph = document.createElement('p')
    const dateParagraph = document.createElement('p')
    const countryText = document.createElement('h6')

    commentParagraph.textContent = comment['text_comment']
    dateParagraph.textContent = comment['created_at']
    countryText.textContent = country

    const adLink = document.createElement('a');
    adLink.className = "btn read-ad-btn p-1 mb-10 rounded mx-auto"
    adLink.textContent = "View Ad"
    adLink.href = `/ads/detail/${comment['ad_id']}`

    commentContainer.appendChild(commentParagraph)
    commentContainer.appendChild(dateParagraph)
    commentContainer.appendChild(countryText)
    commentContainer.appendChild(adLink)

    return commentContainer
}

fetchTop5Comments()