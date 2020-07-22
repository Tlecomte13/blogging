const follow = document.getElementById("follow")

if (follow != null){
    follow.addEventListener("click", function() {

        const notification = {
            currentUser : currentUser,
            follow      : window.location.pathname.split('/')[2],
            title       : "Nouveau abonné(e)",
            content     : "<strong>" + currentUser + "</strong>" + " vient de s'abonner !",
            icon        : "user",
            status      : "success"
        }

        socket.send(JSON.stringify(notification))
    });
}
const unsubscribe = document.getElementById("unfollow")

if (unsubscribe != null){
    unsubscribe.addEventListener("click", function() {

        const notification = {
            currentUser : currentUser,
            follow      : window.location.pathname.split('/')[2],
            title       : "Désabonner",
            content     : "<strong>" + currentUser + "</strong>" + " vient de se désabonner !",
            icon        : "user-slash",
            status      : "danger"
        }

        socket.send(JSON.stringify(notification))
    });
}