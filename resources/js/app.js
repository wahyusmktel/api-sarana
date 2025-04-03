import { createApp } from "vue";
import LoginView from "./components/LoginView.vue";
import PrimeVue from "primevue/config";

import "./assets/tailwind.css";
import "./assets/styles.scss";

const app = createApp(LoginView);

app.use(PrimeVue);

// Daftar komponen jika diperlukan
import InputText from "primevue/inputtext";
import Button from "primevue/button";

app.component("InputText", InputText);
app.component("Button", Button);

app.mount("#app");
