import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import { Tables } from '../models/tables';


@Injectable({
  providedIn: 'root'
})
export class ConexionService {


  private url = 'http://socialgrafo-back.local/index.php?r=site/gettables'
  private api = 'http://socialgrafo-back.local/index.php?r=site/getfields'
  private data = 'http://socialgrafo-back.local/index.php?r=site/getdata'

  constructor( public http: HttpClient) { }

    Gettables(){
      return this.http.get<Tables[]>(this.url); 
    }

  Getfileds(table_list: string){
    //const headers = new HttpHeaders().set('Content-Type', 'multipart/form-data');
    //console.log(table_list);
    return this.http.get(this.api+"&table_list="+table_list);
  }
    GetData(){
      let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');      
      headers.append('Content-Type', 'application/x-www-form-urlencoded');
      headers.append('Content-Type', 'application/json');
      headers.append('Access-Control-Allow-Credentials', "*");
      return this.http.get(this.data,{headers: headers}).subscribe(data => {
      console.log(data);
        }); 
  
    }
}
