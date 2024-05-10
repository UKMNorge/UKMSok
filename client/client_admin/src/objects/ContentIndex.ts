import Keyword from "./Keyword";
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
declare var ajaxurl: string; // Kommer fra global


class ContentIndex {
    public id : String;
    public title : String;
    public description : String;
    public siteUrl : String;
    public context : String;
    private keywords : Array<Keyword> = [];
    private spaInteraction : typeof SPAInteraction = new SPAInteraction(null, ajaxurl);



    constructor(id : String|null, title : String, description : String, siteUrl : String, contextId : Number, keywordsList : Array<Keyword>) {
        if(id == null) {
            id = '-1';
        }

        this.id = id;
        this.title = title;
        this.description = description;
        this.siteUrl = siteUrl;

        this.context = this.getContextName(contextId);

        // if(id == null) {
        //     this.createContentIndex();
        // }

        for(var kw of keywordsList) {
            this.keywords.push(new Keyword(kw.id, kw.name, kw.weight, this));
        }
        
    }

    private getContextName(contextId : Number) : String {
        if(contextId == 1) {
            return 'Min Side';
        }
        else if(contextId == 2) {
            return 'Arrangement';
        }
        else if(contextId == 3) {
            return 'Kommune';
        }
        else if(contextId == 4) {
            return 'Kurs';
        }
        else if(contextId == 5) {
            return 'Kategori';
        }
        else {
            return 'Annet';
        }
    }

    private getContextId(contextId : String) : Number {
        if(contextId == 'Min Side') {
            return 1;
        }
        else if(contextId == 'Arrangement') {
            return 2;
        }
        else if(contextId == 'Kommune') {
            return 3;
        }
        else if(contextId == 'Kurs') {
            return 4;
        }
        else if(contextId == 'Kategori') {
            return 5;
        }
        else {
            return -1
        }
    }

    public getKeywords() : Array<Keyword> {
        return this.keywords;
    }

    public addKeyword(keyword : Keyword) : void {
        this.keywords.push(keyword);
    }
    
    public removeKeyword(keywordId : String) : boolean {
        for(var kw of this.getKeywords()) {
            if(kw.id == keywordId) {
                // this.keywords = this.keywords.filter(k => k.id !== keywordId);
                kw.deleteKeyword();
                return true;
            }
        }
        return false;
    }

    public deleteContentIndex() : void {
        this.runAjaxCall('DELETE');
    }

    public updatContentIndex() : void {
        this.runAjaxCall('UPDATE');
    }

    public async createContentIndex() {
        var response = await <any>this.runAjaxCall('CREATE');
        this.id = response.results['id'];
    }
    
    private async runAjaxCall(method : String|null) : Promise<any> {
        var data : any = {
            action: 'UKMsok_ajax',
            'title': this.title,
            'description': this.description,
            'site_url': this.siteUrl,
            'context_id': this.getContextId(this.context),
        };

        if(method == 'CREATE') {
            data.controller = 'contentIndexCreate';
        }
        else if(method == 'DELETE') {
            data.controller = 'contentIndexDelete';
            data.id = this.id;
        }
        else if(method == 'UPDATE'){
            data.controller = 'contentIndexUpdate';
            data.id = this.id;
        }

        var response = await this.spaInteraction.runAjaxCall('/', 'POST', data);

        if(this.id == null) {
            this.id = response.id;
        }

        return response;
    }
}

export default ContentIndex;