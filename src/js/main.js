import 'bootstrap/dist/css/bootstrap.min.css'
import '@/css/style.css'


import ValidFrom from "./ValidForm"
import {
    getAllMessages,
    renderAllMessage,
    addMessage,
    delMessage
} from "./functions"



const container = document.querySelector('.messages__content')
const from = document.querySelector('#form')


const myFrom = new ValidFrom({
    mainSelector: "#form",
    button: '.button',
    makeAfterClick: {
        cb: addMessage,
        arg: [container, from]
    }
})


if (!container.querySelector('.message')) {
    (async () => {
        const allMessages = await getAllMessages()
        renderAllMessage(container, allMessages)
    })()
}

container.addEventListener('click', async (e) => {
    e.preventDefault()
    const id = e.target.dataset.id
    if (id) {
        await delMessage(container, id)
    }
})


