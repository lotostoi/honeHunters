import 'bootstrap/dist/css/bootstrap.min.css'
import '@/css/style.css'


import validFrom from "./validForm"

import { getAllMessages, renderAllMessage, addMessage, delMessage } from "./functions"

const myFrom = new validFrom({
    mainSelector: "#form",
    button: '.button'
})


const button = document.querySelector('.button')
const form = document.querySelector('#form')
const container = document.querySelector('.messages__content')





if (!container.querySelector('.message')) {
    (async () => {
        const allMessages = await getAllMessages()
        renderAllMessage(container, allMessages)
    })()
} else {
    console.log('no empty')
}

button.addEventListener('click', async (e) => {
    e.preventDefault()
    const add = await addMessage(container, form)
    if (add) {
        myFrom.clearForm()
    }
})

container.addEventListener('click', async (e) => {
    e.preventDefault()
    const id = e.target.dataset.id
    if (id) {
        await delMessage(container, id)
    }
})


