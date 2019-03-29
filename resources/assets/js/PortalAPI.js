class PortalAPI {
    static version = 'v1';
    static url = '/api/' + PortalAPI.version;
    static debug = window.DEBUG;

    static list(model, callback) {
        return PortalAPI.request({
            'm': model,
            'success': (data, textStatus, jqXHR) => {
                callback(
                    {
                        'data': data,
                        'textStatus': textStatus,
                        'jqXHR': jqXHR
                    },
                    {
                        'm': model
                    }
                );
            }
        });
    }

    static view(model, id, callback) {
        return PortalAPI.request({
            'm': model,
            'id': id,
            'success': (data, textStatus, jqXHR) => {
                callback(
                    {
                        'data': data,
                        'textStatus': textStatus,
                        'jqXHR': jqXHR
                    },
                    {
                        'm': model,
                        'id': id
                    }
                );
            }
        });
    }

    static add(model, form, callback) {
        return PortalAPI.request({
            'm': model,
            'a': 'add',
            'data': form,
            'success': (data, textStatus, jqXHR) => {
                callback(
                    {
                        'data': data,
                        'textStatus': textStatus,
                        'jqXHR': jqXHR
                    },
                    {
                        'm': model,
                        'a': 'add',
                        'data': form
                    }
                );
            }
        });
    }

    static edit(model, id, form, callback) {
        return PortalAPI.request({
            'm': model,
            'a': 'edit',
            'id': id,
            'data': form,
            'success': (data, textStatus, jqXHR) => {
                callback(
                    {
                        'data': data,
                        'textStatus': textStatus,
                        'jqXHR': jqXHR
                    },
                    {
                        'm': model,
                        'a': 'edit',
                        'id': id,
                        'data': form
                    }
                );
            }
        });
    }

    static delete(model, id, callback) {
        return PortalAPI.request({
            'm': model,
            'a': 'delete',
            'id': id,
            'success': (data, textStatus, jqXHR) => {
                callback(
                    {
                        'data': data,
                        'textStatus': textStatus,
                        'jqXHR': jqXHR
                    },
                    {
                        'm': model,
                        'a': 'delete',
                        'id': id
                    }
                );
            }
        });
    }

    static form(model, callback) {
        return PortalAPI.request({
            'm': model,
            'a': 'form',
            'success': (data, textStatus, jqXHR) => {
                callback(
                    {
                        'data': data,
                        'textStatus': textStatus,
                        'jqXHR': jqXHR
                    },
                    {
                        'm': model,
                        'a': 'form'
                    }
                );
            }
        });
    }

    static request(query) {
        const settings = PortalAPI.buildAjaxSettings(query);

        if (PortalAPI.debug) console.log(settings);

        return $.ajax(settings);
    }

    static buildUrl(query) {
        if (!(query instanceof Object)) throw new TypeError('The first parameter should be a JSON Object');
        if (! query.hasOwnProperty('m')) throw new Error('No model specified.');
        let url = PortalAPI.url + '?m=' + query.m;
        if (query.hasOwnProperty('a')) url += '&a=' + query.a;
        if (query.hasOwnProperty('id')) url += '&id=' + query.id;

        return url;
    }

    static buildAjaxSettings(query) {
        const url = PortalAPI.buildUrl(query);

        let settings = {
            'type'     : (query.hasOwnProperty('type')) ? query.type : 'POST',
            'url'      : url,
            'dataType' : (query.hasOwnProperty('dataType')) ? query.dataType : 'JSON',
        };

        if (query.hasOwnProperty('data')) settings.data = query.data;
        if (query.hasOwnProperty('success')) settings.success = query.success;

        if (PortalAPI.debug) settings.error = (jqXHR, textStatus, errorThrown) => {
            const error = {'jqXHR': jqXHR, 'textStatus': textStatus, 'errorThrown': errorThrown};
            console.log(error);
            window.error = error;
        };

        return settings;
    }

    toString() {
        let output = "Portal API " + PortalAPI.version;

        if (PortalAPI.debug) output += " Debug Mode";

        return output;
    }
}

module.exports = PortalAPI;