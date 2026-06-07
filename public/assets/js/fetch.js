window.appFetch = async function (url, options) {
    var response = await fetch(url, options || {});

    if (!response.ok) {
        throw new Error('Request failed with status ' + response.status);
    }

    return response;
};
