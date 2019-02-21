class FormBuilder {
    static forms = {};
    static groupTypes = {};

    constructor(models) {
        FormBuilder.addForms(models);
        FormBuilder.getGroupTypes();
    }

    static addForms(models) {
        for (let i in models) {
            let model = models[i];
            if (Array.isArray(model)) model = model[0];
            FormBuilder.addForm(model);
        }
    }

    static addForm(response, data) {
        if (data !== undefined) {
            this.forms[data.m] = response.jqXHR.responseJSON[data.m];
        } else {
            window.PortalAPI.form(response, (response, data) => {
                FormBuilder.addForm(response, data);
            });
        }
    }

    static getGroupTypes() {
        window.PortalAPI.list('grouptype', (response, data) => {
            FormBuilder.groupTypes = response.jqXHR.responseJSON.grouptypes;
        });
    }

    static getGroupType(slug) {
        return FormBuilder.ucfirst(
            FormBuilder.groupTypes.filter(
                type => type.slug === slug
            )[0].name);
    }

    static html(model, values) {
        if (! FormBuilder.forms.hasOwnProperty(model))
            throw Error(model + ' does not have a form.');

        let form = window.clonedeep(FormBuilder.forms[model]);
        let keys = Object.keys(form);
        let html = "<div class='" + model + "-form'>";

        for (let i in keys) {
            let element = form[keys[i]];
            if (values !== undefined && values.hasOwnProperty(keys[i]))
                element.value = values[keys[i]];
            html += FormBuilder.htmlElement(model, keys[i], element);
        }

        html += "</div>";

        return html;
    }

    static htmlElement(model, key, elem) {
        let html = "";
        const type = elem.type.toLowerCase();
        const name = model + '-input-' + key;
        const class_name = model + '-input';
        const label = (elem.hasOwnProperty('label')) ? elem.label : FormBuilder.ucfirst(key);
        const value = (elem.hasOwnProperty('value') && (elem.value !== null) && (elem.value !== undefined)) ? elem.value : '';
        const placeholder = (elem.hasOwnProperty('placeholder')) ? elem.placeholder : '';
        const required = (elem.required) ? 'required' : '';
        const classes = 'input_data form-control';
        html += "<div class='input-group mb-2 " + class_name + "'>";
            html += "<div class='input-group-prepend mr-2 pt-1'>";
                html += "<label for='" + name + "'>" + label + "</label>";
            html += "</div>";


        switch(type) {
            case 'checkbox': case 'color':
            case 'date':     case 'email':
            case 'file':     case 'hidden':
            case 'image':    case 'month':
            case 'number':   case 'radio':
            case 'range':    case 'reset':
            case 'text':     case 'time':
            case 'url':      case 'week':
                html +=
                    "<input " +
                        "type='" + type + "' " +
                        "id='" + name + "' " +
                        "name='" + name + "' " +
                        "value='" + value + "' " +
                        "placeholder='" + placeholder + "' " +
                        "class='" + classes + "' " +
                        required +
                    "/>";
                break;
            case 'textarea':
                html +=
                    "<textarea " +
                        "id='" + name + "' " +
                        "name='" + name + "' " +
                        "class='" + classes + "' " +
                        required +
                    ">" +
                        value +
                    "</textarea>";
                break;
            case 'ip':
                html +=
                    "<input " +
                        "type='text' " +
                        "id='" + name + "' " +
                        "name='" + name + "' " +
                        "value='" + value + "' " +
                        "placeholder='" + placeholder + "' " +
                        "pattern='^([0-9]{1,3}\.){3}[0-9]{1,3}$' " +
                        "class='" + classes + "' " +
                        required +
                    "/>";
                break;
            case 'grouptype':
                html +=
                    "<select " +
                        "class='select2 single grouptype' " +
                        "name='" + name + "' " +
                        "id='" + name + "' " +
                        "class='" + classes + "' " +
                        required +
                    ">" +
                        "<option></option>";
                let types = FormBuilder.groupTypes;
                for (let i in types) {
                    html +=
                        "<option " +
                            "value='" + types[i].slug + "' " +
                        ">" +
                            types[i].name +
                        "</option>";
                }
                html += "</select>";
                break;
        }

        html += "</div>";

        return html;
    }

    static ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
}

module.exports = FormBuilder;