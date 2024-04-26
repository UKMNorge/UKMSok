<template>
    <div :class="active || searchInput.length > 0 ? 'active' : ''" class="as-container main-div as-margin-top-space-3 as-display-flex">
        <div class="as-display-flex search-div">
            <input :class="results.length > 0 ? 'results' : ''" class="search-input-arr-sys" @click="searchInputChanged()" @focus="() => active=true" @blur="() => active=false" v-model="searchInput" @input="searchInputChanged()" placeholder="Søk arrangørsystemet">
            <div class="button-icon">
                <button v-show="searchInput.length < 1" class="as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-space-2" @click="searchInputChanged()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg>
                </button>
                <button v-show="searchInput.length > 0" class="reset-all as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-space-2" @click="resetAll()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                </button>
            </div>
        </div>
        <div v-show="results.length > 0" class="results-div">
            <div class="result-item as-btn-hover-default" v-for="result in results">
                <a class="click-result" :href="result.siteUrl">
                    <p class="title">{{ result.title }}</p>
                    <span class="description">{{ result.description }}</span>
                </a>
            </div>
        </div>
        <div v-show="results.length > 0" class="search-results-overlay" @click="resetSearch()">

        </div>
    </div>
</template>

<script lang="ts">
import { SPAInteraction } from 'ukm-spa/SPAInteraction';

var ajaxurl : string = (<any>window).ajaxurl; // Kommer fra global
const spaInteraction = new SPAInteraction(null, ajaxurl);

export default {
    data() {
        return {
            searchInput : '' as string,
            results : [] as any,
            active : false,
        }
    },
    methods : {
        async searchInputChanged() {
            if( this.searchInput.length < 3 ) {
                this.results = [];
                return;
            }

            var data : any = {
                action: 'UKMsok_ajax',
                controller: 'searchWord',
                searchInput: this.searchInput,
            };
            var response = await spaInteraction.runAjaxCall('/', 'POST', data);

            this.results = response.results;

            return response;
        },
        resetSearch() {
            this.results = [];
        },
        resetAll() {
            this.searchInput = '';
            this.results = [];
        }
    },
    components : {
        // InputTextOverlay : InputTextOverlay
    },
}
</script>

<style>
#searchArrSysVue {
    width: 100%;
}
#wpadminbar .quicklinks .ab-empty-item {
    background: transparent !important;
}
</style>

<style scoped>
.main-div, .search-div, .search-input-arr-sys {
    width: 220px !important;
    transition: .15s width !important;
}
.main-div.active,
.main-div.active .search-div, 
.main-div.active .search-div .search-input-arr-sys {
    width: 500px !important;
    transition: .15s width !important;
    z-index: 9999999;
}
.search-input-arr-sys {
    border-radius: var(--radius-normal) !important;
    border: none;
    background: var(--color-primary-grey-lightest);
    height: 28px !important;
    margin: auto !important;
    padding: 3px 12px !important;
    padding-right: 50px !important;
    outline: none !important;
}
.search-input-arr-sys.results {
    border-bottom-right-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}
.button-icon {
    position: absolute !important;
    right: 15px;
    height: 100% !important;
    display: flex;
    background: transparent;
}
.button-icon button {
    background: transparent !important;
    border: none;
    padding: 0;
    margin: auto !important;
    z-index: 9999999;
}
.results-div {
    position: absolute !important;
    width: calc(100% - 20px) !important;
    top: 34px !important;
    box-shadow: 0 10px 7px #00000014 !important;
    background-color: #fff;
    border-radius: var(--radius-high) !important;
    border-top-right-radius: 0 !important;
    border-top-left-radius: 0 !important;
    background: #f4f4f4;
    z-index: 9999999;
    overflow: hidden !important;
}
.result-item {
    padding: 10px 12px !important;
    border-top: 1px solid #e0e0e0;
}
.click-result {
    display: contents !important;
    padding: 0 !important;
}
.click-result .title {
    font-weight: bold !important;
    margin: 0 !important;
    color: var(--color-primary-grey-dark) !important;
    line-height: 1 !important;
}
.click-result .description {
    margin: 0 !important;
    color: var(--color-primary-grey) !important;
    font-size: 12px !important;
    display: block !important;
    color: var(--color-primary-grey) !important;
    font-weight: 100 !important;
    line-height: 1 !important;
    padding-top: calc(var(--initial-space-box) / 2) !important;
    font-size: 11px !important
}

.search-results-overlay {
    position: fixed !important;
    width: 100% !important;
    height: 100% !important;
    top: 0 !important;
    left: 0 !important;
    background: var(--color-primary-grey-dark) !important;
    opacity: 0.4 !important;
    z-index: 9999998 !important;
}
.result-item:hover {
    background: var(--color-primary-grey-light) !important;
    border-color: var(--color-primary-grey-light) !important;
}
.button-icon button.reset-all {
    border-radius: 50% !important;
    background: var(--color-primary-grey-light) !important;
}
</style>
