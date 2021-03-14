export default class {
    constructor(options) {
        this.container = options.container
        this.content = this.container.querySelector('.content')
        this.delay = options.delay || 3000
    }

    showMessage(status, message, delay = null) {
        if (status) {
            this.content.innerHTML = message
            this.container.classList.add('success')
            setTimeout(() => this.container.classList.remove('success'), delay || this.delay)

        } else {
            this.content.innerHTML = message
            this.container.classList.add('error')
            setTimeout(() => this.container.classList.remove('error'), delay || this.delay)
        }
    }

}