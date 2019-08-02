import { Component, OnInit,ElementRef, Input, OnChanges, ViewChild,ViewEncapsulation } from '@angular/core';
import { ConexionService } from '../../services/conexion.service';
import { Tables } from '../../models/tables';
import { Observable } from 'rxjs';
import { Campos } from 'src/app/models/campos';
import { HttpClient } from '@angular/common/http';
import * as d3 from 'd3';


declare var $:any;
@Component({
  selector: 'app-socialgrafo',
  templateUrl: './socialgrafo.component.html',
  styleUrls: ['./socialgrafo.component.sass'],
  encapsulation: ViewEncapsulation.None
})
export class SocialgrafoComponent implements OnInit {
  data: Observable<Campos>;
  tables: Tables[]
  table_list: []
  tables_fields: any =[]
  table_name: string
  fields: string


  constructor(private connection: ConexionService,private http: HttpClient) {
    this.data = this.http.get<Campos>('data/data.json');
  }

  ngOnInit() {
      this.connection.Gettables().subscribe((data )=> {
      this.tables = data;
      console.log(this.tables);
    })
  } 

  GetAllField(){
      const tabla = this.table_name

    this.connection.Getfileds(tabla).subscribe((field) =>{
        this.tables_fields = field
        console.log(field);
        /*for (let index = 0; index < field.length; index++) {
          field[index]
          for (let x = 0; x < field[index]['fields'].length; x++) {
             console.log(field[index]['fields'][x]);
          }
      }*/
    })
    this.tables_fields.map((data) =>{
      this.tables_fields = [this.tables][data]
      console.log(this.tables_fields);
    });
  } 

  Getdata(){
    this.connection.GetData()
  }

  button(){
    $('.ui.dropdown')
    .dropdown()
  ;
 }

}
