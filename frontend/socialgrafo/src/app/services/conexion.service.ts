import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import { Tables } from '../models/tables';


@Injectable({
  providedIn: 'root'
})
export class ConexionService {

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

  GetData(){
    let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');      
    headers.append('Content-Type', 'application/x-www-form-urlencoded');
    headers.append('Content-Type', 'application/json');
    headers.append('Access-Control-Allow-Credentials', "*");
    return this.http.get(this.URL+'?r=site/getdata',{headers: headers}).subscribe(data => {
      console.log(data);
    }); 
  }

  

}
