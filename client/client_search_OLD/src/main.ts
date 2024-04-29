import { createApp } from "vue";
import App from "./App.vue";

import hljs from "highlight.js/lib/core";
import javascript from "highlight.js/lib/languages/javascript";
import css from "highlight.js/lib/languages/css";
import xml from "highlight.js/lib/languages/xml";
import hljsVuePlugin from "@highlightjs/vue-plugin";
import "highlight.js/styles/base16/dracula.css";
import "../node_modules/ukm-components-vue3/dist/style.css";
import { Director } from 'ukm-spa/Director';

(() => {
    hljs.registerLanguage("xml", xml);
    hljs.registerLanguage("javascript", javascript);
    hljs.registerLanguage("css", css);
    
    // Import CSS for ukm-components-vue3
    
    const app = createApp(App);
    
    
    // Make Director global
    var director = new Director();
    (<any>window).director = director;


    app.use(hljsVuePlugin);

    app.mount("#searchArrSysVue");
})();