import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { SocialgrafoComponent } from './componentes/socialgrafo/socialgrafo.component';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';

import {HttpClientModule} from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { ChartsModule } from 'ng2-charts';
import { ForceComponent } from './componentes/force/force.component';
import { O2ChartComponent,ChartConst } from 'o2-chart-lib'; 


@NgModule({
  declarations: [
    AppComponent,
    SocialgrafoComponent,
    ForceComponent,   
    O2ChartComponent

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    ChartsModule,
    NgbModule,
 
    
  ],
  providers: [ChartConst],
  bootstrap: [AppComponent]
})
export class AppModule { }
