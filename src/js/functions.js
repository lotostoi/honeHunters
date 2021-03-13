export const getAllMessages = async () => {
    const response = await fetch('index/all')
    const messages = await response.json()
    return messages
}


const renderMessage = (mes) => {
    return `
            <div class="col-lg-4 col-md-6 message d-flex flex-column justify-content-center" data-mesid="${mes.id}">
                <h2 class="w-100 row d-flex  justify-content-center align-items-center message__name ">${mes.name}</h2>
                <div class="row message__body h-100">
                    <p class="email w-100">${mes.email}</p>
                    <p class="message">${mes.message}</p>
                </div>
                <div class = "delete" data-id="${mes.id}">&times;</div>
            </div>
    `
}
export const renderAllMessage = (container, messages) => {
    const content = messages.map(mes => renderMessage(mes)).join('')
    container.insertAdjacentHTML('afterbegin', content)

}
const insertMessage = (container, messages) => {
    container.insertAdjacentHTML('afterbegin', renderMessage(messages))
}

export const addMessage = async (container, form) => {
    const add = await fetch('index/add', {
        method: 'POST',
        body: new FormData(form)
    })
    const message = await add.json()
    insertMessage(container, message)
    return true
}

export const delMessage = async (container, id) => {
    const el = container.querySelector(`[data-mesid="${id}"]`)
    const body = new FormData()
    body.append('id', id)
    const add = await fetch('index/del', {
        method: 'POST',
        body
    })
    const message = await add.json()
    el.remove()
    return true
}