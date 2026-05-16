import { reactive } from "vue";

export const GlobalAlertStore = reactive({
    queue: [],
    current: null,
    showing: false,
});

export function pushAlert(message, type = "info", duration = 3000) {
    GlobalAlertStore.queue.push({
        message,
        type,
        duration,
    });

    if (!GlobalAlertStore.showing) {
        showNext();
    }
}

function showNext() {
    if (GlobalAlertStore.queue.length === 0) {
        GlobalAlertStore.current = null;
        GlobalAlertStore.showing = false;
        return;
    }

    const next = GlobalAlertStore.queue.shift();

    GlobalAlertStore.current = next;
    GlobalAlertStore.showing = true;

    setTimeout(() => {
        GlobalAlertStore.showing = false;
        showNext();
    }, next.duration);
}
