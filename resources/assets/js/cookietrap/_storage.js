import config from './config';

export default {
    hasSupport: function() {
        if ('undefined' == typeof(Storage)) {
            // console.warn('Storage is not supported');
            return false;
        }

        return true;
    },

    create: function(value) {
        if (!this.hasSupport()) {
            return false;
        }

        localStorage.setItem(config.cookie_name, value);
    },

    read: function() {
        if (!this.hasSupport()) {
            return false;
        }

        const value = localStorage.getItem(config.cookie_name);

        if (value) {
            return value;
        }

        return false;
    }
};
