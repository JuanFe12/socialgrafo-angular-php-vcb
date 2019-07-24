import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SocialgrafoComponent } from './socialgrafo.component';

describe('SocialgrafoComponent', () => {
  let component: SocialgrafoComponent;
  let fixture: ComponentFixture<SocialgrafoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SocialgrafoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SocialgrafoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
