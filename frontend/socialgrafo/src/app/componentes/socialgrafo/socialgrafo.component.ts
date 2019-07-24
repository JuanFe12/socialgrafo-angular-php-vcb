import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ConexionService } from '../../services/conexion.service';


@Component({
  selector: 'app-socialgrafo',
  templateUrl: './socialgrafo.component.html',
  styleUrls: ['./socialgrafo.component.sass']
})
export class SocialgrafoComponent implements OnInit {


  private url = 'socialgrafo-back.local/index.php?r=site/gettables'

  constructor( private http: HttpClient, 
               private connection: ConexionService) { }

  ngOnInit() {


  }

  consultaTables(){

    this.connection.post()
    
  } 

}
