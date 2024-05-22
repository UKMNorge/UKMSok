<template>
    <div :class="active || searchInput.length > 0 ? 'active' : ''" class="as-container main-div as-margin-top-space-3 as-display-flex">
        <div class="as-display-flex search-div">
            <input :class="hasResults() ? 'results' : ''" class="search-input-arr-sys" @click="searchInputChanged()" @focus="() => active=true" @blur="() => active=false" v-model="searchInput" @input="debouncedSearchInputChanged" :placeholder="searchContext">
            <div class="button-icon">
                <button v-show="searchInput.length < 1" class="as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-space-2" @click="searchInputChanged()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg>
                </button>
                <button v-show="!loading && searchInput.length > 0" class="reset-all as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-space-2" @click="resetAll()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                </button>
                <button v-show="loading" class="loading-btn as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-space-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); animation: 1.5s ease-in-out 0s infinite normal none running rotation">
                        <circle cx="12" cy="20" r="2"></circle><circle cx="12" cy="4" r="2"></circle><circle cx="6.343" cy="17.657" r="2"></circle><circle cx="17.657" cy="6.343" r="2"></circle><circle cx="4" cy="12" r="2.001"></circle><circle cx="20" cy="12" r="2"></circle><circle cx="6.343" cy="6.344" r="2"></circle><circle cx="17.657" cy="17.658" r="2"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Søk arrangørsystemet -->
        <div class="all-results">
            <div v-if="results.length > 0" class="results-div">
                <template v-for="result in results">
                    <div class="result-item as-btn-hover-default" @click="clickOnly(result)">
                        <div class="click-result">
                            <p class="result-title">{{ result.title }}</p>
                            <div class="all-descriptions">
                                <span class="description">Arrangørsystemet</span>
                                <span class="description">{{ result.description }}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Søk områder -->
            <div v-if="omrader.length > 0" class="results-div">
                <template v-for="omrade in omrader">
                    <div class="result-item as-btn-hover-default" @click="clickOmrade(omrade)">
                        <div class="click-result">
                            <p class="result-title">{{ omrade.navn }}</p>
                            <div class="all-descriptions">
                                <span class="description">{{ omrade.type.charAt(0).toUpperCase() + omrade.type.slice(1)}}</span>
                                <span v-if="omrade.beskrivelse && omrade.beskrivelse.length > 0" class="description">{{ omrade.beskrivelse}}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Søk Blogs -->
            <div v-if="blogs.length > 0" class="results-div">
                <template v-for="blog in blogs">
                    <div class="result-item as-btn-hover-default"  @click="clickBlog(blog)">
                        <div class="click-result">
                            <p class="result-title">{{ blog.title }}</p>
                            <div class="all-descriptions">
                                <span class="description">{{ blog.site_type.charAt(0).toUpperCase() + blog.site_type.slice(1)}}</span>
                                <span v-if="blog.season" class="description">{{ blog.season }}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        

        <div v-show="hasResults()" class="search-results-overlay" @click="resetSearch()">

        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import debounce from 'lodash/debounce';

var ajaxurl : string = (<any>window).ajaxurl; // Kommer fra global
var isMainSite : string = (<any>window).isMainSite; // Kommer fra global
var blogName : string = (<any>window).blogName; // Kommer fra global
const spaInteraction = new SPAInteraction(null, ajaxurl);

@Component
export default class SearchArrSys extends Vue {
    searchInput: string = '';
    results: any[] = [];
    omrader : any[] = [];
    blogs: any[] = [];
    active: boolean = false;
    mainBlog : boolean = isMainSite == 'true';
    searchContext: string = 'Søk funksjoner, ' + (this.mainBlog ? 'fylker, kommuner, arrangementer... på arrangørsystemet' : 'innslag eller personer på ' + blogName);
    loading : boolean = false;
    searchLogId : number = -1;

    // Debounce the search input change handler
    debouncedSearchInputChanged = debounce(this.searchInputChanged, 300);

