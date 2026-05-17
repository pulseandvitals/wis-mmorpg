import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// --------------------
// Axios setup (you already have this)
// --------------------
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// --------------------
// Echo (Reverb setup)
// --------------------
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,

    wsHost: window.location.hostname,
    wsPort: 8080,
    wssPort: 8080,

    forceTLS: false,
    enabledTransports: ["ws", "wss"],

    disableStats: true,
});
