module.exports = (minutes) => {

    let timeoutId = undefined;

    const logout = function () {
        axios.post('/logout').then(() => {
            window.location.href = '/';
        });
    }

    const reset = function () {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(logout, minutes * 60000);
    }

    reset();

    window.addEventListener('mousemove', reset);
    window.addEventListener('mousedown', reset);
    window.addEventListener('click', reset);
    window.addEventListener('scroll', reset);
    window.addEventListener('keypress', reset);

}
