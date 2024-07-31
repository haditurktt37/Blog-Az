
function deleteAllCookies() {
    // Get all cookies as a string
    const cookies = document.cookie.split(";");

    // Loop through the cookies
    for (let i = 0; i < cookies.length; i++) {
        // Get the cookie name
        const cookie = cookies[i];
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;

        // Delete the cookie by setting its expiration date to the past
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
}



function redi(url)
{
    window.location.href = url;
}