import config from './config';
import sql from './_sql.js';
import storage from './_storage';
import standard from './_standard';
import imageCookie from './_image_cookie';

let querifyObject = function(data) {
    let list = [];

    Object.keys(data).forEach(function(key) {
        list.push(`${key}=${data[key]}`);
    });

    if (!list.length) {
        return '';
    }

    return '?' + list.join('&');
}

let track = function(endpoint = '', data = {}, callback = function() {}) {
    let image = new Image,
        app_path = config.app_path.replace(new RegExp('[\/]+$'), '');

    /** add unique id */
    data.u = storage.read() || '';

    image.crossOrigin = 'use-credentials';
    image.onload = function() {
        this.u = imageCookie(this);

        sql.create(this.u, true);
        storage.create(this.u);
        standard.create(this.u);

        callback.call(this);
    };

    image.src = app_path + endpoint + querifyObject(data);
}

export function init(resolve) {
    track('', {}, function() {
        resolve(this.u);
    });
}

export function visit() {
    track('/visit', {
        referer: escape(document.referrer)
    });
}