    public clickBlog(blog: any) {
        if(blog.site_type == 'arrangement') {
            window.location.href = blog.siteUrl+'/wp-admin/';
        }
        else {
            window.location.href = blog.siteUrl;
        }
        this.logClick(null, blog.siteUrl);
    }

    public clickOnly(result: string) {
        this.logClick((<any>result).id);
        window.location.href = (<any>result).siteUrl;
    }

    public clickOmrade(omrade: any) {
        if(omrade.type == 'kommune' || omrade.type == 'fylke') {
            var id = omrade.id;
            if (omrade.id.includes('_')) {
                var idParts = omrade.id.split('_');
                id = idParts[1];
            }
            
            window.location.href = `https://ukm.dev/wp-admin/user/index.php?page=UKMnettverket_${omrade.type}&omrade=${id}&type=${omrade.type}`;
        }
        else if(omrade.type == 'arrangement') {
            window.location.href = omrade.siteUrl+'/wp-admin/';
        }
        else {
            window.location.href = omrade.siteUrl;
        }

        this.logClick(null, omrade.siteUrl);
    }

    private logClick(resultId : String|null, text? : String) {
        var data : any = {
            action: 'UKMsok_ajax',
            controller: 'clickedResult',
            logId: this.searchLogId,
        };
        if(resultId) {
            data.resultId = resultId;
        }
        if(text) {
            data.text = text;
        }
        
        var response = spaInteraction.runAjaxCall('/', 'POST', data);
    }

    
    public async searchInputChanged() {
        this.resetSearch();
        if( this.searchInput.length < 3 ) {
            this.loading = false;
            return;
        }

        this.loading = true;

        var data : any = {
            action: 'UKMsok_ajax',
            controller: 'search',
            context : this.mainBlog ? 1 : 2, // 1: user, 2: arrangement
            searchInput: this.searchInput,
        };
        var response = await spaInteraction.runAjaxCall('/', 'POST', data);
        
        // The search input has changed while waiting for the response
        if(response.searchInput != this.searchInput) {
            return;
        }
        
        this.searchLogId = response.logId;
        this.loading = false;

        this.results = response.results;
        this.blogs = response.blogs;
        this.omrader = response.omrader;

        console.log(response);
        console.log(this.omrader);

        return response;
    };
    public resetSearch() {
        this.results = [];
        this.omrader = [];
        this.blogs = [];
    };
    
    public resetAll() {
        this.searchInput = '';
        this.results = [];
        this.omrader = [];
        this.blogs = [];
    };

    public hasResults() {
        return this.results.length > 0 || this.omrader.length > 0 || this.blogs.length > 0;
    }
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
.main-div {
    margin-top: 1px !important;
}
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
.all-results {
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
    max-height: 70vh;
    overflow-y: auto !important;
}
.result-item {
    padding: calc(var(--initial-space-box)*2) !important;
    border-top: 1px solid #e0e0e0;
}
.click-result {
    display: contents !important;
    padding: 0 !important;
}
.result-title {
    font-weight: 400 !important;
    margin: 0 !important;
    line-height: 1 !important;
    font-size: 15px !important;
}
.all-descriptions {
    display: table !important;
}
.click-result .description {
    float: left !important;
    border-radius: 10px !important;
    padding: calc(var(--initial-space-box)/2) var(--initial-space-box) !important;
    border: solid 1px var(--color-primary-grey-medium) !important;
    margin: 0 !important;
    margin-top: var(--initial-space-box) !important;
    margin-right: calc(var(--initial-space-box) / 2) !important;
    color: var(--color-primary-grey-light) !important;
    font-size: 12px !important;
    display: block !important;
    color: var(--color-primary-grey) !important;
    font-weight: 100 !important;
    line-height: 1 !important;
    padding-top: calc(var(--initial-space-box) / 2) !important;
    font-size: 12px !important
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
