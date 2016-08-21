import config from './config';
import sql from './_sql.js';
import storage from './_storage';
import standard from './_standard';
import ajax from './_ajax.js';

export default new Promise(function(resolve, reject) {
    sql.read(function(sql_cookie) {
        const standard_cookie = standard.read(),
            storage_cookie = storage.read(),
            cookie_value = sql_cookie || storage_cookie || standard_cookie,
            fetch_link = config.app_path + 'unique';

        if (!cookie_value) {
            ajax(fetch_link, function(value) {
                sql.create(value);
                storage.create(value);
                standard.create(value);

                resolve(value);
            });

            return;
        }

        if (!sql_cookie) {
            if (storage_cookie) {
                sql.create(storage_cookie);
                standard.create(storage_cookie);

                sql_cookie = storage_cookie;
            } else if (standard_cookie) {
                sql.create(standard_cookie);
                storage.create(standard_cookie);

                sql_cookie = standard_cookie;
            }
        }

        if (!storage_cookie || storage_cookie != sql_cookie) {
            storage.create(sql_cookie);
        }

        /** update it each time */
        standard.create(sql_cookie);

        resolve(sql_cookie);
    });
});
