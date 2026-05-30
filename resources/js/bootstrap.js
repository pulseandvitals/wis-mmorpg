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

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});
