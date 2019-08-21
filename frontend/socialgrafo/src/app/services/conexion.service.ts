import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import { Tables } from '../models/tables';


@Injectable({
  providedIn: 'root'
})
export class ConexionService {

  public select_list = ["name", "email","username", "status"];
  public joined: boolean = false;
  public constraint_list = [
            {
                "table_field":"id",
                "condition":">",
                "value":"0"
            }
    ];

  //private URL = 'http://socialgrafo-back.local/index.php'
  private URL = 'http://localhost/socialgrafo/backend/web/index.php'

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

  GetData(joined, select_list,  constraint_list){
    const headers = new HttpHeaders();
    headers.append('Accept', 'application/json');
    console.log(this.constraint_list);
    this.http.post(this.getData, {joined: this.joined, select_list: this.select_list, constraint_list: this.constraint_list}, {headers: headers}).subscribe((data) =>{
      console.log(data);
      
    });
     //this.http.post(this.getData,{joined, select_list, constraint_list})

    
  }

  

}
