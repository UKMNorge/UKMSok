import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import type ContentIndex from './ContentIndex';
declare var ajaxurl: string; // Kommer fra global


class Keyword {
    id : String = '-1';
    name : String;
    weight : Number;
    parent : ContentIndex;
    private spaInteraction : typeof SPAInteraction = new SPAInteraction(null, ajaxurl);


    constructor(id : String|null, name : String, weight : Number, parent : ContentIndex) {        
        this.name = name;
        this.weight = weight;
        this.parent = parent;
        
        if(id == null) {
            this.createKeyword();
        }
        else {
            this.id = id;
        }
        
    }

    public deleteKeyword() : void {
        this.runAjaxCall('DELETE');
    }

    public updateKeyword() : void {
        this.runAjaxCall('UPDATE');
    }

    private createKeyword() : void {
        this.runAjaxCall('CREATE');
    }
    
    private async runAjaxCall(method : String|null) : Promise<any> {
        var data : any = {
            action: 'UKMsok_ajax',
            controller: '',
            'content_index_id': this.parent.id,
            'weight': this.weight,
            'keyword_name': this.name,
            'keyword_id': this.id
        };

        if(method == 'CREATE') {
            data.controller = 'keywordAdd';
        }
        else if(method == 'DELETE') {
            data.controller = 'keywordDelete';
            data.keywordId = this.id;
        }
        else if(method == 'UPDATE') {
            data.controller = 'updateKeyword';
            data.keywordId = this.id;
        }
        else {
            throw new Error('Invalid method');
        }

        var response = await this.spaInteraction.runAjaxCall('/', 'POST', data);

        if(this.id == null) {
            this.id = response.id;
        }

        return response;
    }
}

export default Keyword;