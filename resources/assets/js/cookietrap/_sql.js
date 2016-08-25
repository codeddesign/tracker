import config from './config';

export default {
    connection: null,

    db: {
        name: function() {
            return config.cookie_name + '_db'
        },

        size: 2 * 1024 * 1024,

        table_name: 'vuer',

        table_field: 'vid'
    },

    hasSupport: function() {
        if ('undefined' == typeof(openDatabase)) {
            // console.warn('Database is not supported');
            return false;
        }

        this.connection = openDatabase(this.db.name(), '1.0', '', this.db.size);

        this.connection.transaction(function(tn) {
            tn.executeSql(`CREATE TABLE IF NOT EXISTS ${this.db.table_name} ('${this.db.table_field}')`);
        }.bind(this));

        return true;
    },

    create: function(value, mustClear = false) {
        if (!this.hasSupport()) {
            return false;
        }

        this.read(function(cookie_value) {
            if (cookie_value && !mustClear) {
                return false;
            }

            this.connection.transaction(function(tn) {
                if (mustClear) {
                    tn.executeSql(`delete from ${this.db.table_name}`);
                }

                tn.executeSql(`INSERT INTO ${this.db.table_name} ('${this.db.table_field}') VALUES ('${value}')`);
            }.bind(this));
        }.bind(this));
    },

    read: function(cb) {
        if (!this.hasSupport()) {
            return false;
        }

        this.connection.transaction(function(tn) {
            tn.executeSql(`SELECT ${this.db.table_field} from ${this.db.table_name}`, [], function(tn, results) {
                if (results.rows.length) {
                    cb(results.rows[0][this.db.table_field]);

                    return false;
                }

                cb(false);
            }.bind(this));
        }.bind(this));
    }
};
