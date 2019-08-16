import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import { Tables } from '../models/tables';


@Injectable({
  providedIn: 'root'
})
export class ConexionService {

  public string = {nombre: 'felipe', email: 'juan@gmail.com', apellido: 'arias'};
  public joined: boolean;
  public constraint_list: {
    "data":[
        {
            "table_field":[
                {
                    "table_field":"<field_name>",
                    "condition":"< < >",
                    "value":[
                        {"field_option_1":"<field_option>"}
                    ]
                }
            ]
        }
    ]
}
  private URL = 'http://socialgrafo-back.local/index.php'

  private url = this.URL+'?r=site/gettables'
  private api = this.URL+'?r=site/getfields'
  //private data = this.URL+'?r=site/getdata'

  private relatedTables = this.URL+'?r=site/getrelatedtables'
  private getData = this.URL+'?r=site/getdatafront'

  constructor( public http: HttpClient) { }

  Gettables(){
    return this.http.get<Tables[]>(this.url); 
  }

  Getfileds(table_list: string){
    //const headers = new HttpHeaders().set('Content-Type', 'multipart/form-data');
    //console.log(table_list);
    return this.http.post(this.api,{table_list: table_list})
  }

  GetData(string, joined, constraint_list){

    return this.http.post(this.getData,{joined, string, constraint_list})
  }

  

}
