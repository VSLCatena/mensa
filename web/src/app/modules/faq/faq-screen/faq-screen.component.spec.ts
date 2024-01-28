import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FaqScreenComponent } from './faq-screen.component';

describe('FaqScreenComponent', () => {
  let component: FaqScreenComponent;
  let fixture: ComponentFixture<FaqScreenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [FaqScreenComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(FaqScreenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
