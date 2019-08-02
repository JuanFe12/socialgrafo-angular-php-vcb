import { Component,ElementRef, Input, OnChanges, ViewChild, ViewEncapsulation } from '@angular/core';
import * as d3 from 'd3';
import { Campos } from 'src/app/models/campos';


@Component({
  selector: 'app-force',
  encapsulation: ViewEncapsulation.None,
  templateUrl: './force.component.html',
  styleUrls: ['./force.component.sass']
})
export class ForceComponent   {
}
