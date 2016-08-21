import config from './config';
import standard from './_standard';

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

export function track(endpoint, data = {}) {
    let image = new Image;

    /** add unique id */
    data.u = standard.read();

    image.src = config.app_path + endpoint + querifyObject(data);
}

export function visit() {
    let data = {
        referer: escape(document.referrer)
    };

    track('visit', data);
}
