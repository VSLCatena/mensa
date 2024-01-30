import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MensaSummaryComponent } from './mensa-summary.component';

describe('MensaSummaryComponent', () => {
  let component: MensaSummaryComponent;
  let fixture: ComponentFixture<MensaSummaryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [MensaSummaryComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(MensaSummaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
