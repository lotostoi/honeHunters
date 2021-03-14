const REG_EXPS = {
    'name': ['^[а-яА-ЯёЁA-z ]{2,}$', 'i'],
    'phone': ['^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$'],
    'email': ['^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$'],
    'message': ['[?!,.а-яА-ЯёЁA-z1-9 ]{2,}', 'i']
}

export default class ValidForm {
    constructor(options) {
        this.makeAfterClick = options.makeAfterClick
        this.startValid = false
        this.cb = options.cb
        this.form = document.querySelector(options.mainSelector) || null
        this.fieldsForValid = options.inputTypeForValid ?? ['name', 'email', 'message']
        this.button = typeof options.button === 'string' ? this.form.querySelector(options.button) : options.button
        this.regExps = options.regExps ? { ...REG_EXPS, ...options.regExps } : REG_EXPS
        this._init()
    }
    _init() {
        this._getFieldsForValid()
        this._validForm()
        this._setHandlers()
        this.buttonOnLoad()
    }

    _getFieldsForValid() {
        let { form, fieldsForValid } = this
        this.fieldsForValid = fieldsForValid
            .map(fieldName => {
                const el = form.querySelector(`[name="${fieldName}"]`)
                const name = el.name
                const regExp = new RegExp(...this.regExps[name])
                const elError = form.querySelector(`[data-name="${fieldName}"]`)
                let result = false
                return { el, name, regExp, elError, result }
            })
            .filter(input => typeof input !== 'string')
        return fieldsForValid
    }

    set valid(value) {
        this._valid = value
       // value ? this.buttonUnBlock() : this.buttonBlock()
        return true
    }
    get valid() {
        return this._valid
    }

    buttonBlock() {
        this.button.setAttribute("disabled", "disabled")
        this.button.classList.add('block')
    }
    buttonUnBlock() {
        this.button.removeAttribute("disabled")
        this.button.classList.remove('block')
    }
    buttonLoad() {
        this.buttonBlock()
        this.button.querySelector('span').classList.add('block')
        this.button.querySelector('div').classList.remove('block')
    }
    buttonOnLoad() {
        this.buttonUnBlock()
        this.button.querySelector('span').classList.remove('block')
        this.button.querySelector('div').classList.add('block')
    }
    clearForm() {
        this.form.reset()
        this.fieldsForValid.forEach(f => f.result = false)
        this.startValid = false
        this._validForm()
    }
    showResult() {
        const el = $.el(`[data-result="saccess"]`, this.form)
        el.classList.remove('block')
        setTimeout(() => { el.classList.add('block') }, 4000)
    }
    showError() {
        const el = $.el(`[data-result="error"]`, this.form)
        el.classList.remove('block')
        setTimeout(() => { el.classList.add('block') }, 4000)
    }
    _validField(name = null, value = null) {
        console.log(11)
        this.valid = false
        if (!name) return
        const field = this.fieldsForValid.find(el => el.name === name)
        if (field) {
            field.result = field.regExp.test(value) ? true : false
            if (!field.result) {
                field.el.classList.add('error')
                field.el.classList.remove('success')
                if (field.elError) {
                    field.elError.classList.remove('block')
                }

            } else {
                field.el.classList.remove('error')
                field.el.classList.add('success')
                if (field.elError) {
                    field.elError.classList.add('block')
                }
            }
        }
        this.valid = this.fieldsForValid.some(field => !field.result) ? false : true
    }

    _validForm() {
        if (!this.startValid) return
        this.fieldsForValid.forEach(f => this._validField(f.el.name, f.el.value.trim()))
    }

    _setHandlers() {
        this.form.addEventListener('input', (e) => {
            e.preventDefault()
            const el = e.target
            if (this.fieldsForValid.find(field => field.el === el)) {
                this._validForm(el.name, el.value.trim())
            }
        })

        this.button.addEventListener('click', async (e) => {
            e.preventDefault()
            this.startValid = true
            this._validForm()
            if (!this.valid) return
            this.buttonLoad()
            if (this.makeAfterClick) {
                await this.makeAfterClick.cb(... this.makeAfterClick.arg)
            }
            this.buttonOnLoad()
            this.clearForm()
        })
    }
}



