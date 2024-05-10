import Keyword from "./Keyword";
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
declare var ajaxurl: string; // Kommer fra global


class ContentIndex {
    public id : String;
    public title : String;
    public description : String;
    public siteUrl : String;
    private keywords : Array<Keyword> = [];
    private spaInteraction : typeof SPAInteraction = new SPAInteraction(null, ajaxurl);



    constructor(id : String, title : String, description : String, siteUrl : String, keywordsList : Array<Keyword>) {
        this.id = id;
        this.title = title;
        this.description = description;
        this.siteUrl = siteUrl;

        if(id == null) {
            this.createContentIndex();
        }

        for(var kw of keywordsList) {
            this.keywords.push(new Keyword(kw.id, kw.name, kw.weight, this));
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

    private createContentIndex() : void {
        this.runAjaxCall('CREATE');
    }
    
    private async runAjaxCall(method : String|null) : Promise<any> {
        var data : any = {
            action: 'UKMsok_ajax',
        };

        if(this.id == null) {
            data.controller = 'createContentIndex';
        }
        else if(method == 'DELETE') {
            data.controller = 'deleteContentIndex';
            data.id = this.id;
        }
        else if(method == 'UPDATE'){
            data.controller = 'updateContentIndex';
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