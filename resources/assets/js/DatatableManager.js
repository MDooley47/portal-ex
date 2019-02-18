import Setup from "./Setup.js";

export default class DatatableManager {
    constructor(tables = null, selections = {}) {
        this.tables = {};
        this.selections = selections;
        new window.FormBuilder(tables);
        this.addTables(tables);
        this.updateButtons();
    }

    addTables(tables) {
        for (let i = 0; i < tables.length; ++i) {
            let name = tables[i];
            let sort = undefined;
            let slug = undefined;
            let direction = undefined;

            if (Array.isArray(tables[i])) {
                name = tables[i][0];
                sort = (tables[i].length >= 2) ? tables[i][1] : undefined;
                slug = (tables[i].length >= 3) ? tables[i][2] : undefined;
                direction = (tables[i].length >= 4) ? tables[i][3] : undefined;
            }

            this.addTable(name, sort, slug, direction);
        }
    }

    addTable(table, sort = 1, slug = 0, direction = 'asc') {
        let dt = this;
        this.tables[table] = $('#' + table + '-list table').DataTable({
            "order": [[sort, direction]],
        });

        $('#' + table + '-list table tbody').on('click', 'tr', function() {
            let data = dt.tables[table].row(this).data();
            dt.toggleSelection(table, data[slug]);
        });

        this.addAddButton(table);
        this.addSelectButton(table, dt);
        this.addDeleteButton(table);

        this.selections[table] = [];
    }

    addAddButton(table) {
        $('button#' + table + '-add').on('click', (e) => {
            let title = DatatableManager.titleCase('add ' + table);
            let message = window.FormBuilder.html(table);

            bootbox.dialog({
                'title': title,
                'message': message,
                buttons: {
                    'cancel': {
                        'label': 'Cancel',
                        'className': 'btn-danger'
                    },
                    'ok': {
                        'label': title,
                        'className': 'btn-success',
                        'callback': () => {
                            let data = {};

                            let inputs = $("." + table + "-input .input_data").toArray();

                            for (let i in inputs) {
                                let key = $(inputs[i]).attr('id').replace(table + '-input-', '');
                                data[key] = $(inputs[i]).val();
                            }

                            console.log(data);
                        }
                    }
                }
            }).find(".modal-dialog").addClass("modal-dialog-centered");

            if (message.includes('select2')) Setup.setupSelect2();
        });
    }

    addDeleteButton(table) {
        $('button#' + table + '-delete').on('click', (e) => {
            let title = DatatableManager.titleCase(table + ' deletion');
            let message = "Are you sure you wish to delete the following " + window.pluralize(table) + "?";
            let list = "<ul>";
            for (let i in this.selections[table]) {
                let slug = this.selections[table][i];
                let name = $('#' + table + '-' + slug + ' .' + table + '-name').text();
                list += "<li>";
                list += "<strong>" + slug + "</strong> ";
                list += name;
                list += "</li>";
            }
            list += "</ul>";

            message += list;

            bootbox.confirm({
                title: title,
                message: message,
                buttons: {
                    confirm: {
                        label: 'Delete',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-secondary'
                    }
                },
                callback: function (result) {
                    if (result) {
                        for (let i in window.DM.selections[table]) {
                            let slug = window.DM.selections[table][i];
                            window.PortalAPI.delete(table, slug, (response, data) => {
                                if (window.DEBUG === true) {
                                    console.log({
                                        'data': data,
                                        'response': response
                                    });
                                }
                            });
                        }
                    }
                }
            }).find(".modal-dialog").addClass("modal-dialog-centered");
        });
    }

    addSelectButton(table, dt) {
        $('button#' + table + '-select').on('click', (e) => {
            let button = $(e.target);
            if (button.hasClass('disabled')) return false;
            else if (button.text().toString().toLowerCase() === 'select all') {
                dt.selectAll(table);
                button.text('Deselect All');
            } else {
                dt.deselectAll(table);
                button.text('Select All');
            }
        });
    }

    addSelection(table, id) {
        this.selections[table].push(id);
        this.renderSelections(table);
    }

    removeSelection(table, id) {
        this.selections[table] = this.selections[table].filter(elem => elem !== id);
        this.renderSelections(table);
    }

    toggleSelection(table, id) {
        if (this.isSelected(table, id)) this.removeSelection(table, id);
        else this.addSelection(table, id);
    }

    selectAll(table) {
        let rows = $("tr." + table + "-row").toArray();
        for(let i = 0; i < rows.length; ++i) {
            this.addSelection(table, $(rows[i]).attr('id').replace(table + '-', ''));
        }
    }

    deselectAll(table) {
        this.removeAllSelectionsFrom(table);
    }

    isSelected(table, id) {
        return this.selections[table].includes(id);
    }

    getSelections(table) {
        return this.selections[table];
    }

    renderSelections(table = null) {
        if (table == null) {
            for (let key in this.selections) this.renderSelections(key);
        } else {
            $("tr." + table + "-row.selected-row").removeClass('selected-row');

            for (let id in this.selections[table]) {
                $('#' + table + '-' + this.selections[table][id]).addClass('selected-row');
            }
        }

        this.updateButtons(table);
    }

    updateButtons(table = null) {
        let disabled_class = 'disabled';

        if (table == null) {
            for (let key in this.selections) this.updateButtons(key);
        } else {
            let rows = $('.' + table + '-row').toArray();

            let buttons = {
                'select': $('#' + table + '-select'),
                'edit': $('#' + table + '-edit'),
                'delete': $('#' + table + '-delete'),
                'info': $('#' + table + '-info'),
            };

            if (rows === undefined || rows.length === 0) {
                buttons.select.addClass(disabled_class);
            } else if (buttons.select.hasClass(disabled_class)) {
                buttons.select.removeClass(disabled_class);
            }

            if (this.selections[table] === undefined || this.selections[table].length === 0) {
                buttons.info.addClass(disabled_class);
                buttons.edit.addClass(disabled_class);
                buttons.delete.addClass(disabled_class);
            } else {
                if (this.selections[table].length === 1) {
                    buttons.info.removeClass(disabled_class);
                    buttons.edit.removeClass(disabled_class);
                } else if (this.selections[table].length > 1) {
                    buttons.info.addClass(disabled_class);
                    buttons.edit.addClass(disabled_class);
                }

                if (buttons.delete.hasClass(disabled_class)) {
                    buttons.delete.removeClass(disabled_class);
                }
            }
        }
    }

    removeAllSelectionsFrom(table) {
        this.selections[table] = [];
        this.renderSelections(table);
    }

    reset() {
        for (let table in this.selections) this.selections[table] = [];
    }

    static titleCase(str) {
        if ((str===null) || (str===''))
            return false;
        else
            str = str.toString();

        return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    }

    static getRowValues(table, slug) {
        const row = $('#' + table.toLowerCase() + '-' + slug);
        const cells = row.children().toArray();
        let data = {};

        for (let i in cells) {
            const key = $(cells[i]).attr('class').split(' ')[0].replace(table + '-', '');
            data[key] = $(cells[i]).text();
        }

        return data;
    }
}