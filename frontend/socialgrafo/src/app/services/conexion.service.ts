import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';


@Injectable({
  providedIn: 'root'
})
export class ConexionService {


  private url = 'http://socialgrafo-back.local/index.php?r=site/gettables'
  private api = 'http://socialgrafo-back.local/index.php?r=site/getfields'
  private data = 'http://socialgrafo-back.local/index.php?r=site/getdata'


  constructor( public http: HttpClient) { }

    Gettables(){
      let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');      
      headers.append('Content-Type', 'application/x-www-form-urlencoded');
      headers.append('Content-Type', 'application/json');
      headers.append('Access-Control-Allow-Credentials', "*");
      return this.http.get(this.url,  {headers: headers}).subscribe(data => {
      console.log(data);
    }); 



}

  Getfileds(){
    let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');      
    headers.append('Content-Type', 'application/x-www-form-urlencoded');
    headers.append('Content-Type', 'application/json');
    headers.append('Access-Control-Allow-Credentials', "*");
    return this.http.get(this.api,{headers: headers}).subscribe(data => {
    console.log(data);
      }); 

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
