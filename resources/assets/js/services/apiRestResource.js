export default class ApiRestResource {

    /**
     * ApiRestResource constructor.
     *
     * @returns {void}
     */
    construct() {
        this._token = null;
    }

    /**
     * Set the api token.
     *
     * @param token
     * @returns {void}
     */
    setToken(token) {
        this._token = token;
    }

    /**
     * Return the api token.
     *
     * @returns {*|null}
     */
    getToken() {
        if (this._token === null || this._token === undefined) {
            this.setToken(this.getCookie('api-token'))
        }

        return this._token;
    }

    /**
     * Forget the api token.
     *
     * @returns {void}
     */
    forgetToken() {
        this._token = null;
    }

    /**
     * Get the headers to include with the request.
     *
     * @returns {object}
     */
    getHeaders() {
        return {
            headers: {
                'api-token': this.getToken(),
            }
        }
    }

    /**
     * Carry out a GET request for the given url.
     *
     * @param url
     * @returns {object}
     */
    getUrl(url) {
        return axios.get(url, this.getHeaders());
    }

    /**
     * Return the value for the given cookie key.
     *
     * @param name
     * @returns {string|null}
     */
    getCookie(name) {
        // Init the return as null in the instance no cookie is found
        let returnValue = null;

        document.cookie.split('; ').forEach(function (cookie) {
            let arr = cookie.split('=');

            // For each cookie see if the key matches the given name
            if (arr[0] !== undefined && arr[0] === name) {
                returnValue = arr[1] !== undefined ? arr[1] : null;
            }
        });

        return returnValue;
    }
}
