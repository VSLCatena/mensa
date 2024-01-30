import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MensaOverviewScreenComponent } from './mensa-overview-screen.component';

describe('MensaOverviewScreenComponent', () => {
  let component: MensaOverviewScreenComponent;
  let fixture: ComponentFixture<MensaOverviewScreenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [MensaOverviewScreenComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(MensaOverviewScreenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
