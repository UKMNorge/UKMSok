<template>
    <div>
      <p>HER KOMMER SEARCHEN</p>
    </div>
</template>


<script lang="ts">
// Import av Vue
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class SearchArrSys extends Vue {
    // data som kommer fra initialisering av komponenten. Eks. <test-komponent :keys="['a', 'b']" :values="['value1', 'value2']"></test-komponent>
    @Prop() keys!: {navn : string, method : string}[];
    @Prop() values!: any[];

    public method() : void {

    }
}

// Registrering av komponenten
Vue.component('search-arr-sys', SearchArrSys);
</script>

<style>
  
</style>
