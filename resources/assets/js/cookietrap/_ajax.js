let request = (function() {
    if (typeof XMLHttpRequest === 'undefined') {
        XMLHttpRequest = function() {
            const list = ['Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.3.0', 'Microsoft.XMLHTTP'];
            list.forEach(function(active_name) {
                try {
                    return new window.ActiveXObject(active_name);
                } catch (e) {}

                throw new Error('No support for XMLHttpRequest');
            });
        };
    }

    return new XMLHttpRequest;
})();

export default function(link, callback) {
    callback = callback || function() {};

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            callback(request.responseText);
        }
    };

    request.open('GET', link)
    request.send();
};
