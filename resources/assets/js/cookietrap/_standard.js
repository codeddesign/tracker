import config from './config';

export default {
    create: function(value) {
        const date = new Date();

        date.setTime(date.getTime() + (config.cookie_days * 24 * 60 * 60 * 1000));

        document.cookie = config.cookie_name + "=" + value + '; expires=' + date.toGMTString() + "; path=/";
    },

    read: function() {
        const nameEQ = config.cookie_name + "=",
            ca = document.cookie.split(';');

        let c, i;

        for (i = 0; i < ca.length; i++) {
            c = ca[i];

            while (c.charAt(0) == ' ') {
                c = c.substring(1, c.length);
            }

            if (c.indexOf(nameEQ) == 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }

        return false;
    }
};
