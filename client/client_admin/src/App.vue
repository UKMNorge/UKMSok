<template>
    <div>
        <div class="content-index">
            <h2>Søk indekser og nøkkelord</h2>
            <ul class="content-indexes">
                <li v-for="index in indexes" :key="index.id">
                    <input v-model="index.name">
                    <input v-model="index.description">
                    <input v-model="index.link">
                    <button @click="saveIndex(index.id)" class="btn-style-keyword as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="-1 -2 20 25" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5 21h14a2 2 0 0 0 2-2V8l-5-5H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zM7 5h4v2h2V5h2v4H7V5zm0 8h10v6H7v-6z"></path></svg>
                    </button>
                        
                    <button @click="removeIndex(index.id)" class="btn-style-keyword as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>
                    <ul class="keywords as-margin-top-space-2 as-margin-bottom-space-2">
                        <li class="on-hover-displ" v-for="keyword in index.keywords" :key="keyword.id">
                            <span class="keyword-name">{{ keyword.name }}</span>
                            <span class="weight-value">Weight: {{ keyword.weight }}</span> <!-- Added weight property -->
                            <button @click="removeKeyword(index.id, keyword.id)" class="btn-style-keyword as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>

                        </li>
                        <li v-if="currentIdNewKeyword == index.id">
                            <input type="text" v-model="newKeyword.name" placeholder="Enter new keyword" />
                            <input type="number" v-model="newKeyword.weight" placeholder="Enter weight" /> <!-- Added weight input -->
                            <button @click="addKeyword(index.id)" class="btn-style-keyword add-icon as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>
                        </li>
                        <li v-else>
                            <button @click="addKeyword(index.id)" class="btn-style-keyword add-icon as-btn-simple as-btn-hover-default as-padding-space-2 as-margin-left-space-1" style="" data-form-type=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></button>
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
export default {
    data() {
        return {
            indexes: [
                { id: 1, name: 'Index 1', description: 'test description', link: '/tana/wp-content/', keywords: [{ id: 1, name: 'Keyword 1', weight: 1 }, { id: 5, name: 'Keyword Zeta', weight: 2 }, { id: 5, name: 'Gentius', weight: 15 }] },
                { id: 2, name: 'Index 2', description: '', link: '', keywords: [{ id: 2, name: 'Keyword 2', weight: 3 }] },
                { id: 3, name: 'Index 3', description: '', link: '', keywords: [{ id: 3, name: 'Keyword 3', weight: 4 }] },
            ],
            newIndex: { id: -1, name: '', description: '', link: '', keywords: [] },
            newKeyword: {id : -1, name: '', weight: 1},
            currentIdNewKeyword: -1
        };
    },
    methods: {
        addIndex() {
            if (this.newIndex.name.trim() !== '') {
                const newIndex = {
                    id: this.indexes.length + 1,
                    name: this.newIndex.name.trim(),
                    keywords: [],
                };
                this.indexes.push(newIndex);
                this.newIndex.name = '';
            }
        },
        removeIndex(indexId : number) {
            this.indexes = this.indexes.filter((index) => index.id !== indexId);
        },
        addKeyword(indexId : number) {
            if(this.currentIdNewKeyword == -1) {
                this.currentIdNewKeyword = indexId;
                return;
            }

            if (this.newKeyword.name.trim() !== '') {
                const newKeyword = {
                    id: this.indexes[indexId - 1].keywords.length + 1,
                    name: this.newKeyword.name.trim(),
                    weight: this.newKeyword.weight,
                };
                this.indexes[indexId - 1].keywords.push(newKeyword);
                this.newKeyword = { id: -1, name: '', weight: 1 };
            }

            this.currentIdNewKeyword = -1;
        },
        removeKeyword(indexId : number, keywordId : number) {
            const index = this.indexes.find((index) => index.id === indexId);
            if (index) {
                index.keywords = index.keywords.filter((keyword) => keyword.id !== keywordId);
            }
        },
        saveIndex(indexId : number) {
            const index = this.indexes.find((index) => index.id === indexId);
            if (index) {
                console.log('Save index', index);
            }
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
