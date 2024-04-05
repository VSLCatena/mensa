import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MensaRegisterScreenComponent } from './mensa-register-screen.component';

describe('MensaRegisterScreenComponent', () => {
  let component: MensaRegisterScreenComponent;
  let fixture: ComponentFixture<MensaRegisterScreenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [MensaRegisterScreenComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(MensaRegisterScreenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
