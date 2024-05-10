<template>
    <div>
        <div class="content-index">
            <h2>Søk indekser og nøkkelord</h2>
            <div class="content-indexes as-container">
                <div v-for="index in indexes">
                    <div class="content-index-item as-card-1 as-padding-space-4">
                        <h3 class="title">{{ index.title }}</h3>
                        <div>
                            <v-text-field v-model="index.siteUrl" label="URL" variant="outlined"></v-text-field>
                            <v-text-field v-model="index.title" label="Tittel" variant="outlined"></v-text-field>
                            <v-text-field v-model="index.description" label="Beskrivelse" variant="outlined"></v-text-field>
                            <div>
                                <v-btn class="as-margin-right-space-1" @click="saveIndex(index.id)" prepend-icon variant="tonal">
                                    <v-icon>mdi-content-save</v-icon>
                                    Lagre  
                                </v-btn>

                                <v-btn @click="removeIndex(index.id)" prepend-icon variant="tonal">
                                    <v-icon>mdi-delete</v-icon>
                                    Slett  
                                </v-btn>
                            </div>
                        </div>
                        
                        <div class="keywords as-margin-top-space-4">
                            <h5>Nøkkelord:</h5>
                        </div>
                        <div class="keywords as-margin-top-space-1">
                            <div v-for="keyword in index.getKeywords()">
                                <v-chip class="as-margin-right-space-1" @click:close="removeKeyword(keyword)" closable>
                                    <span class="kw-name">{{ keyword.name }}</span>
                                    <span class="kw-weight">{{ keyword.weight }}</span>
                                    <!-- <button @click="removeKeyword(keyword)" class="btn-style-keyword mini-remove as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button> -->
                                </v-chip>
                            </div>
                            
                            
                            <div v-if="currentIdNewKeyword == index.id">
                                <v-chip @click:close="closeKeywordInput()" closable>
                                    <input type="text" v-model="newKeyword.name" placeholder="Keyword" />
                                    <input min="1" max="100" type="number" v-model="newKeyword.weight" placeholder="Weight" />
                                    <div class="as-padding-left-space-1">
                                        <v-btn @click="addKeyword(index.id, index)" density="compact" variant="tonal" icon="mdi-plus"></v-btn>
                                    </div>
                                </v-chip>
    
                                <!-- <input type="text" v-model="newKeyword.name" placeholder="Enter new keyword" />
                                <input type="number" v-model="newKeyword.weight" placeholder="Enter weight" />  -->
                            </div>
                            <div class="as-display-flex" v-else>
                                <v-btn class="as-margin-auto" @click="addKeyword(index.id, index)" density="compact" icon variant="tonal">
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                                <!-- <button @click="addKeyword(index.id, index)" class="btn-style-keyword add-icon as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            if(this.currentIdNewKeyword == '-1') {
                this.currentIdNewKeyword = indexId;
                return;
            }

            var kw = new Keyword(null, this.newKeyword.name, this.newKeyword.weight, parent);

            parent.addKeyword(kw);

            this.newKeyword.name = '';
            this.newKeyword.weight = 1;
            this.currentIdNewKeyword = '-1';
        },
        closeKeywordInput() {
            this.currentIdNewKeyword = '-1';
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
/* input {
    border-radius: var(--radius-normal) !important;
    border: none;
    background: var(--color-primary-grey-light);
    height: 28px !important;
    margin: 8px !important;
    padding: 3px 12px !important;
    outline: none !important;
} */
.content-index {
    margin-bottom: 20px;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    margin-bottom: 10px;
}

li span {
    margin-right: 10px;
}



button {
    padding: 5px 10px;
}
.content-indexes>li {
    border-bottom: solid 1px var(--color-primary-grey-light);
}
.keywords {
    display: flex;
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
.btn-style-keyword.mini-remove {
    width: 18px !important;
    height: 18px !important;

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
.inputs-vuetif {
    width: 100%;
}
.content-index-item {
    max-width: 1200px;
}
.content-index-item .title {
    margin-bottom: calc(2*var(--initial-space-box));
}
.kw-name {
    font-weight: 400;
    margin-right: calc(var(--initial-space-box)/2);
}
.kw-weight {
    font-weight: 100;
}
</style>
