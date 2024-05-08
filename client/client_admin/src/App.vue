<template>
    <div>
        <div class="content-index">
            <h2>Søk indekser og nøkkelord</h2>
            <ul class="content-indexes">
                <li v-for="index in indexes">
                    <input v-model="index.title">
                    <input v-model="index.description">
                    <input v-model="index.siteUrl">
                    <button @click="saveIndex(index.id)" class="btn-style-keyword as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="-1 -2 20 25" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5 21h14a2 2 0 0 0 2-2V8l-5-5H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zM7 5h4v2h2V5h2v4H7V5zm0 8h10v6H7v-6z"></path></svg>
                    </button>
                        
                    <button @click="removeIndex(index.id)" class="btn-style-keyword as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>
                    <ul class="keywords as-margin-top-space-2 as-margin-bottom-space-2">
                        <li class="on-hover-displ" v-for="keyword in index.getKeywords()">
                            <span class="keyword-name">{{ keyword.name }}</span>
                            <span class="weight-value">Weight: {{ keyword.weight }}</span> <!-- Added weight property -->
                            <button @click="removeKeyword(keyword)" class="btn-style-keyword as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>

                        </li>
                        <li v-if="currentIdNewKeyword == index.id">
                            <input type="text" v-model="newKeyword.name" placeholder="Enter new keyword" />
                            <input type="number" v-model="newKeyword.weight" placeholder="Enter weight" /> <!-- Added weight input -->
                            <p>awaa</p>
                            <button @click="addKeyword(index.id, index)" class="btn-style-keyword add-icon as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>
                        </li>
                        <li v-else>
                            <button @click="addKeyword(index.id, index)" class="btn-style-keyword add-icon as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="as-display-flex">
                <input type="text" v-model="newIndex.name" placeholder="Enter new index name" />
                <button @click="addIndex" class="btn-style-keyword add-icon as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>

            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
// import type ContentIndex from './objects/ContentIndex';
import ContentIndex from './objects/ContentIndex';
import Keyword from './objects/Keyword';


var ajaxurl : string = (<any>window).ajaxurl; // Kommer fra global
const spaInteraction = new SPAInteraction(null, ajaxurl);

export default {
    data() {
        return {
            indexes: [] as Array<ContentIndex>,
            newIndex: { id: -1, name: '', description: '', link: '', keywords: [] },
            newKeyword: {id : -1, name: '', weight: 1},
            currentIdNewKeyword: '-1' as String,
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            var data : any = {
            action: 'UKMsok_ajax',
            controller: 'getIndexes',
            };

            var response = await spaInteraction.runAjaxCall('/', 'POST', data);
            console.log(response);

            for(var c of response.results) {
                var contentIndex = new ContentIndex(c.id, c.title, c.description, c.siteUrl, c.keywords);
                this.indexes.push(contentIndex);
            }

        },
        addIndex() {
            if (this.newIndex.name.trim() !== '') {
                // const newIndex = {
                //     id: this.indexes.length + 1,
                //     name: this.newIndex.name.trim(),
                //     keywords: [],
                // };
                

                // this.indexes.push(newIndex);
                // this.newIndex.name = '';
            }
        },
        removeIndex(indexId : String) {
            this.indexes = this.indexes.filter((index) => index.id !== indexId);
        },
        addKeyword(indexId : String, parent : any) {
            var kw = new Keyword(null, 'testNavn', 1, parent);
            // if(this.currentIdNewKeyword == '-1') {
            //     this.currentIdNewKeyword = indexId;
            //     return;
            // }

            // console.log(this.newKeyword.name);
            // if (this.newKeyword.name.trim() !== '') {
            //     const newKeyword = {
            //         id: this.indexes[indexId - 1].keywords.length + 1,
            //         name: this.newKeyword.name.trim(),
            //         weight: this.newKeyword.weight,
            //     };
            //     this.indexes[indexId - 1].keywords.push(newKeyword);
            //     this.newKeyword = { id: -1, name: '', weight: 1 };
                
            //     this._addKeyword(newKeyword);
            //     this.currentIdNewKeyword = -1;
            // }
        },
        async _addKeyword(keyword : any) {
            
            // var data : any = {
            //     action: 'UKMsok_ajax',
            //     controller: 'addKeyword',
            //     content_index_id: '1',
            //     keyword_name: 'Ajaxus',
            //     weight: '.7',
            //     description: 'description'
            // };
            // console.warn('cc');
            // var response = await spaInteraction.runAjaxCall('/', 'POST', data);
            // console.log('response');
            // console.log(response);
        },
        removeKeyword(keyword : Keyword) {
            var cIndex = keyword.parent;
            cIndex.removeKeyword(keyword.id);
        },
        saveIndex(indexId : String) {
            // const index = this.indexes.find((index) => index.id === indexId);
            // if (index) {
            //     console.log('Save index', index);
            // }
        },
    },
};
</script>

<style scoped>
input {
    border-radius: var(--radius-normal) !important;
    border: none;
    background: var(--color-primary-grey-light);
    height: 28px !important;
    margin: 8px !important;
    padding: 3px 12px !important;
    outline: none !important;
}
.content-index {
    margin-bottom: 20px;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

li span {
    margin-right: 10px;
}

input[type='text'] {
    margin-right: 10px;
}

button {
    padding: 5px 10px;
}
.content-indexes>li {
    border-bottom: solid 1px var(--color-primary-grey-light);
}
.keywords {
    margin-left: 200px;
}
.keywords>li {
    display: flex;
    align-items: center;
}
.weight-value {
    margin-right: 10px;
    font-weight: 100;
}
.keyword-name {
    font-size: 13px;
    font-weight: bold;
}
.btn-style-keyword {
    border-radius: 50% !important;
    background: var(--color-primary-grey-light) !important;
    transition: var(--transition-time-min);
    width: 24px !important;
    height: 24px !important;
    padding: 0px !important
}
.on-hover-displ .btn-style-keyword {
    visibility: hidden;
    transition: .2s !important;
}
.on-hover-displ:hover .btn-style-keyword {
    visibility: visible;
    transition: .2s !important;
}

.btn-style-keyword.add-icon {
    transform: rotateZ(45deg);
}

.btn-style-keyword:hover {
    transition: var(--transition-time-min);
    background: var(--color-primary-grey-medium) !important;
    border-color: var(--color-primary-grey-medium) !important;
    cursor: pointer;
}
</style>
