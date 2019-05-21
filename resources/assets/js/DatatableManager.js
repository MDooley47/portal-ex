import Setup from "./Setup.js";

export default class DatatableManager {
    constructor(tables = null, selections = {}) {
        this.tables = {};
        this.selections = selections;
        new window.FormBuilder(tables);
        this.addTables(tables);
        // this.updateButtons();
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

            window.PortalAPI.list(name, (response, request) => {
               if (window.DEBUG) console.log({'response': response, 'request': request});
               // $('#' + name + '-list table tbody').html(DatatableManager.buildTable(name, response.jqXHR.responseJSON));
               this.addTable(name, sort, slug, direction);
            });
        }
    }

    addTable(table, sort = 1, slug = 0, direction = 'asc') {
        let dt = this;
        // if ( $.fn.dataTable.isDataTable( '#' + table + '-list table' ) ) {
        //   this.tables[table] = $('#' + table + '-list table').DataTable();
        //   this.tables[table].order([[sort, direction]]);
        // }
        // else {
          this.tables[table] = $('#' + table + '-list table').DataTable({
              "order": [[sort, direction]],
              "ajax": {
                "url": "/api/v1?m=app",
                "dataSrc": "apps"
              },
              "columns": [
                { "data": "slug" },
                {
                  "data": "iconPath",
                  "render": function( data, type, row, meta ) {
                    return '<img src="' + data + '" alt="" class="app-list-icon" />';
                  }
                },
                { "data": "name" },
                { "data": "url" }
              ]
          });

          // $('#' + table + '-list table tbody').on('click', 'tr', function() {
          //     let data = dt.tables[table].row(this).data();
          //     dt.toggleSelection(table, data[slug]);
          // });

          $('#' + table + '-list table tbody').on( 'click', 'tr', function () {
              let buttons = {
                  // 'select': $('#' + table + '-select'),
                  'edit': $('#' + table + '-edit'),
                  'delete': $('#' + table + '-delete'),
                  'info': $('#' + table + '-info'),
              };
              let disabled_class = 'disabled';


              if ( $(this).hasClass('selected') ) {
                  $(this).removeClass('selected');
                  buttons.info.addClass(disabled_class);
                  buttons.info.attr('disabled', 'disabled');
                  buttons.edit.addClass(disabled_class);
                  buttons.edit.attr('disabled', 'disabled');
                  buttons.delete.addClass(disabled_class);
                  buttons.delete.attr('disabled', 'disabled');
              }
              else {
                  dt.tables[table].$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
                  buttons.info.removeClass(disabled_class);
                  buttons.info.removeAttr('disabled');
                  buttons.edit.removeClass(disabled_class);
                  buttons.edit.removeAttr('disabled');
                  buttons.delete.removeClass(disabled_class);
                  buttons.delete.removeAttr('disabled');
              }

          } );


          this.addAddButton(table);
          this.addEditButton(table);
          this.addInfoButton(table);
          // this.addSelectButton(table, dt);
          this.addDeleteButton(table);

          // this.selections[table] = [];
        // }
    }

    addAddButton(table) {
        $('button#' + table + '-add').on('click', (e) => {
            const title = DatatableManager.titleCase('add ' + table);
            const message = window.FormBuilder.html(table);
            var newIcon = false;

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

                            const inputs = $("." + table + "-input .input_data").toArray();

                            let valid = true;

                            for (let i in inputs) {
                                const elem = $(inputs[i]);
                                let key = elem.attr('id').replace(table + '-input-', '');
                                data[key] = elem.val();

                                if (table == 'app' && key == 'icon')
                                {
                                  var iconFile = document.getElementById('app-input-icon').files[0];
                                  if (iconFile == undefined)
                                  {
                                    valid = false;
                                    elem.addClass('is-invalid');
                                  }
                                  else
                                  {
                                    var iconFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
                                    if (! iconFilter.test(iconFile.type))
                                    {
                                      valid = false;
                                      alert('The icon file is not a supported image type');
                                      elem.addClass('is-invalid');
                                    }
                                    else if (iconFile.size > 1048576) // 1MB
                                    {
                                      valid = false;
                                      alert('The icon file is larger than the allowed size of 1MB');
                                      elem.addClass('is-invalid');
                                    }
                                    else
                                    {
                                      newIcon = true;
                                    }
                                  }
                                } else if (! elem[0].checkValidity()) {
                                    valid = false;
                                    elem.addClass('is-invalid');
                                } else if (elem.hasClass('is-invalid')) {
                                    elem.removeClass('is-invalid');
                                }
                            }

                            if (valid) {
                              if (newIcon)
                              {
                                // read the icon file and put it in the dataset before we make the API call
                                var iconReader = new FileReader();
                                iconReader.readAsDataURL(iconFile);
                                iconReader.onload = function ()
                                {
                                  data['icon'] = iconReader.result;
                                  window.PortalAPI.add(table, data, (response, data) => {
                                      if (window.DEBUG === true) {
                                          console.log({
                                              'response': response,
                                              'data': data
                                          });
                                      }
                                      // refresh data in list
                                      window.DM.tables[table].ajax.reload(null, false);
                                  });
                                };
                              }
                              else
                              {
                                window.PortalAPI.add(table, data, (response, data) => {
                                    if (window.DEBUG === true) {
                                        console.log({
                                            'response': response,
                                            'data': data
                                        });
                                    }
                                    // refresh data in list
                                    window.DM.tables[table].ajax.reload(null, false);
                                });
                              }
                            } else return false;
                        }
                    }
                }
            }).find(".modal-dialog").addClass("modal-dialog-centered");

            if (message.includes('select2')) Setup.setupSelect2();
        });
    }

    addEditButton(table) {
        $('button#' + table + '-edit').on('click', (e) => {
            const slug = window.DM.tables[table].row('.selected').data().slug;

            if (typeof(slug) != "undefined")
            {
              window.PortalAPI.view(table, slug, (response, request) => {
                DatatableManager.displayEdit(table, response.data, request.id);
                if (window.DEBUG) console.log({'response': response, 'request': request});
              });
            }
        });
    }

    addInfoButton(table) {
        $('button#' + table + '-info').on('click', (e) => {
            const slug = window.DM.tables[table].row('.selected').data().slug;

            if (typeof(slug) != "undefined")
            {
              window.PortalAPI.view(table, slug, (response, request) => {
                DatatableManager.displayInfo(table, response.data);
                if (window.DEBUG) console.log({'response': response, 'request': request});
              });
            }
        });
    }

    addDeleteButton(table) {
        $('button#' + table + '-delete').on('click', (e) => {
          if (typeof(this.tables[table].row('.selected').data().slug) != "undefined")
          {
            const title = DatatableManager.titleCase(table + ' deletion');
            let message = "Are you sure you wish to delete the following " + window.pluralize(table) + "?";
            let list = "<ul>";
            // for (let i in this.selections[table]) {
            //     let slug = this.selections[table][i];
            //     let name = $('#' + table + '-' + slug + ' .' + table + '-name').text();
            //     list += "<li>";
            //     list += "<strong>" + slug + "</strong> ";
            //     list += name;
            //     list += "</li>";
            // }
            list += "<li>(" + this.tables[table].row('.selected').data().slug +
              ") " + this.tables[table].row('.selected').data().name + "</li>";
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
                        // for (let i in window.DM.selections[table]) {
                            let slug = window.DM.tables[table].row('.selected').data().slug;

                            if (typeof(slug) != "undefined")
                            {
                              window.PortalAPI.delete(table, slug, (response, data) => {
                                window.DM.tables[table].row('.selected').remove().draw(false);
                                if (window.DEBUG === true) {
                                    console.log({
                                        'data': data,
                                        'response': response
                                    });
                                }
                              });
                            }
                        // }
                    }
                }
            }).find(".modal-dialog").addClass("modal-dialog-centered");
          }
        });
    }

    // addSelectButton(table, dt) {
    //     $('button#' + table + '-select').on('click', (e) => {
    //         let button = $(e.target);
    //         if (button.hasClass('disabled')) return false;
    //         else if (button.text().toString().toLowerCase() === 'select all') {
    //             dt.selectAll(table);
    //             button.text('Deselect All');
    //         } else {
    //             dt.deselectAll(table);
    //             button.text('Select All');
    //         }
    //     });
    // }

    // addSelection(table, id) {
    //     this.selections[table].push(id);
    //     this.renderSelections(table);
    // }
    //
    // removeSelection(table, id) {
    //     this.selections[table] = this.selections[table].filter(elem => elem !== id);
    //     this.renderSelections(table);
    // }
    //
    // toggleSelection(table, id) {
    //     if (this.isSelected(table, id)) this.removeSelection(table, id);
    //     else this.addSelection(table, id);
    // }
    //
    // selectAll(table) {
    //     let rows = $("tr." + table + "-row").toArray();
    //     for(let i = 0; i < rows.length; ++i) {
    //         this.addSelection(table, $(rows[i]).attr('id').replace(table + '-', ''));
    //     }
    // }
    //
    // deselectAll(table) {
    //     this.removeAllSelectionsFrom(table);
    // }
    //
    // isSelected(table, id) {
    //     return this.selections[table].includes(id);
    // }
    //
    // getSelections(table) {
    //     return this.selections[table];
    // }

    // renderSelections(table = null) {
    //     if (table == null) {
    //         for (let key in this.selections) this.renderSelections(key);
    //     } else {
    //         $("tr." + table + "-row.selected-row").removeClass('selected-row');
    //
    //         for (let id in this.selections[table]) {
    //             $('#' + table + '-' + this.selections[table][id]).addClass('selected-row');
    //         }
    //     }
    //
    //     this.updateButtons(table);
    // }

    // updateButtons(table = null) {
    //     let disabled_class = 'disabled';
    //
    //     if (table == null) {
    //         for (let key in this.selections) this.updateButtons(key);
    //     } else {
    //         let rows = $('.' + table + '-row').toArray();
    //
    //         let buttons = {
    //             // 'select': $('#' + table + '-select'),
    //             'edit': $('#' + table + '-edit'),
    //             'delete': $('#' + table + '-delete'),
    //             'info': $('#' + table + '-info'),
    //         };
    //
    //         // if (rows === undefined || rows.length === 0) {
    //         //     buttons.select.addClass(disabled_class);
    //         //     buttons.select.attr('disabled', 'disabled');
    //         // } else if (buttons.select.hasClass(disabled_class)) {
    //         //     buttons.select.removeClass(disabled_class);
    //         //     buttons.select.removeAttr('disabled');
    //         // }
    //
    //         if (this.selections[table] === undefined || this.selections[table].length === 0) {
    //             buttons.info.addClass(disabled_class);
    //             buttons.info.attr('disabled', 'disabled');
    //             buttons.edit.addClass(disabled_class);
    //             buttons.edit.attr('disabled', 'disabled');
    //             buttons.delete.addClass(disabled_class);
    //             buttons.delete.attr('disabled', 'disabled');
    //         } else {
    //             if (this.selections[table].length === 1) {
    //                 buttons.info.removeClass(disabled_class);
    //                 buttons.info.removeAttr('disabled');
    //                 buttons.edit.removeClass(disabled_class);
    //                 buttons.edit.removeAttr('disabled');
    //             } else if (this.selections[table].length > 1) {
    //                 buttons.info.addClass(disabled_class);
    //                 buttons.info.attr('disabled', 'disabled');
    //                 buttons.edit.addClass(disabled_class);
    //                 buttons.edit.attr('disabled', 'disabled');
    //             }
    //
    //             if (buttons.delete.hasClass(disabled_class)) {
    //                 buttons.delete.removeClass(disabled_class);
    //                 buttons.delete.removeAttr('disabled', 'disabled');
    //             }
    //         }
    //     }
    // }

    // removeAllSelectionsFrom(table) {
    //     this.selections[table] = [];
    //     this.renderSelections(table);
    // }
    //
    // reset() {
    //     for (let table in this.selections) this.selections[table] = [];
    // }

    static titleCase(str) {
        if ((str===null) || (str===''))
            return false;
        else
            str = str.toString();

        return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    }

    // static getRowValues(table, slug) {
    //     const row = $('#' + table.toLowerCase() + '-' + slug);
    //     const cells = row.children().toArray();
    //     let data = {};
    //
    //     for (let i in cells) {
    //         const key = $(cells[i]).attr('class').split(' ')[0].replace(table + '-', '');
    //         data[key] = $(cells[i]).text();
    //     }
    //
    //     return data;
    // }

    // static buildTable(name, data) {
    //     let html = "";
    //     const table = name.toLowerCase();
    //     name = window.pluralize(name);
    //     let keys = Object.keys(data[name]);
    //     let order;
    //
    //     for (let i in  keys) {
    //         switch (table) {
    //             case 'group':
    //                 order = ['slug', 'groupType', 'name', 'description'];
    //                 break;
    //             case 'app':
    //                 delete data[name][keys[i]].version;
    //                 data[name][keys[i]].icon = data[name][keys[i]].iconPath;
    //                 delete data[name][keys[i]].iconPath;
    //                 order = ['slug', 'icon', 'name', 'url'];
    //                 break;
    //         }
    //
    //         html += DatatableManager.buildTableRow(table, data[name][keys[i]], order);
    //     }
    //     return html;
    // }
    //
    // static buildTableRow(table, data, order) {
    //     let html = "";
    //
    //     html += "<tr " +
    //         "id='" + table + "-" + data.slug + "' " +
    //         "class='" + table + "-row'>";
    //     if (order === undefined) {
    //         const keys = Object.keys(data);
    //
    //         for (let i in keys) {
    //             const value = (data[keys[i]] !== null && data[keys[i]] !== undefined) ? data[keys[i]] : '';
    //
    //             html +=
    //                 "<td class='" + table + "-" + keys[i] + "'>" +
    //                 value +
    //                 "</td>";
    //         }
    //     } else {
    //         for(let i in order) {
    //             const value = (data[order[i]] !== null && data[order[i]] !== undefined) ? data[order[i]] : '';
    //             html +=
    //                 "<td class='" + table + "-" + order[i] + "'>";
    //             switch(order[i].toLowerCase()) {
    //                 case 'grouptype':
    //                     html +=
    //                         "<span class='grouptype-slug-" + data.groupType + "'>" +
    //                         FormBuilder.getGroupType(data.groupType) +
    //                         "</span>";
    //                     break;
    //                 case 'icon':
    //                     html += "<img src='" + value + "' alt='' class='app-list-icon' />";
    //                     break;
    //                 default:
    //                     html += value;
    //             }
    //             html += "</td>";
    //         }
    //     }
    //
    //     html += "</tr>";
    //
    //     return html;
    // }

    static displayEdit(table, data, slug) {
        const title = DatatableManager.titleCase('edit ' + table);
        const message = window.FormBuilder.html(table, data[table]);
        var newIcon = false;

        bootbox.dialog({
            'title': title,
            'message': message,
            buttons: {
                'cancel': {
                    'label': 'Cancel',
                    'className': 'btn-danger'
                },
                'edit': {
                    'label': 'Info',
                    'className': 'btn-info',
                    'callback': () => {
                        $("button#" + table + "-info").click();
                    }
                },
                'ok': {
                    'label': title,
                    'className': 'btn-success',
                    'callback': () => {
                        let data = {};

                        const inputs = $("." + table + "-input .input_data").toArray();

                        let valid = true;

                        for (let i in inputs) {
                            const elem = $(inputs[i]);
                            const key = elem.attr('id').replace(table + '-input-', '');
                            data[key] = elem.val();

                            if (table == 'app' && key == 'icon')
                            {
                              var iconFile = document.getElementById('app-input-icon').files[0];
                              if (iconFile == undefined)
                              {
                                // on edit, it's OK for there to be no icon file attached
                                // if there isn't one, we keep the current one
                                if (elem.hasClass('is-invalid'))
                                {
                                    elem.removeClass('is-invalid');
                                }
                              }
                              else
                              {
                                var iconFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
                                if (! iconFilter.test(iconFile.type))
                                {
                                  valid = false;
                                  alert('The icon file is not a supported image type');
                                  elem.addClass('is-invalid');
                                }
                                else if (iconFile.size > 1048576) // 1MB
                                {
                                  valid = false;
                                  alert('The icon file is larger than the allowed size of 1MB');
                                  elem.addClass('is-invalid');
                                }
                                else
                                {
                                  newIcon = true;
                                }
                              }
                            } else if (! elem[0].checkValidity()) {
                                valid = false;

                                elem.addClass('is-invalid');
                            } else if (elem.hasClass('is-invalid')) {
                                elem.removeClass('is-invalid');
                            }
                        }

                        if (valid) {
                          if (newIcon)
                          {
                            // read the icon file and put it in the dataset before we make the API call
                            var iconReader = new FileReader();
                            iconReader.readAsDataURL(iconFile);
                            iconReader.onload = function ()
                            {
                              data['icon'] = iconReader.result;
                              window.PortalAPI.edit(table, slug, data, (response, data) => {
                                  if (window.DEBUG === true) {
                                      console.log({
                                          'response': response,
                                          'data': data
                                      });
                                  }
                                  // refresh data in list
                                  window.DM.tables[table].ajax.reload(null, false);
                              });
                            };
                          }
                          else
                          {
                            window.PortalAPI.edit(table, slug, data, (response, data) => {
                                if (window.DEBUG === true) {
                                    console.log({
                                        'response': response,
                                        'data': data
                                    });
                                }
                                // refresh data in list
                                window.DM.tables[table].ajax.reload(null, false);
                            });
                          }
                        } else return false;
                    }
                }
            }
        }).find(".modal-dialog").addClass("modal-dialog-centered");

        if (message.includes('select2')) Setup.setupSelect2();

    }

    static displayInfo(model, data) {
        const title = DatatableManager.titleCase( model + ' information');
        const message = DatatableManager.buildInfo(model, data[model]);

        window.bootbox.dialog({
            'title': title,
            'message': message,
            'buttons': {
                'cancel': {
                    'label': 'Cancel',
                    'className': 'btn-danger'
                },
                'edit': {
                    'label': 'Edit',
                    'className': 'btn-warning',
                    'callback': () => {
                        $("button#" + model + "-edit").click();
                    }
                },
                'ok': {
                    'label': 'Okay',
                    'className': 'btn-success',
                }
            }
        }).find(".modal-dialog").addClass("modal-dialog-centered");
    }

    static buildInfo(model, data) {
        const keys = Object.keys(data);
        let html = "";
        if (data.hasOwnProperty('name')) html += "<span class='h4'>" + data.name + "</span>";
        html +=
            "<table class='" + model + "-table table'>" +
                "<tbody>";

        for (let i in keys) {
            html += DatatableManager.buildInfoElement(model, keys[i], data[keys[i]]);
        }

        html += "</tbody>" +
            "</table>";

        return html;
    }

    static buildInfoElement(model, key, elem) {
        let html = "";
        key = window.FormBuilder.ucfirst(key.toLowerCase());
        if (elem === null || elem === undefined) elem = '';
        html +=
            "<tr>" +
                "<th scope='row'>" +
                    key +
                "</th>" +
                "<td>" +
                    elem +
                "</td>" +
            "</tr>";

        return html;
    }
}
