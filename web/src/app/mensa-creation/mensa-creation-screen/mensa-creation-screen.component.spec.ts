import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MensaCreationScreenComponent } from './mensa-creation-screen.component';

describe('MensaCreationScreenComponent', () => {
  let component: MensaCreationScreenComponent;
  let fixture: ComponentFixture<MensaCreationScreenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [MensaCreationScreenComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(MensaCreationScreenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
