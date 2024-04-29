import Vue from "vue";
import SearchArrSys from "./components/SearchArrSys.vue";

let v = new Vue({
    el: "#searchArrSysVue",
    data: { 
        name: "World",
        activeTab : 'deltabruk'
    },
    
    components: {
        SearchArrSys,
    },

    mounted : function() {
    
    },

    methods : {
    },

    template: /*html*/`
    <div>    
        <div>
            <search-arr-sys ref="searcharrsys" :name="name" />
        </div>

    </div>

    `
});
