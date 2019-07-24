import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConexionService } from '../../services/conexion.service';


@Component({
  selector: 'app-socialgrafo',
  templateUrl: './socialgrafo.component.html',
  styleUrls: ['./socialgrafo.component.sass']
})
export class SocialgrafoComponent implements OnInit {

  nuevosalbumnes: any[] = [];
  private url = 'socialgrafo-back.local/index.php?r=site/gettables'

  constructor( private http: HttpClient, 
               private connection: ConexionService) { 


               }

  ngOnInit() {
      this.connection.Gettables()
  }


  GetFields(){

    this.connection.Getfileds()
  }


  Getdata(){

    this.connection.GetData()

  }

}
